<?php

namespace Tests\Feature;

use App\Logging\RedactLogs;
use App\Models\DailyWork;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Services\PrivacyUpgrade;
use App\Services\PrivateUploads;
use App\Services\PublicReference;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\EncryptedStore;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class DataPrivacyTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    private User $staff;

    private string $password = 'Private-test-password-2026';

    protected function setUp(): void
    {
        parent::setUp();
        if (! in_array(DB::getDatabaseName(), ['sitetrack_test', 'sitetrack_ci'], true)) {
            throw new \RuntimeException('Privacy tests require an isolated database.');
        }
        Storage::fake('personal');
        Storage::fake('evidence');
        $this->admin = $this->account(1);
        $role = Role::firstOrFail()->replicate();
        $role->role_name = 'Privacy test role';
        $role->role_status = 1;
        $role->save();
        $this->staff = $this->account($role->role_id);
    }

    private function account(int $role): User
    {
        $user = User::firstOrFail()->replicate();
        $name = 'privacy-'.Str::random(10);
        $user->forceFill(['user_lname' => $name, 'user_name' => 'Private test person '.$name, 'user_email' => $name.'@example.test',
            'user_phone' => '9912345678', 'user_role' => $role, 'user_image' => null,
            'user_password' => Hash::make($this->password), 'user_vpassword' => '', 'user_status' => 1])->save();

        return $user;
    }

    private function grant(string $permission): void
    {
        DB::table('role_permissions')->insert(['rpermission_role' => $this->staff->user_role,
            'rpermission_permission' => DB::table('module_permissions')->where('mpermission_slug', $permission)->value('mpermission_id'),
            'rpermission_added_on' => now(), 'rpermission_added_by' => $this->admin->user_id,
            'rpermission_updated_on' => now(), 'rpermission_updated_by' => $this->admin->user_id, 'rpermission_status' => 1]);
    }

    public function test_all_table_endpoints_return_only_their_display_fields(): void
    {
        $site = Project::withoutGlobalScopes()->firstOrFail();
        $this->actingAs($this->admin)->withSession(['workspace_project_id' => $site->project_id])->withHeader('X-Requested-With', 'XMLHttpRequest');
        foreach (config('response-fields.user') as $action => $fields) {
            $response = $this->postJson(route('user.'.$action))->assertOk();
            $data = $response->json();
            $this->assertArrayHasKey('data', $data, $action);
            $this->assertEmpty(array_diff(array_keys($data), ['data', 'draw', 'recordsTotal', 'recordsFiltered', 'error']), $action);
            foreach ($data['data'] as $row) {
                $this->assertEmpty(array_diff(array_keys($row), $fields), $action);
                $this->assertArrayNotHasKey('user_password', $row);
                $this->assertArrayNotHasKey('user_vpassword', $row);
                foreach (array_keys($row) as $key) {
                    $this->assertDoesNotMatchRegularExpression('/(_id|_added_by|_updated_by|_added_on|_updated_on)$/', $key);
                }
            }
        }
    }

    public function test_read_only_user_directory_returns_only_the_current_account(): void
    {
        $this->grant('user.users');
        $this->actingAs($this->staff)->withHeader('X-Requested-With', 'XMLHttpRequest')->postJson('/user/get_users')
            ->assertOk()->assertJsonPath('recordsTotal', 1)->assertJsonPath('data.0.user_email', $this->staff->user_email)
            ->assertDontSee($this->admin->user_email)->assertDontSee($this->admin->user_name);
        $this->get('/api/user')->assertNotFound();
    }

    public function test_opaque_form_and_route_references_round_trip_without_numeric_primary_keys(): void
    {
        $page = $this->actingAs($this->admin)->get('/user/edit_user/'.$this->staff->user_id)->assertOk();
        $this->assertDoesNotMatchRegularExpression('~/user/edit_user/[0-9]+~', $page->getContent());
        $dom = new \DOMDocument;
        @$dom->loadHTML($page->getContent());
        $xpath = new \DOMXPath($dom);
        $action = $xpath->evaluate('string(//form[@id="user_form"]/@action)');
        $role = $xpath->evaluate('string(//select[@name="user_role"]/option[@selected]/@value)');
        $this->assertStringContainsString('/pr_', $action);
        $this->assertStringStartsWith('pr_', $role);
        $this->post($action, ['user_role' => $role, 'user_name' => $this->staff->user_name, 'user_lname' => $this->staff->user_lname,
            'user_email' => $this->staff->user_email, 'user_phone' => '8812345678', 'user_password' => ''])->assertRedirect();
        $this->assertSame('8812345678', $this->staff->fresh()->user_phone);
        $wrongResource = app(PublicReference::class)->encode('projects', $this->staff->user_role);
        $this->post($action, ['user_role' => $wrongResource])->assertNotFound();
    }

    public function test_private_profile_uploads_are_not_public_and_are_not_visible_to_other_staff(): void
    {
        $payload = ['user_role' => $this->staff->user_role, 'user_name' => $this->staff->user_name, 'user_lname' => $this->staff->user_lname,
            'user_email' => $this->staff->user_email, 'user_phone' => $this->staff->user_phone, 'user_password' => '', 'user_image' => UploadedFile::fake()->image('personal-name-and-device.png')];
        $this->actingAs($this->admin)->post('/user/edit_user/'.$this->staff->user_id, $payload)->assertRedirect();
        $name = $this->staff->fresh()->user_image;
        Storage::disk('personal')->assertExists('profile/'.$name);
        $this->assertStringNotContainsString('personal-name', $name);
        $refs = app(PublicReference::class);
        $url = route('user.private_file', ['kind' => 'profile', 'reference' => $refs->encode('users', $this->staff->user_id)]);
        $this->actingAs($this->staff)->get($url)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $other = $this->account($this->staff->user_role);
        $this->actingAs($other)->get($url)->assertForbidden();
        Auth::logout();
        $this->get($url)->assertRedirect(route('user.login'));
    }

    public function test_cookie_flags_and_encrypted_session_storage(): void
    {
        config(['session.secure' => true]);
        $response = $this->get('/')->assertOk();
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                $this->assertTrue($cookie->isHttpOnly());
                $this->assertTrue($cookie->isSecure());
                $this->assertSame('lax', $cookie->getSameSite());
            }
            $this->assertNotSame('XSRF-TOKEN', $cookie->getName());
        }
        $this->assertTrue(config('session.encrypt'));
        $dir = sys_get_temp_dir().'/privacy-session-'.Str::random(12);
        File::ensureDirectoryExists($dir);
        try {
            $session = new EncryptedStore('privacy', new FileSessionHandler(new Filesystem, $dir, 120), app('encrypter'), Str::random(40));
            $session->start();
            $session->put('email', $this->staff->user_email);
            $session->save();
            $contents = file_get_contents($dir.'/'.$session->getId());
            $this->assertStringNotContainsString($this->staff->user_email, $contents);
        } finally {
            File::deleteDirectory($dir);
        }
    }

    public function test_log_boundary_removes_messages_context_exceptions_sql_and_tokens(): void
    {
        $path = sys_get_temp_dir().'/privacy-log-'.Str::random(12).'.log';
        config(['logging.channels.single.path' => $path, 'logging.channels.single.tap' => [RedactLogs::class]]);
        Log::forgetChannel('single');
        try {
            Log::channel('single')->error('Person '.$this->staff->user_email, ['name' => 'Sensitive Person', 'phone' => '9912345678',
                'password' => $this->password, 'token' => 'private-reset-token', 'exception' => new \RuntimeException('SQL with '.$this->staff->user_email),
                'event' => 'Sensitive Person', 'correlation_id' => 'Sensitive Person', 'exception_class' => 'Sensitive Person']);
            $contents = file_get_contents($path);
            foreach ([$this->staff->user_email, 'Sensitive Person', '9912345678', $this->password, 'private-reset-token', 'SQL with'] as $pii) {
                $this->assertStringNotContainsString($pii, $contents);
            }
            $this->assertStringContainsString('[REDACTED]', $contents);
        } finally {
            Log::forgetChannel('single');
            if (is_file($path)) {
                unlink($path);
            }
        }
        $this->assertSame('array', config('mail.mailers.log.transport'));
    }

    public function test_account_deletion_removes_only_the_current_person_and_anonymises_work_history(): void
    {
        $work = DailyWork::withoutGlobalScopes()->firstOrFail()->replicate();
        $work->dwork_added_by = $this->staff->user_id;
        $work->dwork_updated_by = $this->staff->user_id;
        $work->dwork_remarks = 'Contact '.$this->staff->user_email;
        $work->save();
        $this->staff->user_image = 'private-profile.jpg';
        $this->staff->save();
        Storage::disk('personal')->put('profile/private-profile.jpg', 'private image');
        Password::createToken($this->staff);
        DB::table('sessions')->insert(['id' => 'privacy-legacy-session', 'user_id' => $this->staff->user_id, 'payload' => 'private payload', 'last_activity' => time()]);
        DB::table('personal_access_tokens')->insert(['tokenable_type' => User::class, 'tokenable_id' => $this->staff->user_id,
            'name' => 'privacy token', 'token' => hash('sha256', Str::random(40)), 'abilities' => '["*"]', 'created_at' => now(), 'updated_at' => now()]);
        $this->actingAs($this->staff)->post('/user/account/delete', ['current_password' => $this->password, 'confirmation' => 'DELETE', 'user_id' => $this->admin->user_id])
            ->assertRedirect(route('user.login'))->assertSessionHas('success');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['user_id' => $this->staff->user_id]);
        $this->assertDatabaseHas('users', ['user_id' => $this->admin->user_id, 'user_email' => $this->admin->user_email]);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $this->staff->user_email]);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $this->staff->user_id]);
        $this->assertDatabaseMissing('sessions', ['user_id' => $this->staff->user_id]);
        Storage::disk('personal')->assertMissing('profile/private-profile.jpg');
        $work = $work->fresh();
        $this->assertStringNotContainsString($this->staff->user_email, $work->dwork_remarks);
        $this->assertNotEquals($this->staff->user_id, $work->dwork_added_by);
        $this->assertSame('Deleted account', User::findOrFail($work->dwork_added_by)->user_name);
    }

    public function test_deletion_needs_password_confirmation_and_preserves_the_last_administrator(): void
    {
        $this->actingAs($this->staff)->postJson('/user/account/delete', ['current_password' => 'incorrect', 'confirmation' => 'DELETE'])->assertUnprocessable();
        $this->assertDatabaseHas('users', ['user_id' => $this->staff->user_id]);
        DB::table('users')->where('user_role', 1)->where('user_id', '<>', $this->admin->user_id)->update(['user_status' => 0]);
        $this->actingAs($this->admin)->postJson('/user/account/delete', ['current_password' => $this->password, 'confirmation' => 'DELETE'])->assertUnprocessable()->assertJsonValidationErrors('confirmation');
        $this->assertDatabaseHas('users', ['user_id' => $this->admin->user_id]);
    }

    public function test_privacy_upgrade_purges_legacy_copies_logs_sessions_and_public_uploads(): void
    {
        $oldStorage = app()->storagePath();
        $oldPublic = app()->publicPath();
        $root = sys_get_temp_dir().'/privacy-upgrade-'.Str::random(12);
        app()->useStoragePath($root.'/storage');
        app()->usePublicPath($root.'/public');
        foreach (['storage/logs', 'storage/framework/sessions', 'public/assets/admin/uploads/profile'] as $dir) {
            File::ensureDirectoryExists($root.'/'.$dir);
        }
        try {
            file_put_contents($root.'/storage/logs/legacy.log', $this->staff->user_email.' '.$this->password);
            file_put_contents($root.'/storage/framework/sessions/legacy', $this->staff->user_email);
            file_put_contents($root.'/public/assets/admin/uploads/profile/legacy.jpg', 'private legacy image');
            DB::table('users')->where('user_id', $this->staff->user_id)->update(['user_vpassword' => base64_encode($this->password)]);
            app(PrivacyUpgrade::class)->run();
            $this->assertSame('', $this->staff->fresh()->user_vpassword);
            $this->assertStringNotContainsString($this->staff->user_email, file_get_contents($root.'/storage/logs/legacy.log'));
            $this->assertFileDoesNotExist($root.'/storage/framework/sessions/legacy');
            $this->assertFileDoesNotExist($root.'/public/assets/admin/uploads/profile/legacy.jpg');
            Storage::disk('personal')->assertExists('profile/legacy.jpg');
            $this->assertFileExists($root.'/storage/app/private/privacy-v3.complete');
            app(PrivacyUpgrade::class)->run();
        } finally {
            app()->useStoragePath($oldStorage);
            app()->usePublicPath($oldPublic);
            File::deleteDirectory($root);
        }
    }

    public function test_uploaded_images_lose_embedded_camera_and_gps_metadata(): void
    {
        $file = UploadedFile::fake()->image('personal-photo.jpg');
        $bytes = file_get_contents($file->getRealPath());
        $metadata = "Exif\0\0PRIVATE CAMERA AND GPS METADATA";
        file_put_contents($file->getRealPath(), substr($bytes, 0, 2)."\xFF\xE1".pack('n', strlen($metadata) + 2).$metadata.substr($bytes, 2));
        $name = app(PrivateUploads::class)->store($file, 'profile');
        $this->assertStringNotContainsString('PRIVATE CAMERA AND GPS METADATA', Storage::disk('personal')->get('profile/'.$name));
    }

    public function test_legacy_vendor_exports_treat_untrusted_text_as_text_not_excel_formulas(): void
    {
        $vendor = Vendor::firstOrFail()->replicate();
        $formula = '=WEBSERVICE("https://example.invalid/private")';
        $vendor->vendor_name = $formula;
        $vendor->save();
        $response = $this->actingAs($this->admin)->get('/user/vendors_download_excel')->assertOk();
        $book = IOFactory::load($response->baseResponse->getFile()->getPathname());
        $found = false;
        foreach ($book->getActiveSheet()->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                if ($cell->getValue() === $formula) {
                    $found = true;
                    $this->assertNotSame('f', $cell->getDataType());
                }
            }
        }
        $this->assertTrue($found);
    }
}
