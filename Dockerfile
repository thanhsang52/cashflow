FROM php:8.1-fpm

# Cài đặt các package cần thiết
RUN apt-get update && apt-get install -y \
    nginx \
    redis-server \
    supervisor \
    gnupg2 \
    apt-transport-https \
    ca-certificates \
    curl \
    wget \
    unixodbc-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libldap2-dev \
    && rm -rf /var/lib/apt/lists/*

# Cấu hình và cài đặt PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install pdo pdo_mysql mysqli zip bcmath gd intl soap ldap exif

# Cài đặt Microsoft ODBC Driver cho SQL Server
RUN wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/11/prod bullseye main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && ACCEPT_EULA=Y apt-get install -y mssql-tools18 \
    && rm -rf /var/lib/apt/lists/*

# Cài đặt PHP extensions cho SQL Server
RUN pecl install sqlsrv pdo_sqlsrv redis \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv redis

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Tạo thư mục làm việc
WORKDIR /var/www/html

# Copy composer files trước để tận dụng Docker cache
COPY core/composer.json core/composer.lock ./core/

# Cài đặt dependencies trong thư mục core
RUN cd core && composer install --optimize-autoloader --no-scripts

# Copy toàn bộ project
COPY ./ .

# Tạo các thư mục cần thiết cho Laravel
RUN mkdir -p core/storage/framework/{cache,sessions,views} \
    && mkdir -p core/bootstrap/cache

# Thiết lập quyền
RUN chown -R www-data:www-data /var/www/html/cashflow \
    && chmod -R 755 /var/www/html/core/storage \
    && chmod -R 755 /var/www/html/core/bootstrap/cache

# Cấu hình Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Cấu hình PHP-FPM
RUN sed -i 's/listen = \/run\/php\/php-fpm.sock/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

# Cấu hình Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy script khởi động
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 80

# Khởi động services
CMD ["/usr/local/bin/start.sh"]