# Use official PHP image with Apache
FROM php:8.1-apache

# Install the PHP extensions we need
RUN apt-get update && \
    apt-get install -y --no-install-recommits libpng-dev libonig-dev libxml2-dev zip unzip && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install -j "$(nproc)" pdo_mysql mbstring xml gd

# Enable Apache mod_rewrite for URL rewrites and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN if command -v a2enmod >/dev/null 2>&1; then \
        a2enmod rewrite headers \
    ;fi

# Change document root for Apache
RUN sed -i -e 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/*.conf
RUN sed -i -e 's|/var/www/|/var/www/html/public|g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set recommended PHP.ini settings for better performance.
RUN { \
        echo 'opcache.memory_consumption=192'; \
        echo 'opcache.interned_strings_buffer=12'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

EXPOSE 80

# Define volumes for Kirby installation and content.
VOLUME /var/www/html

# Copy Kirby files into image
COPY . /var/www/html/

# Set server name
RUN echo 'ServerName localhost' >> /etc/apache2/conf-available/fqdn.conf && a2enconf fqdn
