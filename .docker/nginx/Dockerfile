ARG IMAGE_NAME=nginx
ARG VERSION=1.25.1

FROM ${IMAGE_NAME}:${VERSION} as base

WORKDIR /var/www/app

EXPOSE 80

COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./.docker/nginx/localhost.crt /etc/nginx/cert/localhost.crt
COPY ./.docker/nginx/localhost.key /etc/nginx/cert/localhost.key