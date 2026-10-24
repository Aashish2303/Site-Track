# Mandatory MySQL TLS rollout

This change is prepared on a separate branch and must remain unmerged until the actual Hostinger database hostname and trusted CA are verified. The main application security release deliberately does not claim production database TLS has passed.

The connector requires a readable CA, server-certificate verification and TCP in every environment other than local/testing. It checks the actual PDO session cipher immediately after connection, before Laravel's database selection/encoding/application queries. It never retries with verification disabled or permits a cleartext fallback. Runtime configuration validation refuses startup when mandatory certificate settings are missing. TLS rejection is a generic 503 to the browser with a correlation reference.

1. Obtain the PHP/PDO MySQL TLS hostname, port and CA certificate from Hostinger. Confirm the database account supports TLS; do not assume the SSH IP or phpMyAdmin session is the application TLS endpoint.
2. Install the trusted CA outside public_html. Retain the existing DB name/user/password and APP_KEY privately. Set DB_HOST to the certificate-matching hostname, DB_PORT to its TLS-capable MySQL port, DB_SOCKET empty, MYSQL_ATTR_SSL_CA to the readable CA file. Clear an old DATABASE_URL if it overrides the intended fields. Never disable certificate verification.
3. On the current release, clear/rebuild cached configuration using the site's deployment process and run `php artisan security:check --database`. Both connection_tls and certificate_verification must pass. Ask Hostinger to enforce account REQUIRE SSL or server require_secure_transport and restrict Remote MySQL/firewall access to necessary hosts. The read-only command also reports server/account enforcement where grants are visible.
4. Only after those checks pass, merge this branch and deploy. Run the same command again, check normal authenticated workflows and confirm that failed/absent TLS settings fail closed.

If the host supports only local unencrypted MySQL/socket access, this deployment would intentionally refuse it. Resolve that provider constraint before merging; silently accepting the socket would not meet the requested TLS requirement. Do not change production settings to speculative hostnames or trust an arbitrary uploaded CA.

Tests cover rejecting missing/unreadable CA files, disabled verification, socket bypass and an actual PDO session reporting no cipher. Local/testing connections remain available for the isolated test suite; production cannot turn off TLS with a URL or environment flag.
