FROM php:8.2-cli

# Устанавливаем системные пакеты и Redis
RUN apt-get update && apt-get install -y unzip git libssl-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем composer файлы
COPY ./www/composer.json ./
COPY ./www/composer.lock ./

# Устанавливаем зависимости
RUN composer install --no-dev --optimize-autoloader

# Копируем остальные файлы
COPY ./www/ ./

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]