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

# Copia o certificado e chave da Cloudflare para dentro do container
COPY ./certs/origin.pem /etc/ssl/certs/origin.pem
COPY ./certs/origin.key /etc/ssl/private/origin.key

# Ativa os módulos Apache necessários
RUN a2enmod rewrite ssl

# Copia o config SSL conforme ambiente (supondo que já tem apache-ssl-cloudflare.conf)
ARG ENVIRONMENT=local
COPY ./apache-ssl-${ENVIRONMENT}.conf /etc/apache2/sites-available/360help-ssl.conf

# Ativa o site SSL
RUN a2ensite 360help-ssl.conf

# Expõe as portas HTTP e HTTPS
EXPOSE 80 443

CMD ["apache2-foreground"]