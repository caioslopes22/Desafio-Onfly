FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definimos a pasta de trabalho como /laravel
WORKDIR /laravel

# Instala dependências do Laravel no primeiro build
RUN composer create-project laravel/laravel . --no-interaction

# Instala JWT Auth
RUN composer require tymon/jwt-auth

# Copia arquivos customizados para dentro do container
COPY custom/ /tmp/custom/

# Move arquivos customizados para o projeto Laravel
RUN cp -r /tmp/custom/app /laravel/ \
    && cp -r /tmp/custom/database /laravel/ \
    && cp -r /tmp/custom/routes /laravel/ \
    && cp -r /tmp/custom/config /laravel/ || true

# Publica config do JWT e gera chaves
RUN php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider" --force \
    && php artisan key:generate \
    && php artisan jwt:secret --force

EXPOSE 9000
CMD ["php-fpm"]
