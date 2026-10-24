<?php

namespace App\Console\Commands;

use App\Security\DatabaseReadiness;
use App\Security\RuntimeConfiguration;
use Illuminate\Console\Command;
use Illuminate\Contracts\Debug\ExceptionHandler;

class SecurityCheck extends Command
{
    protected $signature = 'security:check {--database : Verify the actual database session TLS without printing connection details}';

    protected $description = 'Validate runtime configuration without printing credentials or connecting to external services';

    public function handle(RuntimeConfiguration $configuration): int
    {
        $errors = $configuration->problems();
        foreach ($errors as $name => $message) {
            $this->error($name.': '.$message);
        }
        if (! $errors) {
            $this->info('PASS: required application/database configuration is present. No secret values are printed.');
        }
        if ($this->option('database')) {
            try {
                $checks = app(DatabaseReadiness::class)->inspect();
            } catch (\Throwable $e) {
                $this->error('Database transport inspection failed; no connection details are displayed.');
                app(ExceptionHandler::class)->report($e);

                return self::FAILURE;
            }
            foreach ($checks as $name => $passed) {
                $this->line(($passed ? 'PASS: ' : 'FAIL: ').$name);
            }
            if (! $checks['connection_tls'] || ! $checks['certificate_verification']) {
                return self::FAILURE;
            }
            $this->warn('Public-port authentication/firewall exposure must also be verified with hosting/network access.');
        }

        return $errors ? self::FAILURE : self::SUCCESS;
    }
}
