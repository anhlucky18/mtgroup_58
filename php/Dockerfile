FROM php:7.3-fpm

# Fix repository Debian EOL nếu sử dụng archive
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list || true \
    && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list || true \
    && sed -i '/buster-updates/d' /etc/apt/sources.list || true \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y --no-install-recommends \
    libprocps-dev \
    libpcre3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    locales \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt các extension PHP cần thiết cho Laravel 5.8 & Botble CMS
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Cài đặt Composer (bản 2.2 LTS hỗ trợ tối đa cho PHP 7.3)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/minhthanh3

CMD ["php-fpm"]

