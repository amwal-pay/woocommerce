# Use the official WordPress image as base
FROM wordpress:5.8-php8.0

# Set environment variables for MySQL
ENV MYSQL_ROOT_PASSWORD=root \
    MYSQL_DATABASE=wordpress \
    MYSQL_USER=wordpress \
    MYSQL_PASSWORD=password

# Install required packages
RUN apt-get update && \
    apt-get install -y \
    wget \
    unzip

# Install WooCommerce
RUN wget -O /usr/src/woocommerce.zip https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip && \
    unzip /usr/src/woocommerce.zip -d /usr/src/wordpress/wp-content/plugins/ && \
    rm /usr/src/woocommerce.zip

# Install custom plugin (replace plugin-url with your actual plugin URL)
COPY amwal.php /usr/src/wordpress/wp-content/plugins/

# Set up PHP configuration for WordPress
COPY php.ini /usr/local/etc/php/conf.d/

# Set ServerName directive to suppress warning message
RUN echo "ServerName localhost" >> /etc/apache2/conf-available/servername.conf && \
    a2enconf servername

# Expose ports
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]
