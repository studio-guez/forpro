FROM php:8.0-apache

ENV VERSION="1.4.3"
ENV BASE_URL="http://localhost:8003"
ENV LANGUAGE="french"
ENV DEBUG_MODE="TRUE"
ENV DB_HOST="mysql"
ENV DB_NAME="easyappointments"
ENV DB_USERNAME="root"
ENV DB_PASSWORD="***REMOVED***"
ENV GOOGLE_SYNC_FEATURE=FALSE
ENV GOOGLE_PRODUCT_NAME=""
ENV GOOGLE_CLIENT_ID=""
ENV GOOGLE_CLIENT_SECRET=""
ENV GOOGLE_API_KEY=""

EXPOSE 80

WORKDIR /var/www/html

COPY ./assets/99-overrides.ini /usr/local/etc/php/conf.d

COPY ./assets/docker-entrypoint.sh /usr/local/bin

RUN apt-get update \
    && apt-get install -y libfreetype-dev libjpeg62-turbo-dev libpng-dev unzip wget nano \
	&& curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
      curl gd mbstring mysqli xdebug gettext \
    && docker-php-ext-enable xdebug \
    && wget https://github.com/alextselegidis/easyappointments/releases/download/${VERSION}/easyappointments-${VERSION}.zip \
    && unzip easyappointments-${VERSION}.zip \
    && rm easyappointments-${VERSION}.zip \
    && echo "alias ll=\"ls -al\"" >> /root/.bashrc \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && chown -R www-data:www-data .

ENTRYPOINT ["docker-entrypoint.sh"]

