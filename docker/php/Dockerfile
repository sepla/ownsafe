FROM php:8.1-fpm

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libxml2-dev git

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mysqli pdo pdo_mysql xml
RUN git clone https://github.com/sepla/ownsafe.git /ownsafe
RUN cp /ownsafe/docker/install-app.sh /
RUN chmod +x /install-app.sh
RUN mkdir -p /nginx-conf
#RUN mkdir -p /docker/php
RUN cp -r /ownsafe/www/* /var/www/html
RUN cp -r /ownsafe/docker/nginx-conf/* /nginx-conf
#RUN cp /ownsafe/docker/php/php-logging.conf /usr/local/etc/php-fpm.d/zz-log.conf
#ENTRYPOINT ["/ownsafe/docker/install-app.sh"]
