FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip unzip curl git \
    libpq-dev \
    libssl-dev \
    libicu-dev \
    libxslt1-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath gd intl xsl sockets

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY .env.example .env

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwX storage bootstrap/cache

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]
