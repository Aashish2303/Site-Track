# SiteTrack UI and Hostinger deployment

The UI follows the charcoal, amber, typography and component spacing of the Core design reference. The existing Laravel controllers, permissions, routes, validation and database schema still own application behavior. Shared presentation lives in `public/assets/admin/css/sitetrack-core.css`, with navigation search in `public/assets/admin/js/sitetrack-core.js`.

The app uses Laravel's `mysql` database driver. The supplied `database.sql` was exported from **MariaDB 11.8.8** and has 39 tables. This identifies the supplied database; the current live Hostinger engine, database assignment, PHP version and file paths still require an authenticated hosting inspection. Production database credentials are not needed for UI deployment and must not be committed.

## Current deployment state

The GitHub workflow checks the application and prepares UI-only deployment. **Live deployment has a literal `false` gate until the existing Hostinger account and paths have been verified and deployment is confirmed.** After that review, remove the literal `false &&` from the deployment job and set `SITETRACK_DEPLOY_ENABLED` to `true` as a repository variable. The workflow updates the application already serving `https://sitetrack.pro`.

Hostinger connector servers were registered during this task. Restart the Codex host once so those tools load, and finish the Hostinger consent screen if prompted. Then inspect `sitetrack.pro` under its actual account before enabling deployment. The current connection has not yet exposed the Hostinger hosting tools, so live hosting settings have not been verified or changed.

## Settings to configure after inspection

Use repository variables for `SITETRACK_DEPLOY_ENABLED`, `HOSTINGER_SSH_HOST`, `HOSTINGER_SSH_PORT`, `HOSTINGER_SSH_USER`, `SITETRACK_APP_ROOT`, `SITETRACK_PUBLIC_ROOT`, `SITETRACK_BACKUP_ROOT` and `SITETRACK_PHP_BINARY`. The backup root must be private and outside the public document root. No server paths or SSH port have been guessed in the workflow.

Store `HOSTINGER_DEPLOY_KEY` and `HOSTINGER_KNOWN_HOSTS` as GitHub **production environment secrets**. Use an SSH key authorized for the hosting account and host keys verified against Hostinger's authenticated SSH information. Do not obtain trust by disabling host-key checks. Restrict the production environment's deployment branch to `main`.

The runtime deployment switch is a repository variable so it can be evaluated before GitHub enters the production environment. The initial literal `false` gate prevents any first push from deploying, even if an old variable happens to exist. Account-specific variables can be production environment variables. SSH, `rsync`, an executable PHP CLI 8.2–8.4 and the existing Laravel `vendor` directory are prerequisites; verify these on the actual plan before enabling the job. The ephemeral CI database password in the workflow is only for a disposable GitHub runner service, never a production credential.

## What a push does

UI commits to `main` run Composer's locked installation, the existing Laravel tests, the Vite build, JavaScript/shell checks and deployment-package validation. The CI database is an isolated MariaDB service populated from the repository dump. The GitHub workflow itself still needs its first hosted run; local build and tests do not establish that hosted CI or production deployment succeeded.

Once deployment is explicitly enabled, the job uploads only Blade templates and allowed CSS, JavaScript and font assets. The base file list is in `deploy/ui-files.txt`; cumulative UI changes since `deploy/ui-baseline` are included automatically, so later edits to other existing UI pages are included as well. Source paths are validated and package files receive SHA-256 checksums.

Replaced files are backed up outside the document root. Assets upload before views; Laravel's compiled view cache is then cleared, and the public login page must contain the new UI's real text and stylesheet. There is no delete sync, database import, migration, dependency replacement, environment-file replacement, upload-directory sync, or application-key regeneration on the live site. Deletions and backend releases need a separate deployment change. If a transfer fails, use the private release backup to restore the affected UI files and clear the view cache; backups are retained for review.

## Validation and a separate security issue

Local validation covers authenticated UI pages, login and form validation, server-side table filtering, keyboard navigation search, responsive navigation and theme switching, as well as the existing PHP suite and asset build. This is not a security audit.

The existing password-recovery controller decodes a stored password and emails it. That behavior was retained for this UI task; replacing it with expiring, single-use password-reset tokens should be a separate security fix. Do not describe this UI release as resolving that issue.

Inter is self-hosted under `public/assets/admin/fonts/inter`; its SIL Open Font License is included there.
