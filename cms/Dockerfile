FROM ubuntu:24.04

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
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY nginx/default.conf /etc/nginx/sites-available/default

RUN mkdir -p /run/php

RUN rm -rf /var/www/html/*

RUN git clone --depth 1 https://github.com/getkirby/plainkit.git /var/www/html

COPY composer.json /var/www/html/composer.json
RUN cd /var/www/html && composer install --no-dev --no-security-blocking --optimize-autoloader

RUN rm -rf /var/www/html/content
COPY content /var/www/html/content

# Bake custom site files (templates, models, blueprints, snippets, config, plugins, ...)
# into the image so prod (which initializes the kirby_data named volume from the image)
# has all of them. In dev these are overlaid by bind-mounts from compose.dev.yml.
COPY site /var/www/html/site
COPY utils /var/www/html/utils
COPY index.php /var/www/html/index.php

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