FROM php:7.2.2-apache
RUN apt-get update -y && apt-get install -y openssl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql mbstring
WORKDIR /app
ADD . /app
RUN composer install

CMD sleep 5 \
    && php artisan migrate:fresh \
    && php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000
