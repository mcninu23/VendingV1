FROM php:8.3-apache

# Habilitar módulos necesarios y extensiones PHP típicas para Symfony
RUN a2enmod rewrite headers && \
    apt-get update && apt-get install -y \
        git unzip libicu-dev zlib1g-dev libonig-dev default-mysql-client \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Configuración Apache: DocumentRoot -> /public y reglas
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Config PHP
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html