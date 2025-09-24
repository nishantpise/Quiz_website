# Use official PHP + Apache image
FROM php:8.1-apache

# Copy your project files into the Apache root
COPY . /var/www/html/

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
