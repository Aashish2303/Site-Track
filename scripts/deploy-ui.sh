#!/usr/bin/env bash
set -euo pipefail

# Called by the explicitly enabled GitHub production deployment job. Hostinger
# account paths and host keys must be verified before enabling that job.
required_names=(HOSTINGER_SSH_HOST HOSTINGER_SSH_PORT HOSTINGER_SSH_USER SSH_KEY_FILE KNOWN_HOSTS_FILE SITETRACK_APP_ROOT SITETRACK_PUBLIC_ROOT SITETRACK_BACKUP_ROOT SITETRACK_PHP_BINARY)
for variable_name in "${required_names[@]}"; do
    [[ -n "${!variable_name:-}" ]] || { echo "Missing deployment setting: $variable_name" >&2; exit 1; }
done
[[ "$HOSTINGER_SSH_HOST" =~ ^[A-Za-z0-9][A-Za-z0-9.-]*$ && "$HOSTINGER_SSH_USER" =~ ^[A-Za-z0-9_][A-Za-z0-9_-]*$ && "$HOSTINGER_SSH_PORT" =~ ^[0-9]{1,5}$ ]] || { echo 'Invalid SSH destination.' >&2; exit 1; }
((10#$HOSTINGER_SSH_PORT > 0 && 10#$HOSTINGER_SSH_PORT < 65536)) || { echo 'Invalid SSH port.' >&2; exit 1; }
for directory_name in SITETRACK_APP_ROOT SITETRACK_PUBLIC_ROOT SITETRACK_BACKUP_ROOT SITETRACK_PHP_BINARY; do
    directory_value=${!directory_name}
    [[ "$directory_value" =~ ^/[A-Za-z0-9_./-]+$ && "$directory_value" != / && "$directory_value" != *..* ]] || { echo "Invalid absolute path: $directory_name" >&2; exit 1; }
done
[[ -f "$SSH_KEY_FILE" && -s "$KNOWN_HOSTS_FILE" ]] || { echo 'SSH key or verified host keys are missing.' >&2; exit 1; }
temporary_dir=$(mktemp -d)
trap 'rm -rf "$temporary_dir"' EXIT
bash scripts/package-ui.sh "$temporary_dir/package"
(cd "$temporary_dir/package" && sha256sum --check SHA256SUMS >/dev/null)
ssh_options=(-i "$SSH_KEY_FILE" -p "$HOSTINGER_SSH_PORT" -o BatchMode=yes -o IdentitiesOnly=yes -o StrictHostKeyChecking=yes -o "UserKnownHostsFile=$KNOWN_HOSTS_FILE")
destination="$HOSTINGER_SSH_USER@$HOSTINGER_SSH_HOST"
release_id="${GITHUB_SHA:-$(git rev-parse HEAD)}-${GITHUB_RUN_ID:-$(date -u +%Y%m%d%H%M%S)}-${GITHUB_RUN_ATTEMPT:-1}"
[[ "$release_id" =~ ^[a-f0-9]+-[a-zA-Z0-9]+-[0-9]+$ ]] || { echo 'Invalid release identifier.' >&2; exit 1; }

# Check the existing Laravel installation and private backup location before any
# application files are uploaded. No migration or database command runs here.
ssh "${ssh_options[@]}" "$destination" bash -s -- "$SITETRACK_APP_ROOT" "$SITETRACK_PUBLIC_ROOT" "$SITETRACK_BACKUP_ROOT" "$SITETRACK_PHP_BINARY" <<'REMOTE'
set -euo pipefail
app_root=$1; public_root=$2; backup_root=$3; php_binary=$4
test -f "$app_root/artisan"
test -f "$app_root/vendor/autoload.php"
test -d "$app_root/resources/views/user"
test -f "$public_root/index.php"
test -d "$public_root/assets/admin"
test -w "$app_root/resources/views/user"
test -w "$public_root/assets/admin"
public_real=$(realpath "$public_root")
backup_real=$(realpath -m "$backup_root")
case "$backup_real/" in "$public_real/"*) echo 'Backups must be outside the public document root.' >&2; exit 1;; esac
"$php_binary" -r 'exit(PHP_VERSION_ID >= 80200 && PHP_VERSION_ID < 80500 ? 0 : 1);'
cd "$app_root"
"$php_binary" artisan --version
"$php_binary" -r 'require "vendor/autoload.php"; $app=require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (realpath($app->publicPath()) !== realpath($argv[1])) { fwrite(STDERR, "Laravel public_path does not match the deployment document root.\n"); exit(1); }' "$public_root"
command -v rsync >/dev/null
REMOTE

printf -v rsync_shell '%q ' ssh "${ssh_options[@]}"
transfer_options=(--recursive --times --compress --perms --chmod=D755,F644 --backup --rsh="$rsync_shell")
# Assets arrive before templates that reference them. Each replacement is backed
# up; rsync intentionally never deletes files from the running application.
rsync "${transfer_options[@]}" --backup-dir="$SITETRACK_BACKUP_ROOT/$release_id/public" \
    "$temporary_dir/package/public/" "$destination:$SITETRACK_PUBLIC_ROOT/"
rsync "${transfer_options[@]}" --backup-dir="$SITETRACK_BACKUP_ROOT/$release_id/views" \
    "$temporary_dir/package/views/" "$destination:$SITETRACK_APP_ROOT/"
ssh "${ssh_options[@]}" "$destination" bash -s -- "$SITETRACK_APP_ROOT" "$SITETRACK_PHP_BINARY" <<'REMOTE'
set -euo pipefail
cd "$1"
"$2" artisan view:clear --no-interaction
REMOTE
curl --fail --silent --show-error --max-time 30 https://sitetrack.pro/ > "$temporary_dir/live.html"
rg -q 'Your workspace awaits' "$temporary_dir/live.html"
rg -q 'sitetrack-core.css' "$temporary_dir/live.html"
echo "Verified the new sign-in page on https://sitetrack.pro; UI backups: $SITETRACK_BACKUP_ROOT/$release_id"
