#!/bin/sh
set -e

CURRENT_UID="$(id -u)"
CURRENT_GID="$(id -g)"

echo "🚀 Starting Laravel container..."

# ----------------------------
# Fix Git dubious ownership
# ----------------------------
git config --global --add safe.directory /var/www

# ----------------------------
# Run Composer always with error handling
# ----------------------------
if [ ! -f "vendor/autoload.php" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader || {
        echo "❌ Composer install failed"
        exit 1
    }
else
    echo "✅ Composer dependencies already installed"
fi

# ----------------------------
# Ensure Laravel directories
# ----------------------------
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache/data} storage/logs
chown -R "${CURRENT_UID}:${CURRENT_GID}" bootstrap/cache storage 2>/dev/null || true

# ----------------------------
# Ensure tmp directories
# ----------------------------
for dir in "${SESSION_FILES_PATH:-/tmp/laravel-sessions}" \
           "${VIEW_COMPILED_PATH:-/tmp/laravel-views}" \
           "${CACHE_FILE_PATH:-/tmp/laravel-cache}"
do
    mkdir -p "$dir"
    chown "${CURRENT_UID}:${CURRENT_GID}" "$dir" 2>/dev/null || true
done

# ----------------------------
# Start main process
# ----------------------------
exec "$@"
