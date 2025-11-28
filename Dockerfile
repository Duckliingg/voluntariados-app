FROM php:8.1-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git zip unzip libssl-dev pkg-config curl

# Instalar extensión MongoDB para PHP
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar extensión PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar código fuente
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Copiar configuración de Apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
