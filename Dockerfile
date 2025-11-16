FROM php:8.2-apache

# نصب ابزارهای مورد نیاز
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

# نصب اکستنشن‌های PHP مورد نیاز لاراول
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# فعال کردن mod_rewrite برای مسیریابی لاراول
RUN a2enmod rewrite

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کاری
WORKDIR /var/www/html

# پیکربندی آپاچی برای اجرای از public
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN service apache2 restart