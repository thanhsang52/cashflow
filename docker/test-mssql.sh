#!/bin/bash

echo "Testing MSSQL connection..."

# Kiểm tra ODBC driver
echo "Checking ODBC drivers:"
odbcinst -q -d

# Kiểm tra PHP extensions
echo "Checking PHP extensions:"
php -m | grep -E "(sqlsrv|pdo_sqlsrv)"

# Test kết nối database
php -r "
try {
    \$host = getenv('DB_HOST') ?: 'localhost';
    \$port = getenv('DB_PORT') ?: '1433';
    \$database = getenv('DB_DATABASE') ?: 'master';
    \$username = getenv('DB_USERNAME');
    \$password = getenv('DB_PASSWORD');
    
    \$dsn = \"sqlsrv:Server=\$host,\$port;Database=\$database\";
    \$pdo = new PDO(\$dsn, \$username, \$password);
    echo \"✓ MSSQL connection successful!\n\";
} catch (Exception \$e) {
    echo \"✗ MSSQL connection failed: \" . \$e->getMessage() . \"\n\";
}
"