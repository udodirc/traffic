FROM node:16.15 as node
FROM php:8.2-fpm

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN npm install pm2 -g

RUN apt-get update \
    && apt-get install -y curl wget git libpng-dev libwebp-dev libjpeg-dev libfreetype-dev libzip-dev zip libicu-dev \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql gd intl zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require 'squizlabs/php_codesniffer=*'
#    && pecl install xdebug \
#    && docker-php-ext-enable xdebug \
#    && printf 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && printf '\nxdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/mlm

CMD ["php-fpm"]