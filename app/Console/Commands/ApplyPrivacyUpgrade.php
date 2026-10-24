<?php

namespace App\Console\Commands;

use App\Services\PrivacyUpgrade;
use Illuminate\Console\Command;

class ApplyPrivacyUpgrade extends Command
{
    protected $signature = 'privacy:upgrade {--force : Repeat cleanup after restoring old backups}';

    protected $description = 'Purge recoverable login passwords, remove legacy logs and privatise personal uploads';

    public function handle(PrivacyUpgrade $upgrade): int
    {
        $upgrade->run((bool) $this->option('force'));
        $this->info('Privacy upgrade completed. No personal values are printed.');

        return self::SUCCESS;
    }
}
