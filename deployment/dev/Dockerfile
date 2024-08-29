FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    net-tools \
    iputils-ping \
    wkhtmltopdf \
    sudo

RUN docker-php-ext-install pdo_mysql zip exif pcntl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis

COPY php/custom.ini /usr/local/etc/php/conf.d/custom.ini

ARG HOST_UID
ARG HOST_GID

RUN addgroup --gid $HOST_GID ponygroup && \
    adduser --disabled-password --gecos '' --uid $HOST_UID --ingroup ponygroup pony && \
    usermod -aG ponygroup www-data

COPY . /var/www/html

RUN chown -R pony:ponygroup /var/www/html && \
    chmod -R 775 /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000

USER pony

CMD ["php-fpm"]
