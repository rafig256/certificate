FROM php:8.2-apache

# نصب ابزار کمکی برای نصب اکستنشن‌ها (بسیار قدرتمند و هوشمند)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql mbstring zip exif pcntl gd intl

# نصب ابزارهای عمومی سیستم که لاراول/کامپوزر نیاز دارند
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# فعال کردن mod_rewrite
RUN a2enmod rewrite

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
