FROM php:7.4

WORKDIR /www

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /www

EXPOSE 808

CMD php -S 0.0.0.0:808