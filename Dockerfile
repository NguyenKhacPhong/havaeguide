# Stage 1: Sử dụng PHP Alpine để giảm kích thước
FROM php:8.1.18-alpine AS base

# Cài đặt các extension PHP cần thiết cho Laravel và Apache
RUN docker-php-ext-install pdo_mysql \
    && apk add --no-cache apache2 apache2-ssl \
    && a2enmod rewrite \
    && sed -ri 's!/var/www/localhost/htdocs!/var/www/html!g' /etc/apache2/httpd.conf \
    && mkdir -p /run/apache2

WORKDIR /var/www/html

# Stage 2: Sao chép mã nguồn và cài đặt dependencies
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-scripts --no-autoloader --no-dev --ignore-platform-reqs && rm -rf /root/.composer

COPY . .

# Chạy composer dump-autoload để load các dependencies đã cài đặt
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Stage 3: Xây dựng ứng dụng Laravel và copy vào stage cuối cùng
FROM base AS final

COPY --from=composer /app/vendor/ /var/www/html/vendor/
COPY --from=composer /app/ /var/www/html/

# Đặt các quyền để Apache có thể truy cập vào tất cả các tệp tin trong thư mục html
RUN chown -R apache:apache /var/www/html \
    && chmod -R 755 /var/www/html \
    && rm -rf /run/apache2/* /var/cache/apk/*

# Mở port 80 để Apache có thể lắng nghe kết nối
EXPOSE 80

# Khởi động Apache khi container được khởi động
CMD ["httpd", "-DFOREGROUND"]