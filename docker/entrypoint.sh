#!/bin/sh

set -e

CURRENT_UID="$(id -u)"
CURRENT_GID="$(id -g)"

echo "🚀 Starting Laravel container..."

# ----------------------------
# Install Composer dependencies (only if missing)
# ----------------------------
if [ ! -f "vendor/autoload.php" ]; then
    echo "📦 vendor/ not found, running composer install..."
    composer install --no-interaction --prefer-dist
else
    echo "✅ Composer dependencies already installed"
fi

# ----------------------------
# Ensure Laravel directories
# ----------------------------
ensure_storage_dirs() {
    mkdir -p bootstrap/cache
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/framework/cache/data
    mkdir -p storage/logs
}

ensure_storage_dirs

chown -R "${CURRENT_UID}:${CURRENT_GID}" bootstrap/cache storage 2>/dev/null || true

# ----------------------------
# Ensure tmp directories
# ----------------------------
ensure_tmp_dirs() {
    for dir in \
        "${SESSION_FILES_PATH:-/tmp/laravel-sessions}" \
        "${VIEW_COMPILED_PATH:-/tmp/laravel-views}" \
        "${CACHE_FILE_PATH:-/tmp/laravel-cache}"
    do
        mkdir -p "$dir"
        chown "${CURRENT_UID}:${CURRENT_GID}" "$dir" 2>/dev/null || true
    done
}

ensure_tmp_dirs

# ----------------------------
# Start main process
# ----------------------------
exec "$@"
