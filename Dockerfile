# Use a base image with PHP and Apache
FROM php:7.4-apache

# Set the working directory
WORKDIR /var/www/html

# Copy files and directories from the repository into the container
COPY css/*.css css/
COPY icons/ icons/
COPY images/ images/
COPY js/ js/
COPY amwal-payment-link.php .
COPY amwal.php .
COPY example.mp4 .

# Expose port 80 to access the web server
EXPOSE 80

# Start Apache web server when the container starts
CMD ["apache2-foreground"]
