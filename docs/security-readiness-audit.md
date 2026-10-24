# Production security readiness — 24 October 2026

Scope: application source, configuration, routes, first-party browser scripts, response headers, isolated MariaDB tests, and public HTTPS deployment checks. Hosting control-plane settings and production MySQL transport cannot be inferred from the website's HTTPS certificate or phpMyAdmin's padlock.

## Checklist and changes

| Requested check | Finding before changes | Result |
|---|---|---|
| 1. Environment variables | Runtime secrets could be absent until a request failed; generic database account defaults remained. | **Fixed in application.** Startup checks encryption-key validity, production HTTPS URL, explicit DB identity/database/password, supported production DB driver, same-host assets, explicit CORS origins and selected integration credentials. Default production DB accounts/passwords are rejected. Metadata-only setup commands remain usable before secrets injection. |
| 2. Debug code | Dormant code/comments, a fixed dummy-password hash, fixed CI database credentials, and development-package debug routes needed attention. | **Fixed.** Production debug is forced off; debug/seed/backdoor routes are removed and denied independently of route caching. Dormant first-party code/handlers removed; dummy hashes and isolated CI credentials generated randomly. Current application code has no debugging console dumps or unfinished security TODO/FIXME markers. |
| 3. Error handling | Existing generic handling lacked a consistent request reference; some framework/DataTables errors could expose details. | **Fixed.** HTML/JSON errors use safe messages and a generated correlation UUID. Headers and server diagnostics share the UUID. Exception messages, SQL, bindings, argument values, absolute server paths and request data are excluded from logs and responses. Server logs retain exception class plus bounded relative source frames. Public validation field messages remain useful. |
| 4. Security headers | Script restrictions and frame/HSTS coverage needed strengthening. | **Fixed in application and web-server rules.** Every Laravel response, including redirects, errors and CORS preflights, gets nosniff, DENY, one-year HSTS and CSP. First-party inline scripts have a fresh request nonce; script event attributes use an allowlisted delegated dispatcher without eval. External scripts are limited to self. Static-response rules are in public/.htaccess. |
| 5. Authentication rate limits | Login IP cap was 20/minute; recovery IP cap was 5/minute. | **Fixed.** Login: 5/minute/IP plus 5/15 minutes/account-IP and 25/15 minutes/account. Recovery requests: 3/hour/IP and 3/hour/account. Reset-token submission: separate 3/hour/IP. Own-password changes: 5/minute. Limiter keys use HMACs. There is no public signup or OTP endpoint; administrator user creation requires authentication, permission and CSRF. Production null/array cache is rejected. |
| 6. CORS | Wildcard framework defaults could survive in cached host configuration. | **Fixed.** Explicit frontend origin https://sitetrack.pro, explicit methods/headers, no origin patterns. Boot also replaces legacy cached wildcard configuration. Untrusted-origin responses have no access-control grant headers. CORS is additional browser policy; server authentication, roles and CSRF remain required. |
| 7. Database TLS, credentials and network exposure | PDO had an optional CA setting, no mandatory TLS check; actual production encryption/account grants/firewall were unavailable. | **Partially fixed; production verification blocked.** Default credentials are rejected and certificate verification is explicit when using a CA. A read-only command checks the actual application DB session. Mandatory TLS rollout requires verified Hostinger TLS hostname/CA. The owner reports SSH works from their terminal, but this cloud environment still receives connection refusal on port 65002. Actual production TLS, server/account REQUIRE SSL and database firewall exposure are **unverified**, not passed. |

## Environment inventory and startup behavior

[Every referenced configuration variable](security/environment-variables.csv) is listed with source locations, exact fallback expression and required/conditional/optional behavior. The inventory contains code expressions only, never deployed values. There are 91 variable names, all referenced through configuration files; application business code does not read environment variables directly.

Required runtime fields are APP_KEY, database name/user, and a non-default production database password, supplied through DB_* or DATABASE_URL. APP_URL has a canonical HTTPS default. The key must match the configured Laravel cipher. Never regenerate APP_KEY on an existing site: it protects stored integration secrets, sessions and opaque references. `.env.example` contains blank credential placeholders and safe production defaults.

Mailgun/Postmark/SES, S3, Pusher/Ably and SQS/DynamoDB credentials are required only when those services are selected. SQS rejects its account-placeholder prefix. This app's normal configuration uses private local files, file cache/sessions, synchronous queues and no external broadcaster. SMTP may come from protected application Settings; missing mail configuration returns a generic recovery response rather than exposing provider details. Inactive framework adapter settings are documented with their defaults rather than invented credentials.

Missing/invalid critical runtime configuration aborts application boot. Web clients receive generic HTTP 503 with a correlation reference; server logs name missing fields without values. Composer package discovery, key generation, configuration clearing, help/list and the read-only security command can run before runtime secrets are supplied. This exception does not apply to HTTP requests, workers or ordinary commands.

## Error and script policy

The response policy applies before authentication/validation middleware and also through Laravel's exception handler for failures during boot. Client-supplied reference IDs are ignored. Generic JSON errors contain message and correlation_id, plus safe field-validation messages for 422. DataTables failures receive a safe message and reference. Application errors are not a source of raw exception messages or trace arguments.

Script policy is `script-src 'self' 'nonce-<request nonce>'` with `script-src-attr 'none'`; neither unsafe-eval nor unsafe-inline script permission is used. Existing inline style compatibility remains explicitly allowed. Handlers previously written as onclick/onchange were migrated to named, allowlisted callbacks without string evaluation. External script tags do not receive nonces. A nonce authorizes reviewed first-party inline scripts, not arbitrary user content.

HSTS is one year without includeSubDomains/preload, avoiding assumptions about other hosts. Apache/LiteSpeed responses use matching static header rules. Headers injected or removed by an upstream proxy are outside application middleware and must be checked live. Failures before PHP can start are controlled by Hostinger.

## Production database verification

The following command is read-only and prints statuses only, not passwords, connection URLs, account grants or personal records:

```sh
php artisan security:check --database
```

Run it in the deployed Laravel directory containing artisan. It checks runtime configuration, actual `Ssl_cipher` for the PDO session, explicit readable CA/certificate verification options, and whether the server or account requires TLS. The hosting account must also verify Remote MySQL allowlists, firewall/bind settings and authenticated-only exposure. An external port scan alone does not establish those properties.

Do not use the SSH IP as DB_HOST without Hostinger's database hostname and certificate guidance. SSH public keys and MySQL TLS certificates serve different purposes. A TLS-required deployment must use the provider's hostname, trusted CA and certificate verification, and must refuse an unencrypted fallback. If Hostinger offers only a local Unix socket, that is a different security model and does not satisfy the requested TLS check.

No production database configuration, firewall or account grants were changed during this audit. No SQL import is required for the application hardening. Existing application data and password hashes remain intact.

## Verification

- Regression suite: 47 tests / 1,849 assertions, including startup configuration, nonce/headers, error redaction/reference matching, rotated-identifier login/reset throttles, CORS and debug-route denial.
- Browser: 12 global/project screens; category and BOQ modal actions; reset fragment handling; zero script errors, failed responses or external request origins. Browser storage contained theme settings only.
- Asset build, JavaScript syntax, workflow actionlint, Blade compilation and patch whitespace checks are part of release verification.
- Isolated CI database uses freshly generated credentials and loopback-only published port. CI tests do not contact the production database.

Public deployment observations and GitHub CI results are reported with the release. Production database TLS/firewall cannot be marked passed until the hosting-side checks above succeed.

## Release evidence

Application release `ba1069c` is deployed on https://sitetrack.pro. [GitHub verification](https://github.com/Aashish2303/Site-Track/actions/runs/40101529644) passed. Public HTTPS checks confirmed a successful login page, required headers on HTML/error/static responses, fresh nonce-authorized inline scripts, a generic JSON 404 with a matching correlation header, debug-route 404, Secure/HttpOnly/SameSite=Lax cookies, allowed frontend preflight and no CORS grant for an untrusted origin. The hosting proxy also supplies an independent upgrade-insecure-requests policy; it does not conflict with the application nonce policy.

Mandatory database TLS implementation is prepared in [draft PR #1](https://github.com/Aashish2303/Site-Track/pull/1) with its rollout instructions. Its 49 tests / 1,861 assertions and GitHub checks passed. It is deliberately unmerged pending the real hosting configuration: absent/unreadable CA, disabled verification, Unix socket bypass or an empty session cipher will fail closed. This is a reviewable implementation, not a claim that the production database already uses TLS.
