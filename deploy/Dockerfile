FROM php:8.2-fpm-alpine

# installing system dependencies and php extensions
RUN apk add --no-cache \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    nodejs \
    npm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd

# install composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/inventario

# copy necessary files and change permissions
COPY . .
RUN chown -R www-data:www-data /var/www/inventario \
    && chmod -R 775 /var/www/inventario/storage \
    && chmod -R 775 /var/www/inventario/bootstrap/cache

# install php and node.js dependencies
RUN composer install --no-dev --prefer-dist \
    && npm install \
    && npm run build

RUN chown -R www-data:www-data /var/www/inventario/vendor \
    && chmod -R 775 /var/www/inventario/vendor
