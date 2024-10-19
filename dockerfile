FROM php:8.3-apache

# Instala dependências
RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev libmemcached-dev libicu-dev && \
    docker-php-ext-install pdo pdo_mysql intl && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    a2enmod rewrite && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copia arquivos
COPY ./public /var/www/html
COPY ./vendor /var/www/vendor
COPY ./app /var/www/app
COPY ./certificados-dev /var/lib/https-portal
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
