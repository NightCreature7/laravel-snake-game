# Use the official PHP 7.4 image with Apache
FROM php:7.4.27-apache

# Update package lists and install required packages
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip

# Enable Apache's mod_rewrite
RUN a2enmod rewrite

# Set environment variables within Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_CONFDIR /etc/apache2
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' $APACHE_CONFDIR/sites-available/000-default.conf

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Copy the Laravel application files into the container
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel's dependencies
RUN composer install

# Set appropriate file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
