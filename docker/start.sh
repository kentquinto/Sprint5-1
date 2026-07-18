#!/bin/sh
set -e

# Fresh demo database on every boot: migrate from zero and seed
# 13 games + 10 test users + sample events (see README seed credentials).
php artisan migrate:fresh --force --seed

# Passport needs encryption keys and a personal access client to issue tokens.
# The database is fresh each boot, so the client is always created exactly once.
php artisan passport:keys --force
php artisan passport:client --personal --name="TCGManager" --no-interaction

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
