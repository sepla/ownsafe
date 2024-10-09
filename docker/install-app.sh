#!/bin/bash
if [ ! -f "/ownsafe/www/index.html" ]; then
    cp -r /ownsafe/* /var/www/html
fi


if [ ! -f "/var/www/html/index.html" ]; then
    cp -r /ownsafe/* /var/www/html
fi

/usr/local/bin/docker-php-entrypoint

