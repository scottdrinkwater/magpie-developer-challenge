# syntax=docker/dockerfile:1

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install

EXPOSE 80