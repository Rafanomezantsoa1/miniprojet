FROM php:8.2-apache

# installer extension PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# activer mod_rewrite
RUN a2enmod rewrite

# copier le projet dans apache
COPY . /var/www/html/

# permissions
RUN chown -R www-data:www-data /var/www/html