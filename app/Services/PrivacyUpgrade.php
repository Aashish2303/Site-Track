<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PrivacyUpgrade
{
    public function complete(): bool
    {
        return is_file(storage_path('app/private/privacy-v3.complete'));
    }

    public function run(bool $force = false): void
    {
        $marker = storage_path('app/private/privacy-v3.complete');
        if (is_file($marker) && ! $force) {
            return;
        }
        File::ensureDirectoryExists(dirname($marker), 0700);
        $lock = fopen($marker.'.lock', 'c');
        if (! flock($lock, LOCK_EX | LOCK_NB)) {
            fclose($lock);

            return;
        }
        try {
            if (is_file($marker) && ! $force) {
                return;
            }
            DB::table('users')->where('user_vpassword', '<>', '')->update(['user_vpassword' => '']);
            foreach (Settings::all() as $settings) {
                app(ServiceSecrets::class)->smtp($settings);
            }
            foreach (PrivateUploads::DIRECTORIES as $kind) {
                $dir = public_path('assets/admin/uploads/'.$kind);
                if (! is_dir($dir)) {
                    continue;
                }
                foreach (File::files($dir) as $file) {
                    if ($file->getFilename() !== '.htaccess') {
                        app(PrivateUploads::class)->migrate($kind, $file->getFilename());
                    }
                }
            }
            // Historical records can contain SQL bindings, SMTP payloads or old form input.
            foreach (File::glob(storage_path('logs/*.log*')) as $log) {
                if (is_file($log) && ! is_link($log)) {
                    file_put_contents($log, "[REDACTED: pre-audit log content removed]\n");
                }
            }
            foreach (is_dir(storage_path('framework/sessions')) ? File::files(storage_path('framework/sessions')) : [] as $session) {
                if ($session->getFilename() !== '.gitignore') {
                    unlink($session->getPathname());
                }
            }
            $temporary = storage_path('framework/cache/laravel-excel');
            if (is_dir($temporary)) {
                foreach (File::allFiles($temporary) as $file) {
                    if (! $file->isLink()) {
                        unlink($file->getPathname());
                    }
                }
                chmod($temporary, 0700);
            }
            // Database sessions and failed job payloads may retain historical request/mail data.
            if (\Schema::hasTable('sessions')) {
                DB::table('sessions')->delete();
            }
            if (\Schema::hasTable('failed_jobs')) {
                DB::table('failed_jobs')->delete();
            }
            if (\Schema::hasTable('personal_access_tokens')) {
                DB::table('personal_access_tokens')->delete();
            }
            if (\Schema::hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->delete();
            }
            if (\Schema::hasTable('jobs')) {
                DB::table('jobs')->delete();
            }
            file_put_contents($marker, json_encode(['version' => 3, 'run_id' => (string) Str::uuid(), 'completed_at' => now()->toIso8601String()]));
            chmod($marker, 0600);
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
