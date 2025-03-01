FROM ubuntu:latest

ENV TZ=Europe/Zurich

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -y \
  ca-certificates \
  git \
  nginx \
  php-fpm \
  php-curl \
  php-dom \
  php-gd \
  php-intl \
  php-json \
  php-mbstring \
  php-xml \
  php-zip && \
  apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy the existing nginx configuration
COPY nginx/default.conf /etc/nginx/sites-available/default

# Create directory for PHP-FPM socket
RUN mkdir -p /run/php

# Remove default content
RUN rm -rf /var/www/html/*

# Clone the Kirby Starterkit
RUN git clone --depth 1 https://github.com/getkirby/starterkit.git /var/www/html

# Fix files and directories ownership
RUN chown -R www-data:www-data /var/www/html/

# Verify nginx config is correct
RUN nginx -t

# Copy startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]