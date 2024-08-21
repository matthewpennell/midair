FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/app/public

# Update packages and install zip and intl
RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip libicu-dev \
&& docker-php-ext-install zip \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
&& docker-php-ext-enable intl

# Copy composer to container in case it is needed
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

# Log into Docker container terminal and run "composer install" to create /vendor
