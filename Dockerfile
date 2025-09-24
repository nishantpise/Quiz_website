# Use official PHP + Apache image
FROM php:8.1-apache

# Install PostgreSQL extension and dependencies
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql pgsql

# Copy project files into Apache root
COPY . /var/www/html/

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
