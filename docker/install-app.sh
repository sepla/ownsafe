#!/bin/sh
echo "Starting app installation"
cd /
cp -r /ownsafe/www/* /var/www/html
cp -r /ownsafe/docker/nginx-conf/* /nginx-conf/
#cp /ownsafe/docker/php/php-logging.conf /docker/php/php-logging.conf
echo "App installation finisched"

