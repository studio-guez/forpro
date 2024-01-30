FROM ubuntu:22.04

ENV TZ=Europe/Berlin

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
  apache2 \
  apache2-utils \
  ca-certificates \
  php \
  libapache2-mod-php \
  php-curl \
  php-dom \
  php-gd \
  php-intl \
  php-json \
  php-mbstring \
  php-xml \
  php-zip && \
  apt-get clean && rm -rf /var/lib/apt/lists/*

COPY ./conf/default.conf /etc/apache2/sites-available/000-default.conf

RUN rm /var/www/html/*

COPY . /var/www/html/

RUN a2enmod headers rewrite

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 80

CMD [ "/usr/sbin/apache2ctl", "-DFOREGROUND" ]
