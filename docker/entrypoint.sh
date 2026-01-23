#!/bin/sh

set -e

TARGET_USER="${UID:-1000}"
TARGET_GROUP="${GID:-1000}"

ensure_dirs() {
    mkdir -p storage
    mkdir -p bootstrap/cache
}

ensure_dirs

chown -R "${TARGET_USER}:${TARGET_GROUP}" storage bootstrap/cache 2>/dev/null || true

is_true() {
    case "${1:-}" in
        true|TRUE|True|t|T|1) return 0 ;;
        *) return 1 ;;
    esac
}

can_run_artisan() {
    [ -f artisan ] && [ -f vendor/autoload.php ]
}

if is_true "${LARAVEL_CACHE_CLEAR:-false}" && can_run_artisan; then
    php artisan optimize:clear
fi

if is_true "${LARAVEL_CACHE_WARMUP:-false}" && can_run_artisan; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
