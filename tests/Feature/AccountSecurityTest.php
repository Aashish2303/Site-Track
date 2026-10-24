<?php

namespace Tests\Feature;

use App\Mail\ResetAccountPassword;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AccountSecurityTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    private User $staff;

    private string $password = 'Test-only-account-2026';

    protected function setUp(): void
    {
        parent::setUp();
        if (! in_array(config('database.connections.mysql.database'), ['sitetrack_test', 'sitetrack_ci'], true)) {
            throw new \RuntimeException('Account security tests require an isolated database.');
        }
        Carbon::setTestNow('2026-10-24 12:00:00');
        $this->admin = $this->account(1);
        $role = Role::firstOrFail()->replicate();
        $role->role_name = 'Restricted test role';
        $role->role_status = 1;
        $role->save();
        $this->staff = $this->account($role->role_id);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function account(int $role): User
    {
        $user = User::firstOrFail()->replicate();
        $user->user_role = $role;
        $user->user_lname = 'test-'.Str::random(12);
        $user->user_email = $user->user_lname.'@example.test';
        $user->user_password = Hash::make($this->password);
        $user->user_vpassword = '';
        $user->user_status = 1;
        $user->save();

        return $user;
    }

    private function grant(string $permission): void
    {
        DB::table('role_permissions')->insert([
            'rpermission_role' => $this->staff->user_role,
            'rpermission_permission' => DB::table('module_permissions')->where('mpermission_slug', $permission)->value('mpermission_id'),
            'rpermission_added_on' => now(), 'rpermission_added_by' => $this->admin->user_id,
            'rpermission_updated_on' => now(), 'rpermission_updated_by' => $this->admin->user_id,
            'rpermission_status' => 1,
        ]);
    }

    public function test_login_validates_on_the_server_and_never_flashes_passwords(): void
    {
        $this->post('/', ['user_lname' => ['bad'], 'user_password' => $this->password])->assertSessionHasErrors('user_lname');
        $this->assertArrayNotHasKey('user_password', session('_old_input', []));
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])->assertRedirect(route('user.projects'));
        $this->assertAuthenticatedAs($this->staff);
    }

    public function test_wrong_unknown_and_disabled_accounts_have_the_same_login_error(): void
    {
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => 'incorrect'])->assertSessionHas('error', 'Unable to sign in with those details.');
        $this->post('/', ['user_lname' => 'missing-account', 'user_password' => 'incorrect'])->assertSessionHas('error', 'Unable to sign in with those details.');
        $this->staff->user_status = 0;
        $this->staff->save();
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])->assertSessionHas('error', 'Unable to sign in with those details.');
        $this->assertGuest();
        $this->assertArrayNotHasKey('user_password', session('_old_input', []));
    }

    public function test_login_limits_account_and_ip_pairs_and_expires_the_lockout(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => 'incorrect'])->assertRedirect();
        }
        $this->postJson('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])
            ->assertStatus(429)->assertHeader('Retry-After')->assertJsonPath('message', 'Too many attempts. Please try again later.');
        Carbon::setTestNow(now()->addMinutes(16));
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])->assertRedirect(route('user.projects'));
        $this->assertAuthenticatedAs($this->staff);
    }

    public function test_rotating_usernames_and_rotating_ip_addresses_do_not_bypass_login_limits(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->post('/', ['user_lname' => 'missing-'.$i, 'user_password' => 'incorrect'])->assertRedirect();
        }
        $this->post('/', ['user_lname' => 'another-missing', 'user_password' => 'incorrect'])->assertStatus(429)->assertSee('Please try again later');
        Carbon::setTestNow(now()->addMinutes(16));
        for ($i = 1; $i <= 25; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.$i])->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => 'incorrect'])->assertRedirect();
        }
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.26'])->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => 'incorrect'])->assertStatus(429);
    }

    public function test_every_protected_nonpersonal_endpoint_denies_an_unprivileged_role(): void
    {
        $this->actingAs($this->staff);
        $personal = ['user.dashboard', 'user.profile', 'user.change_password', 'user.check_current_password', 'user.logout', 'user.leave_project', 'user.private_file', 'user.delete_account'];
        foreach (Route::getRoutes() as $route) {
            if (! in_array('checkpermission', $route->middleware(), true) || in_array($route->getName(), $personal, true)) {
                continue;
            }
            $uri = '/'.preg_replace('/\{[^}]+\}/', '1', $route->uri());
            $method = in_array('POST', $route->methods(), true) ? 'POST' : 'GET';
            $this->call($method, $uri)->assertForbidden();
        }
        Route::middleware(['web', 'checkuser', 'checkpermission'])->post('/security-test-unknown-route', fn () => 'should never execute')->name('user.unregistered_capability');
        $this->post('/security-test-unknown-route')->assertForbidden();
    }

    public function test_read_only_users_permission_does_not_allow_password_views_or_user_writes(): void
    {
        $this->grant('user.users');
        $this->actingAs($this->staff)->withHeader('X-Requested-With', 'XMLHttpRequest')->postJson('/user/get_users')->assertOk()
            ->assertJsonMissingPath('data.0.user_password')->assertJsonMissingPath('data.0.user_vpassword')->assertDontSee($this->password);
        $this->get('/user/edit_user/'.$this->admin->user_id)->assertForbidden();
        $this->post('/user/edit_user/'.$this->admin->user_id, ['user_role' => $this->staff->user_role])->assertForbidden();
        $this->post('/user/add_project_status', ['project_id' => 1, 'status' => 3])->assertForbidden();
        $this->assertSame(1, (int) $this->admin->fresh()->user_role);
        $permission = DB::table('module_permissions')->where('mpermission_slug', 'user.users')->value('mpermission_id');
        DB::table('role_permissions')->where('rpermission_role', $this->staff->user_role)->where('rpermission_permission', $permission)->update(['rpermission_status' => 0]);
        $this->postJson('/user/get_users')->assertForbidden();
    }

    public function test_recoverable_passwords_are_erased_and_cannot_be_revealed(): void
    {
        DB::table('users')->where('user_id', $this->staff->user_id)->update(['user_vpassword' => base64_encode($this->password)]);
        $hash = $this->staff->user_password;
        $this->actingAs($this->admin)->get('/user/edit_user/'.$this->staff->user_id)->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')->assertDontSee($this->password);
        $this->assertSame('', $this->staff->fresh()->getRawOriginal('user_vpassword'));
        $this->assertSame($hash, $this->staff->fresh()->user_password);
    }

    public function test_new_users_have_hashes_only_and_invalid_input_cannot_create_an_account(): void
    {
        $this->actingAs($this->admin);
        $payload = ['user_role' => $this->staff->user_role, 'user_name' => 'New account', 'user_lname' => 'new-'.Str::random(8),
            'user_email' => 'new-'.Str::random(8).'@example.test', 'user_phone' => '1234567890', 'user_password' => $this->password];
        $count = User::count();
        $this->postJson('/user/add_user', array_replace($payload, ['user_role' => 999999, 'user_password' => 'short', 'user_image' => UploadedFile::fake()->create('shell.php', 1, 'application/x-httpd-php')]))
            ->assertUnprocessable()->assertJsonValidationErrors(['user_role', 'user_password', 'user_image']);
        $this->assertSame($count, User::count());
        $this->post('/user/add_user', $payload)->assertRedirect();
        $created = User::where('user_lname', $payload['user_lname'])->firstOrFail();
        $this->assertTrue(Hash::check($this->password, $created->user_password));
        $this->assertSame('', $created->getRawOriginal('user_vpassword'));
        $this->post('/user/edit_user/'.$created->user_id, array_replace($payload, ['user_password' => '']))->assertRedirect();
        $this->assertSame($created->user_password, $created->fresh()->user_password);
    }

    public function test_current_password_uses_the_hash_and_password_changes_validate_and_preserve_current_session(): void
    {
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])->assertRedirect(route('user.projects'));
        $this->withHeader('Accept', 'text/html')->post('/user/change_password', ['current_password' => $this->password, 'new_password' => 'short', 'confirm_password' => 'different'])->assertSessionHasErrors(['new_password', 'confirm_password']);
        $this->assertArrayNotHasKey('new_password', session('_old_input', []));
        $new = 'A-new-test-password-2026';
        $this->post('/user/change_password', ['current_password' => 'incorrect', 'new_password' => $new, 'confirm_password' => $new])->assertSessionHas('error');
        $this->assertTrue(Hash::check($this->password, $this->staff->fresh()->user_password));
        $this->post('/user/change_password', ['current_password' => $this->password, 'new_password' => $new, 'confirm_password' => $new])->assertSessionHas('success');
        $this->assertTrue(Hash::check($new, $this->staff->fresh()->user_password));
        $this->assertSame('', $this->staff->fresh()->user_vpassword);
        Auth::forgetGuards();
        $this->get('/user/profile')->assertOk();
    }

    public function test_recovery_is_generic_and_sends_a_single_use_link_instead_of_a_password(): void
    {
        Mail::fake();
        $generic = 'If the account is eligible, a password reset link will be sent.';
        $this->post('/forgot_password', ['user_email' => $this->staff->user_email])->assertRedirect(route('user.login'))->assertSessionHas('success', $generic);
        $mail = Mail::sent(ResetAccountPassword::class)->first();
        $this->assertNotNull($mail);
        $this->assertStringNotContainsString($this->password, $mail->render());
        $this->post('/forgot_password', ['user_email' => 'missing@example.test'])->assertSessionHas('success', $generic);
        $this->post('/forgot_password', ['user_email' => $this->staff->user_email])->assertSessionHas('success', $generic);
        Mail::assertSentCount(1);
        parse_str(parse_url($mail->resetUrl, PHP_URL_FRAGMENT), $query);
        $token = $query['token'];
        $this->assertNull(parse_url($mail->resetUrl, PHP_URL_QUERY));
        $this->assertStringNotContainsString($this->staff->user_email, $mail->resetUrl);
        $this->assertTrue(Password::tokenExists($this->staff, $token));
        $this->get('/reset-password')->assertOk()->assertHeader('Referrer-Policy', 'no-referrer');
        $data = ['user_email' => $this->staff->user_email, 'token' => $token, 'password' => 'Reset-test-password-2026', 'password_confirmation' => 'Reset-test-password-2026'];
        $this->post('/reset-password', $data)->assertRedirect(route('user.login'))->assertSessionHas('success');
        $this->assertTrue(Hash::check($data['password'], $this->staff->fresh()->user_password));
        $this->post('/reset-password', $data)->assertSessionHas('error', 'Unable to reset the password. Request a new link.');
    }

    public function test_reset_links_expire_and_old_authenticated_sessions_are_rejected_after_password_changes(): void
    {
        $token = Password::createToken($this->staff);
        Carbon::setTestNow(now()->addMinutes(61));
        $data = ['user_email' => $this->staff->user_email, 'token' => $token, 'password' => 'Reset-test-password-2026', 'password_confirmation' => 'Reset-test-password-2026'];
        $this->post('/reset-password', $data)->assertSessionHas('error', 'Unable to reset the password. Request a new link.');
        $this->post('/', ['user_lname' => $this->staff->user_lname, 'user_password' => $this->password])->assertRedirect(route('user.projects'));
        $this->get('/user/profile')->assertOk();
        DB::table('users')->where('user_id', $this->staff->user_id)->update(['user_password' => Hash::make('Other-test-password-2026')]);
        Auth::forgetGuards();
        $this->get('/user/profile')->assertRedirect(route('user.login'));
        $this->assertGuest();
    }

    public function test_disabled_users_and_roles_cannot_keep_using_existing_sessions(): void
    {
        $this->actingAs($this->staff);
        DB::table('roles')->where('role_id', $this->staff->user_role)->update(['role_status' => 0]);
        $this->getJson('/user/profile')->assertUnauthorized()->assertJsonPath('message', 'Please sign in again.');
        $this->assertGuest();
    }

    public function test_invalid_quantities_dates_and_statuses_do_not_reach_database_writes(): void
    {
        $site = Project::withoutGlobalScopes()->firstOrFail();
        $this->actingAs($this->admin)->withSession(['workspace_project_id' => $site->project_id]);
        $this->postJson('/user/add_boq_item', ['project_id' => $site->project_id, 'pboq_item' => 'Test', 'pboq_unit' => 'm3', 'pboq_qty' => -1, 'pboq_rate' => 'invalid'])->assertUnprocessable()->assertJsonValidationErrors(['pboq_qty', 'pboq_rate']);
        $this->postJson('/user/add_daily_work', ['dwork_date' => 'not-a-date', 'dwork_qty' => -12])->assertUnprocessable()->assertJsonValidationErrors(['dwork_date', 'dwork_qty']);
        $this->postJson('/user/add_project_status', ['project_id' => $site->project_id, 'status' => 999])->assertUnprocessable()->assertJsonValidationErrors('status');
        $this->get('/user/user_status/'.$this->staff->user_id.'/1')->assertStatus(405);
    }

    public function test_dashboard_does_not_expose_modules_that_the_role_cannot_read(): void
    {
        $this->actingAs($this->staff)->get('/user/dashboard')->assertOk()
            ->assertDontSee('Reported project progress')->assertDontSee('Needs your attention')
            ->assertDontSee('data-stat="orders"', false)->assertDontSee('data-stat="team"', false);
    }

    public function test_invalid_spreadsheet_rows_cannot_leave_a_partially_created_project(): void
    {
        $prototype = Project::withoutGlobalScopes()->firstOrFail();
        $sheet = new Spreadsheet;
        $sheet->getActiveSheet()->fromArray([
            ['No', 'Item', 'Unit', 'Quantity', 'Rate', 'Notes'],
            [1, 'Invalid imported quantity', 'm3', -5, 100, 'Test'],
        ]);
        $path = tempnam(sys_get_temp_dir(), 'security-boq-');
        try {
            (new Xlsx($sheet))->save($path);
            $name = 'Invalid import '.Str::random(8);
            $count = Project::withoutGlobalScopes()->count();
            $this->actingAs($this->admin)->postJson('/user/add_project', [
                'project_name' => $name, 'project_location' => 'Test location',
                'project_client' => $prototype->project_client, 'project_engineer' => $this->admin->user_id,
                'project_start' => '2026-10-24', 'project_end' => '2026-11-24', 'project_budget' => 1000,
                'boq_file' => new UploadedFile($path, 'boq.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ])->assertUnprocessable()->assertJsonValidationErrors('boq_file');
            $this->assertSame($count, Project::withoutGlobalScopes()->count());
            $this->assertDatabaseMissing('projects', ['project_name' => $name]);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_sessions_created_before_the_security_update_require_a_fresh_login(): void
    {
        $this->actingAs($this->staff)->withSession(['password_hash_web' => null])
            ->getJson('/user/profile')->assertUnauthorized()->assertJsonPath('message', 'Please sign in again.');
        $this->assertGuest();
    }
}
