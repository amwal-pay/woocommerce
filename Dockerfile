# Use an official PHP runtime as the base image
FROM php:7.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install necessary PHP extensions and dependencies.
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli zip

# Enable Apache modules
RUN a2enmod rewrite

# Download WooCommerce plugin
RUN curl -o woocommerce.zip -SL https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip \
    && unzip woocommerce.zip -d /var/www/html/wp-content/plugins/ \
    && rm woocommerce.zip

# Change ownership of files to Apache user
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
