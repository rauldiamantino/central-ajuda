FROM php:8.3-apache

# Instala pacotes essenciais e dependências
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        dnsutils \
        curl \
        wget \
        zlib1g-dev \
        libmemcached-dev \
        libssl-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        gnupg2 \
        lsb-release && \
    # Limpa a cache do apt
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Instala as extensões PHP necessárias
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-install intl

# Instala a extensão PDO para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

# Instala o memcached
RUN pecl install memcached && \
    docker-php-ext-enable memcached

RUN apt-get update && apt-get install -y telnet

# Ativa o módulo de reescrita do Apache
RUN a2enmod rewrite

# Copia os arquivos do projeto
COPY ./public /var/www/html
COPY ./vendor /var/www/vendor
COPY ./app /var/www/app
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./config/php.ini /usr/local/etc/php/

# Comando padrão
CMD ["apache2-foreground"]
