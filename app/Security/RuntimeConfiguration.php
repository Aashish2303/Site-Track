<?php

namespace App\Security;

use Illuminate\Database\ConfigurationUrlParser;
use Illuminate\Encryption\Encrypter;

class RuntimeConfiguration
{
    public function problems(?array $configuration = null): array
    {
        $c = $configuration ?? config()->all();
        $errors = [];
        $key = $c['app']['key'] ?? null;
        $raw = is_string($key) && str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7), true) : $key;
        if (! is_string($raw) || ! Encrypter::supported($raw, $c['app']['cipher'] ?? 'AES-256-CBC')) {
            $errors['APP_KEY'] = 'A valid application encryption/authentication key is required. Generate it once; do not replace an existing deployment key.';
        }
        $production = ! in_array($c['app']['env'] ?? 'production', ['local', 'testing'], true);
        $url = $c['app']['url'] ?? '';
        if (! filter_var($url, FILTER_VALIDATE_URL) || ($production && parse_url($url, PHP_URL_SCHEME) !== 'https')) {
            $errors['APP_URL'] = 'Set the canonical application URL; production requires HTTPS.';
        }
        $asset = $c['app']['asset_url'] ?? '/';
        if (is_string($asset) && (str_starts_with($asset, '//') || (preg_match('~^https?://~i', $asset) && parse_url($asset, PHP_URL_HOST) !== parse_url($url, PHP_URL_HOST)))) {
            $errors['ASSET_URL'] = 'Scripts and assets must use the application origin.';
        }
        if (in_array('*', $c['cors']['allowed_origins'] ?? [], true) || ! empty($c['cors']['allowed_origins_patterns'])) {
            $errors['CORS_ALLOWED_ORIGINS'] = 'Use explicit frontend origins, not wildcard origins or patterns.';
        }
        $driver = $c['database']['default'] ?? 'mysql';
        if ($production && $driver !== 'mysql') {
            $errors['DB_CONNECTION'] = 'This application requires its supported MySQL/MariaDB database in production.';
        }
        try {
            $db = (new ConfigurationUrlParser)->parseConfiguration($c['database']['connections'][$driver] ?? []);
        } catch (\Throwable $e) {
            $db = [];
            $errors['DATABASE_URL'] = 'The database URL is invalid. Use a valid URL or the documented DB_* variables.';
        }
        if ($driver === 'mysql') {
            if ($production) {
                $errors += TlsMySqlConnector::configurationProblems($db, $db['options'] ?? []);
            }
            foreach (['database' => 'DB_DATABASE / DATABASE_URL', 'username' => 'DB_USERNAME / DATABASE_URL'] as $field => $name) {
                if (! is_string($db[$field] ?? null) || trim($db[$field]) === '') {
                    $errors[$name] = 'Set the database connection field explicitly.';
                }
            }
            if (empty($db['host']) && empty($db['unix_socket'])) {
                $errors['DB_HOST / DATABASE_URL'] = 'A database host or local development socket is required.';
            }
            if ($production && in_array(strtolower($db['username'] ?? ''), ['root', 'forge', 'laravel', 'admin'], true)) {
                $errors['DB_USERNAME'] = 'Use a dedicated non-default application database account.';
            }
            if ($production && (! is_string($db['password'] ?? null) || $db['password'] === '' || in_array(strtolower($db['password']), ['password', 'changeme', 'secret', 'root', 'forge', 'laravel'], true))) {
                $errors['DB_PASSWORD'] = 'A non-default database password is required in production.';
            }
        }
        $mailer = $c['mail']['default'] ?? 'smtp';
        foreach (match ($mailer) {
            'mailgun' => ['MAILGUN_DOMAIN' => $c['services']['mailgun']['domain'] ?? null, 'MAILGUN_SECRET' => $c['services']['mailgun']['secret'] ?? null],
            'postmark' => ['POSTMARK_TOKEN' => $c['services']['postmark']['token'] ?? null],
            'ses' => ['AWS_ACCESS_KEY_ID' => $c['services']['ses']['key'] ?? null, 'AWS_SECRET_ACCESS_KEY' => $c['services']['ses']['secret'] ?? null],
            default => [],
        } as $name => $value) {
            if (! $value) {
                $errors[$name] = 'This credential is required by the selected mail provider.';
            }
        }
        if (($c['filesystems']['default'] ?? 'local') === 's3') {
            foreach (['key' => 'AWS_ACCESS_KEY_ID', 'secret' => 'AWS_SECRET_ACCESS_KEY', 'bucket' => 'AWS_BUCKET', 'region' => 'AWS_DEFAULT_REGION'] as $field => $name) {
                if (empty($c['filesystems']['disks']['s3'][$field])) {
                    $errors[$name] = 'This value is required by the selected S3 disk.';
                }
            }
        }
        $conditional = [];
        $broadcast = $c['broadcasting']['default'] ?? 'null';
        if ($broadcast === 'pusher') {
            foreach (['key' => 'PUSHER_APP_KEY', 'secret' => 'PUSHER_APP_SECRET', 'app_id' => 'PUSHER_APP_ID'] as $field => $name) {
                $conditional[$name] = $c['broadcasting']['connections']['pusher'][$field] ?? null;
            }
        } elseif ($broadcast === 'ably') {
            $conditional['ABLY_KEY'] = $c['broadcasting']['connections']['ably']['key'] ?? null;
        }
        foreach (['queue' => 'sqs', 'cache' => 'dynamodb'] as $section => $driver) {
            if (($c[$section]['default'] ?? '') === $driver) {
                $selected = $c[$section][$section === 'queue' ? 'connections' : 'stores'][$driver] ?? [];
                $conditional['AWS_ACCESS_KEY_ID'] = $selected['key'] ?? null;
                $conditional['AWS_SECRET_ACCESS_KEY'] = $selected['secret'] ?? null;
                if ($driver === 'sqs' && (empty($selected['prefix']) || str_contains($selected['prefix'], 'your-account-id'))) {
                    $errors['SQS_PREFIX'] = 'Configure the queue account URL before selecting SQS.';
                }
            }
        }
        foreach ($conditional as $name => $value) {
            if (! is_string($value) || trim($value) === '') {
                $errors[$name] = 'This credential is required by the selected service.';
            }
        }
        if ($production && in_array($c['cache']['default'] ?? 'file', ['array', 'null'], true)) {
            $errors['CACHE_DRIVER'] = 'Production authentication limits require a persistent cache, such as file or Redis.';
        }

        return $errors;
    }

    public function enforce(): void
    {
        // Metadata-only Composer/setup commands must work before runtime secrets are injected.
        if (app()->runningInConsole() && in_array($_SERVER['argv'][1] ?? '', ['package:discover', 'vendor:publish', 'key:generate', 'security:check', 'list', 'help', 'config:clear'], true)) {
            return;
        }
        if ($errors = $this->problems()) {
            throw new RuntimeConfigurationException(array_keys($errors));
        }
    }
}
