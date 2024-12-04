# Use the official PHP image as a base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/inventario

# install composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

# Install system dependencies
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
    
RUN composer install --no-dev --prefer-dist \
    && npm install \
    && npm run production
# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl  zip \ libzip-dev \ freetype \ libjpeg-turbo \ libpng \ freetype-dev \ libjpeg-turbo-dev \ libpng-dev \ oniguruma-dev \ gettext-dev \ freetype-dev \ nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents to the working directory
COPY . .

COPY /var/www/inventario/deploy/nginx.conf /etc/nginx/sites-enabled/default.conf

# Copy the existing application directory permissions to the working directory
COPY --chown=www-data:www-data . /var/www/inventario

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]