<?php

namespace App\Security;

use Illuminate\Support\Facades\DB;

/** Operator-only, read-only inspection; never returns a DSN, password, grant string or username. */
class DatabaseReadiness
{
    public function inspect(): array
    {
        $connection = DB::connection();
        if ($connection->getDriverName() !== 'mysql') {
            return ['connection_tls' => false, 'certificate_verification' => false];
        }
        $result = $connection->select("SHOW SESSION STATUS LIKE 'Ssl_cipher'");
        $cipher = $result ? (array) $result[0] : [];
        $encrypted = ! empty($cipher['Value'] ?? $cipher['value'] ?? null);
        $options = $connection->getConfig('options') ?? [];
        $ca = $options[\PDO::MYSQL_ATTR_SSL_CA] ?? null;
        $verification = ($options[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] ?? false) === true && is_string($ca) && is_readable($ca);
        $server = $connection->select("SHOW VARIABLES LIKE 'require_secure_transport'");
        $row = $server ? (array) $server[0] : [];
        $serverEnforces = in_array(strtoupper((string) ($row['Value'] ?? $row['value'] ?? '')), ['ON', '1'], true);
        $accountEnforces = false;
        foreach ($connection->select('SHOW GRANTS FOR CURRENT_USER') as $grant) {
            foreach ((array) $grant as $text) {
                if (preg_match('/REQUIRE (SSL|X509)/i', $text)) {
                    $accountEnforces = true;
                }
            }
        }

        return ['connection_tls' => $encrypted, 'certificate_verification' => $verification,
            'server_or_account_requires_tls' => $serverEnforces || $accountEnforces];
    }
}
