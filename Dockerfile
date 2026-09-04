FROM php:8.2-apache

# Install system dependencies, Node.js and PM2
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    ca-certificates \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && update-ca-certificates \
    && docker-php-ext-install pdo_mysql mysqli mbstring gd zip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g pm2 \
    && a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Install Node dependencies in server directory
RUN cd /var/www/html/server && npm install --production

# Setup entrypoint script and remove Windows carriage returns
COPY entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

# Permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["/entrypoint.sh"]
