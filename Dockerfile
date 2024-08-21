FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/app/public

# Update packages and install zip (for Composer) and intl (for CodeIgniter)
RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip libicu-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl && docker-php-ext-enable intl
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy composer to container in case it is needed
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update
# Log into Docker container terminal and run composer commands to create or update /vendor

# Execute all database migrations
CMD cd /var/www && php spark migrate && /usr/sbin/apache2ctl -D FOREGROUND
