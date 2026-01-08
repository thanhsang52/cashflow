# cashflow

service nginx start
service php8.1-fpm start
service redis-server start

Build và chạy container:
docker build -t laravel-mssql .
docker run -d -p 80:80 --env-file ./core/.env --name cashflow-app laravel-mssql
Test kết nối:
docker exec -it container-name /var/www/html/docker/test-mssql.sh
