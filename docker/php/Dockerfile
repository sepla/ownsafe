FROM php:8.1-fpm

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libxml2-dev git

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mysqli pdo pdo_mysql xml
RUN rm /ownsafe -rf
RUN rm /nginx-conf -rf
RUN git clone https://github.com/sepla/ownsafe.git /ownsafe
RUN mkdir -p /nginx-conf
RUN sleep 5
RUN cp -r /ownsafe/www/* /var/www/html
RUN cp -r /ownsafe/docker/nginx-conf/* /nginx-conf
