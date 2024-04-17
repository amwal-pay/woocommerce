# Use the official WordPress image as base
FROM wordpress:latest

# Set environment variables for MySQL
ENV MYSQL_ROOT_PASSWORD=root \
    MYSQL_DATABASE=wordpress \
    MYSQL_USER=wordpress \
    MYSQL_PASSWORD=password

# Update package lists
RUN apt-get update

# Install required packages individually
RUN apt-get install -y wget
RUN apt-get install -y unzip
RUN apt-get install -y mysql-client

# Install WooCommerce
RUN wget -O /usr/src/woocommerce.zip https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip && \
    unzip /usr/src/woocommerce.zip -d /usr/src/wordpress/wp-content/plugins/ && \
    rm /usr/src/woocommerce.zip

# Install custom plugin (replace plugin-url with your actual plugin URL)
COPY amwal.php /usr/src/wordpress/wp-content/plugins/

# Set up PHP configuration for WordPress
COPY php.ini /usr/local/etc/php/conf.d/

# Expose ports
EXPOSE 80

# Start MySQL service
CMD ["docker-entrypoint.sh", "apache2-foreground"]
