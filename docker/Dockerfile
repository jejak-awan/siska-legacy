# Dockerfile for Laravel + Vue.js
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    supervisor

# Install Node.js 18 LTS from NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install PHP GD extension
RUN docker-php-ext-install gd

# Install PHP zip extension
RUN docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy backend files
COPY backend/ /var/www/backend/

# Install backend dependencies
WORKDIR /var/www/backend
RUN composer install

# Copy frontend files
COPY frontend/ /var/www/frontend/

# Install frontend dependencies (tanpa build untuk development)
WORKDIR /var/www/frontend
RUN npm install

# Copy supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose ports for Laravel and Vite
EXPOSE 8000 5173

# Start supervisor
CMD ["/usr/bin/supervisord"]
