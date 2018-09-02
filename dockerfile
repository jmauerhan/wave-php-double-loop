FROM php:7.1-cli
RUN apt-get update
RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

EXPOSE 80

WORKDIR /var/www

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www", "/var/www/public/router.php"]