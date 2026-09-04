#!/bin/bash
set -e

PORT=""

sed -i "s/Listen 80/Listen /" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html/server && pm2 start server.js --name "casino-server"

exec apache2-foreground
