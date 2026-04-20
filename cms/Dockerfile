FROM ubuntu:latest

ENV TZ=Europe/Zurich

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install --no-install-recommends -y \
  ca-certificates \
  curl \
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
  php-zip \
  unzip && \
  apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY nginx/default.conf /etc/nginx/sites-available/default

RUN mkdir -p /run/php

RUN rm -rf /var/www/html/*

RUN git clone --depth 1 https://github.com/getkirby/plainkit.git /var/www/html

COPY composer.json /var/www/html/composer.json
RUN cd /var/www/html && composer install --no-dev --no-security-blocking --optimize-autoloader

RUN rm -rf /var/www/html/content
COPY content /var/www/html/content

RUN mkdir -p /var/www/html/site/sessions
RUN mkdir -p /var/www/html/site/accounts
RUN mkdir -p /var/www/html/site/plugins
RUN mkdir -p /var/www/html/media

RUN chown -R www-data:www-data /var/www/html/

RUN nginx -t

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]