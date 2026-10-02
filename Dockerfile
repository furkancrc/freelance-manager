FROM serversideup/php:8.4-fpm-apache

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock* ./

RUN if [ -f "composer.json" ]; then composer install --no-interaction; fi

COPY --chown=www-data:www-data . /var/www/html
