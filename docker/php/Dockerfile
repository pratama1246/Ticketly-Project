FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & build tools
RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    imagemagick \
    imagemagick-dev \
    libxml2-dev \
    oniguruma-dev \
    openssl-dev \
    linux-headers \
    $PHPIZE_DEPS

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        intl \
        zip \
        mbstring \
        bcmath \
        fileinfo \
        exif \
        opcache \
        xml \
        soap \
        sockets

# Install Imagick via PECL
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Clean up build dependencies to keep image size small
RUN apk del --no-cache $PHPIZE_DEPS imagemagick-dev

# Install Composer from official image
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Copy custom php and opcache configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Ensure www-data user has correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
