#!/bin/sh
set -e

php artisan key:generate --force
php artisan config:clear
php artisan migrate --force
php artisan db:seed --force
php -S 0.0.0.0:8000 -t public