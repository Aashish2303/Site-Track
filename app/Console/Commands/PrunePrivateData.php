<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PrunePrivateData extends Command
{
    protected $signature = 'privacy:prune';

    protected $description = 'Remove expired reset tokens, sessions, stale export files and old application logs';

    public function handle(): int
    {
        DB::table('password_reset_tokens')->where('created_at', '<', now()->subMinutes(config('auth.passwords.users.expire', 60)))->delete();
        foreach ([
            storage_path('framework/sessions') => (int) config('session.lifetime', 120) * 60,
            storage_path('framework/cache/laravel-excel') => 86400,
            storage_path('logs') => 7 * 86400,
        ] as $directory => $ttl) {
            if (! is_dir($directory)) {
                continue;
            }
            foreach (File::files($directory) as $file) {
                if ($file->getFilename() === '.gitignore' || $file->isLink()) {
                    continue;
                }
                if ($file->getMTime() < time() - $ttl) {
                    unlink($file->getPathname());
                }
            }
        }
        $this->info('Expired application data pruned. No personal values are printed.');

        return self::SUCCESS;
    }
}
