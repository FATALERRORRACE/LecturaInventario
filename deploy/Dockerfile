# deploy/Dockerfile

# Set working directory
WORKDIR /var/www/inventario

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    nodejs \
    npm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    libpq-dev \
    libonig-dev

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
    && npm run production
RUN chown -R www-data:www-data /var/www/inventario/vendor \
    && chmod -R 775 /var/www/inventario/vendor

# stage 2: production stage

# install nginx
RUN apk add --no-cache \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    oniguruma-dev \
    gettext-dev \
    freetype-dev \
    nginx \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && docker-php-ext-install bcmath \
    && docker-php-ext-enable bcmath \
    && docker-php-ext-install exif \
    && docker-php-ext-enable exif \
    && docker-php-ext-install gettext \
    && docker-php-ext-enable gettext \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable opcache \
    && rm -rf /var/cache/apk/*

# copy files from the build stage
COPY --from=build /var/www/inventario /var/www/inventario
