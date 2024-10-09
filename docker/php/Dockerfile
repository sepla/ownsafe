FROM php:8.1-fpm

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libxml2-dev git

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mysqli pdo pdo_mysql xml
RUN git clone https://github.com/sepla/ownsafe.git /ownsafe
RUN cp /ownsafe/docker/install-app.sh /
RUN chmod +x /install-app.sh 
#ENTRYPOINT ["/ownsafe/docker/install-app.sh"]
