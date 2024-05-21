FROM php:8.2-fpm

WORKDIR /var/www/MLCity_backend
CMD composer install
