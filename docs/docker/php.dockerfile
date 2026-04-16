# Use the official PHP image as the base image
# FROM php:7.4-fpm
FROM php:8.2-fpm

RUN touch /var/log/error_log

ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -gid 1000 wp && adduser -gid 1000 -shell /bin/sh -disabled-login -disabled-password wp

COPY ./php/custom-php.ini /usr/local/etc/php/php.ini

RUN mkdir -p /var/www/html

RUN chown wp:wp /var/www/html

WORKDIR /var/www/html

# settings for live server
# Remove conflicting repository configuration.
RUN rm -f /etc/apt/sources.list.d/debian.sources

# Ensure HTTPS support is in place.
RUN apt-get update && apt-get install -y ca-certificates

# Set up repositories to use HTTPS.
RUN echo "deb https://deb.debian.org/debian bookworm main contrib non-free" > /etc/apt/sources.list \
    && echo "deb https://security.debian.org/debian-security bookworm-security main contrib non-free" >> /etc/apt/sources.list \
    && echo "deb https://deb.debian.org/debian bookworm-updates main contrib non-free" >> /etc/apt/sources.list

# Enable mysqli extension
#RUN apt-get update && apt-get upgrade -y \

RUN apt-get update && apt-get install -y \
	libzip-dev \
	libxml2-dev \
	libgmp-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql soap gmp sockets && docker-php-ext-enable mysqli pdo_mysql soap gmp sockets