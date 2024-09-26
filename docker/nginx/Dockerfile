FROM nginx:latest

RUN apt-get update && \
    apt-get install -y zip libzip-dev libpng-dev git

RUN mkdir -p /var/log/nginx
RUN touch /var/log/nginx/access.log
RUN touch /var/log/nginx/error.log
RUN chmod 664 /var/log/nginx/access.log
RUN chmod 664 /var/log/nginx/error.log

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

#RUN git clone https://github.com/sepla/ownsafe.git /src
#WORKDIR /src
#RUN cp /src/* /var/www/html -R




