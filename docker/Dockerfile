FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    ffmpeg
RUN pecl install \
    redis \
    imagick \
    xdebug
RUN docker-php-ext-enable \
    redis \
    imagick \
    xdebug
RUN docker-php-ext-configure \
    gd --with-freetype --with-jpeg
RUN docker-php-ext-configure \
    calendar
RUN docker-php-ext-configure \
    pcntl --enable-pcntl
RUN docker-php-ext-install \
    exif \
    -j$(nproc) \
    bcmath \
    gd \
    intl \
    pdo \
    pdo_mysql \
    soap \
    zip \
    calendar \
    pcntl
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV PHP_MEMORY_LIMIT=512M
RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memory-limit.ini
ENV NODE_VERSION=16.17.0
