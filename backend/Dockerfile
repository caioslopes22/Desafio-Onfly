FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y     git zip unzip libpng-dev libzip-dev libonig-dev libxml2-dev     && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create fresh Laravel app at build time
RUN composer create-project laravel/laravel . --no-interaction

# Install JWT auth
RUN composer require tymon/jwt-auth

# Copy our custom application files (controllers, models, routes, etc.)
COPY custom/ /tmp/custom/

# Move files into place
RUN cp -r /tmp/custom/app /var/www/html/  && cp -r /tmp/custom/database /var/www/html/  && cp -r /tmp/custom/routes /var/www/html/  && cp -r /tmp/custom/config /var/www/html/ || true

# Publish jwt config and generate keys
RUN php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider" --force  && php artisan key:generate  && php artisan jwt:secret --force

EXPOSE 9000
CMD ["php-fpm"]
