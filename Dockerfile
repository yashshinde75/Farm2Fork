FROM php:8.2-apache

# Remove all MPM modules completely
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_prefork.load \
          /etc/apache2/mods-enabled/mpm_prefork.conf

# Enable ONLY prefork MPM
RUN a2enmod mpm_prefork

# Fix Apache ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Enable rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
