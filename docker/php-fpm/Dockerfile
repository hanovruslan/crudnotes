FROM php:7.2-fpm-alpine
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install json
WORKDIR /var/www/html
EXPOSE 9000
CMD ["php-fpm"]