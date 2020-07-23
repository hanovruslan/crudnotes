ARG PHP_VERSION
FROM php:${PHP_VERSION}-alpine
RUN docker-php-ext-install pdo_mysql
COPY etc/share/ /etc/share
WORKDIR /var/www/html
ENTRYPOINT ["/etc/share/entrypoint/entrypoint.sh"]
CMD []
