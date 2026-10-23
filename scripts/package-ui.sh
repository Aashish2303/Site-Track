#!/usr/bin/env bash
set -euo pipefail

project_root=$(git rev-parse --show-toplevel)
cd "$project_root"
output_dir=${1:?Usage: scripts/package-ui.sh /absolute/output/directory}
[[ "$output_dir" == /* && ! -e "$output_dir" ]] || { echo 'Output must be a new absolute directory.' >&2; exit 1; }
case "$output_dir/" in "$project_root/"*) echo 'Keep deployment packages outside the checkout.' >&2; exit 1;; esac
baseline=$(tr -d '\r\n' < deploy/ui-baseline)
git rev-parse --verify "$baseline^{commit}" >/dev/null
file_list=$(mktemp)
trap 'rm -f "$file_list"' EXIT
{
    cat deploy/ui-files.txt
    git -c core.quotepath=false diff --name-only --diff-filter=ACMR "$baseline" -- \
        resources/views/user public/assets/admin/css public/assets/admin/js public/assets/admin/fonts
} | sed '/^[[:space:]]*$/d' | sort -u > "$file_list"

# Validate the complete list before creating any output. Database files, uploads,
# environment files, PHP controllers and dependency directories cannot enter it.
while IFS= read -r source_file; do
    [[ "$source_file" =~ ^[A-Za-z0-9_./-]+$ && "$source_file" != *..* ]] || { echo "Invalid UI path: $source_file" >&2; exit 1; }
    case "$source_file" in
        resources/views/user/*.blade.php|public/assets/admin/css/*.css|public/assets/admin/js/*.js|public/assets/admin/fonts/*.woff|public/assets/admin/fonts/*.woff2|public/assets/admin/fonts/*.ttf|public/assets/admin/fonts/*.otf|public/assets/admin/fonts/*/OFL.txt) ;;
        *) echo "Outside the UI deployment scope: $source_file" >&2; exit 1;;
    esac
    git ls-files --error-unmatch -- "$source_file" >/dev/null
    [[ -f "$source_file" && ! -L "$source_file" ]] || { echo "UI file is missing or is a symlink: $source_file" >&2; exit 1; }
    case "$(realpath "$source_file")" in "$project_root/"*) ;; *) echo 'UI path escapes the checkout.' >&2; exit 1;; esac
done < "$file_list"

mkdir -p "$output_dir/views" "$output_dir/public"
while IFS= read -r source_file; do
    case "$source_file" in
        public/*) destination="$output_dir/public/${source_file#public/}" ;;
        *) destination="$output_dir/views/$source_file" ;;
    esac
    mkdir -p "$(dirname "$destination")"
    cp "$source_file" "$destination"
done < "$file_list"
(
    cd "$output_dir"
    find views public -type f -print0 | sort -z | xargs -0 sha256sum > SHA256SUMS
)
printf 'Prepared %s UI files with SHA-256 checksums.\n' "$(wc -l < "$file_list")"
