FROM nginx:latest

RUN apt-get update && \
    apt-get install -y zip libzip-dev libpng-dev git

RUN mkdir -p /var/log/nginx

#RUN git clone https://github.com/sepla/ownsafe.git /ownsafe
#RUN cp -r /ownsafe/docker/nginx-conf/* /etc/nginx/conf.d
#ADD install-app.sh /install-app.sh
#RUN chmod +x /install-app.sh
#CMD ["/install-app.sh"]
#ENTRYPOINT ["/docker-entrypoint.sh","nginx"]



