FROM php:8.2-apache

# Install system dependencies, Node.js and PM2
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mysqli mbstring gd zip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g pm2 \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Install Node dependencies in server directory
RUN cd /var/www/html/server && npm install --production

# Permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Create startup script
RUN printf '#!/bin/bash\nPORT=\nsed -i "s/Listen .*/Listen /" /etc/apache2/ports.conf\nsed -i "s/<VirtualHost \\*:[0-9]*>/<VirtualHost *:>/" /etc/apache2/sites-available/000-default.conf\ncd /var/www/html/server && pm2 start server.js --name "casino-server"\nexec apache2-foreground\n' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
