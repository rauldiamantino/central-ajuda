FROM php:8.3-apache

RUN apt-get update && \
    apt-get install -y libssl-dev zlib1g-dev libmemcached-dev libicu-dev && \
    docker-php-ext-install pdo pdo_mysql intl && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    a2enmod rewrite

COPY ./public /var/www/html
COPY ./vendor /var/www/vendor
COPY ./app /var/www/app
COPY apache.conf /etc/apache2/sites-available/000-default.conf
