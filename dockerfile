# Sử dụng PHP 8.2
FROM php:8.2-fpm

# Cài đặt các extensions cần thiết
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql gd

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy source code Laravel
WORKDIR /var/www
COPY . .

# Cài đặt dependencies
RUN composer install --no-dev --optimize-autoloader

# Phân quyền
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose cổng
EXPOSE 9000
CMD ["php-fpm"]
