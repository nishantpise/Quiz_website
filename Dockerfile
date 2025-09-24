# Use official PHP + Apache image
FROM php:8.1-apache

# Install PostgreSQL client libraries and PHP extensions
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pgsql pdo_pgsql

# Copy project files into Apache root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
