#!/bin/bash

# Chuyển đến thư mục Laravel
cd /var/www/html/core

# Tạo các thư mục cache nếu chưa có
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

# Thiết lập quyền
chown -R www-data:www-data /var/www/html/core/storage
chown -R www-data:www-data /var/www/html/core/bootstrap/cache
chmod -R 777 /var/www/html/core/storage
chmod -R 777 /var/www/html/core/bootstrap/cache

# Chạy các lệnh Laravel setup
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Khởi động supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf