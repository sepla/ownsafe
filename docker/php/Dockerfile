FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y zip libzip-dev libpng-dev git
    
RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip net-tools nano


