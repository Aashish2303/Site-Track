# Account security

The privacy audit in [privacy-audit.md](privacy-audit.md) supersedes the earlier encrypted admin-password-display implementation.

SiteTrack uses Laravel's session-based `web` guard and Eloquent users, not Auth0. User login passwords have bcrypt hashes only. The legacy `user_vpassword` column remains empty for schema compatibility; no recoverable login-password copy or reveal method remains. The admin password field sets a replacement password and starts blank. Existing bcrypt passwords continue to work.

Login is limited by account/IP pair, IP and account. Limiter identities use keyed HMACs. Password changes, resets and deletion require server validation; reset tokens are single-use, hashed in the database, and expire after 60 minutes. New reset links carry the token in a browser fragment, not the URL path/query, and contain no email address. The browser removes the fragment immediately after filling the hidden form input.

Production limits and the environment/header/database checklist are recorded in [security-readiness-audit.md](security-readiness-audit.md). Login permits at most 5 attempts/minute/IP; recovery requests and reset submissions each permit 3/hour/IP using separate buckets. JSON/HTML errors carry generic messages and a correlation reference. Existing inline script handlers use an allowlisted dispatcher under a nonce-based Content Security Policy.

Authenticated requests use the shared fail-closed role policy. Unknown/missing/unassigned/inactive permissions are denied; AJAX, save, status and export routes have explicit mappings. User-directory read-only access returns the current account only; managing other accounts requires account-management permission. Roles apply across projects; selecting a site is a context filter, not a user-to-project membership grant.

The session cookie is HttpOnly, SameSite=Lax and Secure in production/HTTPS. Sessions are encrypted in private server-side file storage; no PII is stored in a session cookie. The JavaScript-readable XSRF cookie is disabled; forms use CSRF hidden inputs/meta values. All private responses use no-store. DataTable responses have explicit field allowlists, and browser record references are opaque. Existing numeric inbound links remain compatible; authorisation and site checks protect all references.

Initial privacy migration runs before the first web request is served. It erases old recoverable copies without changing login hashes, removes legacy application logs, sessions/reset/API tokens and queue payloads, and moves personal uploads into private storage. It is idempotent. To repeat it after restoring an old backup:

```sh
php artisan privacy:upgrade --force
```

Daily retention cleanup is defined in the Laravel scheduler:

```sh
php artisan privacy:prune
```

Hostinger must actually invoke Laravel's scheduler (`php artisan schedule:run` every minute) for daily pruning to run. This repository cannot verify that hosting-side cron is configured. The audit report lists retention and external-system limits.

Account deletion is available on My profile. It requires the current password and typing DELETE. Profile/contact credentials, profile and uploaded progress photos, reset/API tokens and sessions are removed. Linked construction records use a common disabled anonymous account and known identity text is redacted within linked records. The last active primary administrator must create another administrator first.

SMTP transport secrets are not user login passwords. They must be usable by the SMTP transport, so they are encrypted in a private integration-secret file rather than hashed or exposed in HTML/JSON. Database copies are cleared. Protect APP_KEY separately from database/file backups. Old repository history, hosting/provider logs, recipient mailboxes and downloaded exports need separate retention/erasure management.
