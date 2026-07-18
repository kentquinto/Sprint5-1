#!/bin/sh
set -e

# Fresh demo database on every boot: migrate from zero and seed
# 13 games + 10 test users + sample events (see README seed credentials).
php artisan migrate:fresh --force --seed

# Passport needs encryption keys and a personal access client to issue tokens.
# The database is fresh each boot, so the client is always created exactly once.
php artisan passport:keys --force
php artisan passport:client --personal --name="TCGManager" --no-interaction

# Scribe bakes config('app.url') into the static docs HTML/JSON at generation
# time ("Try It Out" base URL, curl examples). Regenerating here — instead of
# relying on the committed .scribe/public/docs, which reflect the local
# APP_URL — guarantees the live docs point at whatever host is actually
# serving them.
php artisan scribe:generate

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
