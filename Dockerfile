# Use official PHP with Apache
FROM php:8.2-apache

# Enable PostgreSQL support
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set permissions (safe default)
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80
