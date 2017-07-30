FROM alpine:edge

MAINTAINER Jack Timblin <jacktimblin@gmail.com>

ARG ENVIRONMENT=PROD

#Add the testing repository.
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories

#Install the main php packages.
RUN set -xe && \
    apk update && apk add curl php7-fpm \
        php7-curl \
        php7-json \
        php7-mbstring \
        php7-dev \
        php7-mcrypt \
        php7-zip \
        php7-xml \
        php7-intl \
        php7-simplexml \
        php7-dom \
        php7-tokenizer \
        php7-ctype

COPY vagrant/xdebug.ini /data/xdebug.ini

#do a -v .:/data/dashboard to use local dashboard.
ADD . /data/web-scraper

# Install and run composer.
RUN set -xe && \
    apk add --no-cache --virtual .build-deps git curl php7-phar php7-openssl php7-zlib && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer && chmod -R 777 /usr/bin/composer && \
    cd /data/web-scraper && composer install && \
    apk del .build-deps && rm /usr/bin/composer

# Install xdebug if we are on a development build
RUN set -xe && \
    if [[ $ENVIRONMENT = 'DEV' ]]; then \
        apk add --no-cache --virtual .build-deps make gcc g++ autoconf openssl && \
        wget http://xdebug.org/files/xdebug-2.5.3.tgz && tar -xvzf xdebug-2.5.3.tgz && \
        cd xdebug-2.5.3 && phpize && ./configure && make && \
        cp modules/xdebug.so /usr/lib/php7/modules && \
        apk del .build-deps && cd ../ && rm xdebug-2.5.3.tgz && rm -rf xdebug-2.5.3 && \
        cp /data/xdebug.ini /etc/php7/conf.d/xdebug.ini; \
    fi

RUN set -xe && \
    apk add nginx bash && \
    mkdir /run/nginx

COPY docker/default-nginx.conf /etc/nginx/conf.d/default.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

COPY docker/docker-entrypoint /data/docker-entrypoint

EXPOSE 80

STOPSIGNAL SIGQUIT

CMD ["/bin/bash", "/data/docker-entrypoint"]
