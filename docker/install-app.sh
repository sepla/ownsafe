#!/bin/bash
echo "Starting app installation"
cd /
if [ ! -f "/ownsafe/www/index.html" ]; then
    git clone https://github.com/sepla/ownsafe.git 
fi
cp -r /ownsafe/www/* /var/www/html
mkdir /var/www/docker
cp /ownsafe/docker/php/php-logging.conf /var/www/docker
mkdir /var/www/docker/nginx-conf
cp /ownsafe/docker/nginx-conf/* /var/www/docker/nginx-conf
echo "App installation finisched"

