<?php

namespace App\Security;

use Illuminate\Database\Connectors\MySqlConnector;
use PDO;

/** Verify transport before Laravel can issue any application SQL. */
class TlsMySqlConnector extends MySqlConnector
{
    public static function configurationProblems(array $config, array $options): array
    {
        $errors = [];
        $ca = $options[PDO::MYSQL_ATTR_SSL_CA] ?? null;
        if (! is_string($ca) || ! is_file($ca) || ! is_readable($ca)) {
            $errors['MYSQL_ATTR_SSL_CA'] = 'Production MySQL requires a readable trusted CA certificate file.';
        }
        if (($options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] ?? false) !== true) {
            $errors['MYSQL_ATTR_SSL_VERIFY_SERVER_CERT'] = 'Server certificate verification must be enabled.';
        }
        if (! empty($config['unix_socket'])) {
            $errors['DB_SOCKET'] = 'A local socket cannot satisfy mandatory production database TLS. Use the verified TLS hostname.';
        }

        return $errors;
    }

    public function createConnection($dsn, array $config, array $options)
    {
        $required = ! app()->environment(['local', 'testing']) || ($config['tls_required'] ?? false);
        if ($required && ($errors = self::configurationProblems($config, $options))) {
            throw new RuntimeConfigurationException(array_keys($errors));
        }
        $connection = parent::createConnection($dsn, $config, $options);
        if ($required) {
            $status = $connection->query("SHOW SESSION STATUS LIKE 'Ssl_cipher'")->fetch(PDO::FETCH_ASSOC);
            if (empty($status['Value'] ?? $status['value'] ?? null)) {
                // Never retry with disabled verification or an unencrypted connection.
                throw new RuntimeConfigurationException(['DB_TLS']);
            }
        }

        return $connection;
    }
}
