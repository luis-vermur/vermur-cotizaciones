FROM dunglas/frankenphp:php8.3-bookworm

# Instalar extensiones PHP necesarias
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    xml \
    curl \
    zip \
    intl \
    gd \
    bcmath \
    tokenizer \
    fileinfo

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip

# Instalar y compilar assets
RUN npm ci && npm run build

# Permisos de storage
RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
    storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Cachear configuración
RUN php artisan route:cache \
    && php artisan view:cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:cache && php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000"]