FROM nginx:latest

RUN apt-get update && \
    apt-get install -y zip libzip-dev libpng-dev git

RUN touch /var/log/nginx/access.log
RUN touch /var/log/nginx/error.log
#RUN git clone https://github.com/sepla/ownsafe.git /src
#WORKDIR /src
#RUN cp /src/* /var/www/html -R




