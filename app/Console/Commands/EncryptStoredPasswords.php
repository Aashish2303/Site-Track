<?php

namespace App\Console\Commands;

use App\Services\PasswordVault;
use Illuminate\Console\Command;

class EncryptStoredPasswords extends Command
{
    protected $signature = 'security:encrypt-passwords';

    protected $description = 'Encrypt legacy recoverable passwords without changing login hashes';

    public function handle(PasswordVault $vault): int
    {
        $count = 0;
        while ($batch = $vault->upgradeLegacyBatch()) {
            $count += $batch;
        }
        $this->info("Secured {$count} legacy password copies. Login hashes were preserved.");

        return self::SUCCESS;
    }
}
