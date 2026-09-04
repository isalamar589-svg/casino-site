#!/bin/bash
set -e

PORT="${PORT:-80}"

echo "Starting server on port $PORT..."

sed -i "s/Listen [0-9]*/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf

cd /var/www/html/server && pm2 start server.js --name "casino-server" || true

exec apache2-foreground
