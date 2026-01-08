#!/bin/bash

# Chuyển đến thư mục Laravel
cd /var/www/html/core

# Chạy các lệnh Laravel setup
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Khởi động supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf