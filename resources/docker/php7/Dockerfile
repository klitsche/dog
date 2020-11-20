FROM php:7.4-cli-alpine

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS ; \
    pecl install xdebug-stable; \
    docker-php-ext-enable xdebug; \
    apk del --no-network .build-deps;

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app