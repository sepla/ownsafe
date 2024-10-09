#!/bin/bash
echo "Starting app installation"
cd /
if [ ! -f "/ownsafe/www/index.html" ]; then
    git clone https://github.com/sepla/ownsafe.git 
fi
cp -r /ownsafe/www/* /var/www/html
cp /ownsafe/docker/php/php-logging.conf /usr/local/etc/php-fpm.d/zz-log.conf
mkdir -p /etc/nginx/conf.d
cp /ownsafe/docker/nginx-conf/* /etc/nginx/conf.d -r
echo "App installation finisched"

