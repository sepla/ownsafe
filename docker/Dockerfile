FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y zip libzip-dev libpng-dev git
RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip


RUN git clone https://github.com/sepla/ownsafe.git /src
WORKDIR /src
RUN cp /src/* /var/www/html -R




