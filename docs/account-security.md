# Account security and role permissions

SiteTrack uses Laravel 10's `web` session guard and Eloquent user provider, not Auth0. Login verifies the bcrypt hash in `users.user_password`. Session IDs are regenerated after login and password changes. Authenticated sessions check the current password hash on each request, so a password change or reset invalidates other established sessions. Disabled accounts and disabled roles cannot continue using existing sessions. Sessions created before this update must sign in again once, because they do not have the password-hash stamp needed for later revocation.

## Admin password display and encryption

The admin Edit User eye button is intentionally retained. Viewing the page requires `user.edit_user`, including for delegated administrators. It is masked initially and the page is served with `Cache-Control: no-store, private`. Neither password field is included in Eloquent JSON or DataTables user-list responses. Validation never flashes submitted passwords or reset tokens into old form input.

The display copy in `user_vpassword` now uses authenticated AES-256-GCM encryption through Laravel's Encrypter, with a random nonce and a purpose-derived key based on the existing `APP_KEY`. Login continues to use bcrypt. Encryption protects a database-only leak; access to the app's encryption key or an authorised Edit User session can reveal the display copies. Do not rotate or regenerate `APP_KEY` casually: existing encrypted data and sessions depend on it. Keep the key out of database backups, repositories, screenshots and chat.

Existing Base64 copies are upgraded automatically in batches of 200 on requests from the active primary administrator. For a larger installation, or to complete and verify the upgrade immediately, run on the deployed host:

```sh
php artisan security:encrypt-passwords
```

Sign in again as the primary administrator after deployment and open Users to trigger the automatic upgrade. The command is idempotent and prints counts only. It compares the legacy value before updating, preserving concurrent password changes. Login hashes remain unchanged. Invalid copies, copies that do not match the login hash, and legacy copies longer than 72 bytes are cleared instead of being displayed incorrectly; those users can still sign in with their existing hash, and an administrator can set a new password. No SQL import or schema change is required.

The checked-in seed dump has had its recoverable password copies removed. Previously exposed copies can remain in Git history or old backups; changing existing passwords is necessary to invalidate those earlier copies. This change does not rewrite repository history or delete backups.

## Login and recovery limits

POST login attempts are limited to 5 per account/IP pair per 15 minutes, 20 per IP per minute, and 25 per account per 15 minutes. Successful and unsuccessful submissions count. Username keys are normalised and hashed. The IP limit covers attempts that rotate usernames; the account limit covers attempts that rotate IPs. The application uses the connection IP and does not trust arbitrary forwarded-IP headers. Exceeding a limit returns HTTP 429 and `Retry-After` with the same generic message.

Password recovery has limits of 5 requests per IP per minute and 5 per email per hour, plus Laravel's reset-token creation throttle. Existing and unknown addresses receive the same response. Passwords are never emailed: recovery sends a single-use, 60-minute link using Laravel's password broker. Tokens are hashed in `password_reset_tokens`. Set the deployed `APP_URL` to `https://sitetrack.pro` and keep SMTP configured in Settings or environment configuration. The reset page uses a no-referrer policy.

New or changed passwords need at least 12 characters, including a letter and a number, and at most 72 bytes to avoid bcrypt truncation. Existing passwords continue to work. Clearing the password field while editing a user preserves their password. Existing unchanged passwords can be retained while editing other user details.

## Permissions and server validation

`RoleAccess` provides the common policy for route middleware and navigation. Missing permission entries deny access. Read-only AJAX endpoints map to the list permission; save/status endpoints map to their write permission; exports retain separate download permissions. Shared dropdown lookups accept the appropriate form permissions. The legacy role-creation page uses the existing Edit Role capability. Authenticated users may access their own profile, password change, logout, and the shared dashboard without module-management permission.

Roles control modules and actions across projects. Selecting a site scopes operational records; it does not constitute user-to-project membership assignment.

Configure access under Administration → Roles & Permissions, and assign each account's role under Users. Grant Edit User only to people allowed to reveal and replace account passwords. New workflow permissions remain explicitly assigned rather than automatically granted to staff.

`ValidateUserInput` applies the schemas in `config/input-validation.php` before legacy controllers execute. It validates types, lengths, dates, referenced IDs, quantities/rates, status values, nested material rows, role permission IDs, and allowed uploads independently of JavaScript or submit-button presence. Existing workflow services retain their deeper project, revision, quantity and payment validations. BOQ spreadsheet rows are validated before import, and project creation plus import is transactional. Status mutations now require POST and Laravel's CSRF token; GET cannot change an account or approval status. Production debug output is disabled and server errors use generic pages/responses.

## Verification

Run the tests only against the isolated imported test database:

```sh
DB_DATABASE=sitetrack_test php artisan test
```

The security feature suite covers account/IP limits, malformed inputs, missing/unassigned/disabled permissions, all protected nonpersonal routes for an unprivileged role, read-only user-list secrecy, admin display, encryption randomisation/tampering/wrong keys, legacy conversion, reset-link expiry and reuse, and session invalidation. Existing workflow tests cover cross-project writes, independent approvals, inventory, wages, and Excel/PDF exports.
