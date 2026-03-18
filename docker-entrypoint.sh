#!/bin/sh

composer install --no-interaction --no-progress --no-scripts
php artisan key:generate --no-interaction --force
php artisan migrate --force
php artisan queue:restart