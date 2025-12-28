FROM php:8.2-apache

# Disable other MPMs, enable prefork
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Fix Apache ServerName warning (important)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Enable rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
