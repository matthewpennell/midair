FROM php:8.3-apache

# Update packages and install zip
RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip
RUN docker-php-ext-install zip

# Install and update Composer in case it's needed
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

WORKDIR /var/www/html
COPY . .

# Log into Docker container terminal and run "composer install" to create /vendor
