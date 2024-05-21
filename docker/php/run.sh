#!/bin/sh

cd /var/www/MLCity_backend

php artisan migrate
php artisan cache:clear
php artisan route:cache
