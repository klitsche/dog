FROM php:8.0-rc-cli-alpine

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS zip git; \
    cd /tmp; \
    curl https://codeload.github.com/xdebug/xdebug/tar.gz/master -o xdebug.tar.gz; \
    tar -xzf xdebug.tar.gz; \
    cd xdebug-master; \
    sh rebuild.sh; \
    docker-php-ext-enable xdebug; \
    rm -rf /tmp/*; \
    apk del --no-network .build-deps;

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app