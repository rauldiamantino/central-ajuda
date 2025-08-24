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
        lsb-release \
        telnet && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Extensões PHP
RUN docker-php-ext-configure intl && \
    docker-php-ext-install pdo pdo_mysql intl

# Memcached
RUN pecl install memcached && docker-php-ext-enable memcached

# Habilita módulos do Apache
RUN a2enmod rewrite ssl

# Remove sites padrão para evitar conflitos
RUN a2dissite 000-default.conf default-ssl.conf || true

# Copia arquivos do projeto (se você não usa volumes)
COPY ./public /var/www/html
COPY ./vendor /var/www/vendor
COPY ./app /var/www/app
COPY ./config/php.ini /usr/local/etc/php/

# Copia certificado Cloudflare
COPY ./certs/origin.pem /etc/ssl/certs/origin.pem
COPY ./certs/origin.key /etc/ssl/private/origin.key
RUN chmod 600 /etc/ssl/private/origin.key && chmod 644 /etc/ssl/certs/origin.pem

# Copia config SSL (ajustável via ARG)
ARG ENVIRONMENT=prod
COPY ./apache-ssl-${ENVIRONMENT}.conf /etc/apache2/sites-available/360help-ssl.conf
RUN a2ensite 360help-ssl.conf

# Exposição de portas
EXPOSE 80 443

CMD ["apache2-foreground"]
