FROM php:8.1-apache

RUN a2enmod ssl && a2enmod rewrite

COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data .
RUN chmod -R 755 .

EXPOSE 80
EXPOSE 443
