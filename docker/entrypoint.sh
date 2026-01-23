#!/bin/sh

set -e

CURRENT_UID="$(id -u)"
CURRENT_GID="$(id -g)"

ensure_storage_dirs() {
    mkdir -p bootstrap/cache
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/framework/cache/data
    mkdir -p storage/logs
}

ensure_storage_dirs

chown -R "${CURRENT_UID}:${CURRENT_GID}" bootstrap/cache storage 2>/dev/null || true

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

exec "$@"
