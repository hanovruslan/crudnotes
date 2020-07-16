FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install json
RUN cd /var/www/html && bin/console doctrine:migrations:migrate -n && bin/console doctrine:fixtures:load -n

CMD ["php-fpm"]
EXPOSE 9000
