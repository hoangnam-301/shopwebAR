# Chọn image PHP-FPM chính thức
FROM php:8.4-fpm

# Cập nhật và cài các tiện ích cần thiết
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    curl \
    && docker-php-ext-install zip pdo_mysql

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Tạo thư mục làm việc
WORKDIR /var/www/backend

# Copy toàn bộ code backend vào container
COPY . /var/www/backend

# Expose port để Nginx kết nối
EXPOSE 9000

# Khởi động PHP-FPM
CMD ["php-fpm"]
