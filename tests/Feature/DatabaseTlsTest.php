<?php

namespace Tests\Feature;

use App\Security\RuntimeConfigurationException;
use App\Security\TlsMySqlConnector;
use PDO;
use PDOStatement;
use Tests\TestCase;

class DatabaseTlsTest extends TestCase
{
    private function sslOptions(): array
    {
        return [PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt', PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true];
    }

    public function test_tls_rejects_missing_ca_disabled_verification_and_socket_before_connecting(): void
    {
        foreach ([['', []], ['', [PDO::MYSQL_ATTR_SSL_CA => '/missing/ca.pem']], ['/tmp/mysql.sock', $this->sslOptions()], ['', array_replace($this->sslOptions(), [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false])]] as [$socket, $options]) {
            $connector = new TlsMySqlConnector;
            try {
                $connector->createConnection('mysql:host=unreachable.invalid', ['tls_required' => true, 'unix_socket' => $socket], $options);
                $this->fail('Unsafe configuration must fail before any network connection.');
            } catch (RuntimeConfigurationException $e) {
                $this->assertNotEmpty($e->fields);
            }
        }
    }

    public function test_tls_checks_the_actual_session_and_rejects_a_cleartext_fallback(): void
    {
        foreach (['', 'TLS_AES_256_GCM_SHA384'] as $cipher) {
            $statement = $this->getMockBuilder(PDOStatement::class)->disableOriginalConstructor()->onlyMethods(['fetch'])->getMock();
            $statement->expects($this->once())->method('fetch')->willReturn(['Variable_name' => 'Ssl_cipher', 'Value' => $cipher]);
            $pdo = $this->getMockBuilder(PDO::class)->disableOriginalConstructor()->onlyMethods(['query'])->getMock();
            $pdo->expects($this->once())->method('query')->with("SHOW SESSION STATUS LIKE 'Ssl_cipher'")->willReturn($statement);
            $connector = new class($pdo) extends TlsMySqlConnector
            {
                public function __construct(private PDO $pdo) {}

                protected function createPdoConnection($dsn, $username, $password, $options)
                {
                    return $this->pdo;
                }
            };
            try {
                $result = $connector->createConnection('mysql:host=db.example.invalid', ['tls_required' => true], $this->sslOptions());
                $this->assertNotSame('', $cipher);
                $this->assertSame($pdo, $result);
            } catch (RuntimeConfigurationException $e) {
                $this->assertSame('', $cipher);
                $this->assertSame(['DB_TLS'], $e->fields);
            }
        }
    }
}
