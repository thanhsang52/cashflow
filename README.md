# CASHFLOW
## Giới thiệu
Dự án cuối khóa lớp DEVOPS 01: Áp dụng Docker compose để deploy 1 source code mã nguồn mở PHP lấy từ Smartend CMS (webiste sử dụng database MSSQL) lên 1 VPS digitalocean có địa chỉ IP 159.223.60.250.
Các service cơ bản cho website: nginx,php8.1-fpm, redis-server.
```bashBuild và chạy container:
docker build -t laravel-mssql .
docker run -d -p 80:80 --env-file ./core/.env --name cashflow-app laravel-mssql
Test kết nối:
docker exec -it container-name /var/www/html/docker/test-mssql.sh
```
## Build source code bằng docker compose trên VPS
```bash
docker-compose up -d --build
```
## Websites
https://sangshare.cloud/
https://sangshare.cloud/admin/login
## Grafana
URL https://grafana.sangshare.cloud/
Account: mentor/1234567890
