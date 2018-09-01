FROM php:7.1.15-fpm-jessie

MAINTAINER Andrey Vorobyev <avorobyev@codenetix.com>

#####################################
# Common software:
#####################################
ENV DEBIAN_FRONTEND noninteractive

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get update && \
    apt-get install -y \
    git \
    openssl \
    libxml2-dev libxslt-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng12-dev \
    libgd-dev

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install \
    pcntl \
    mysqli \
    pdo \
    pdo_mysql \
	mbstring \
	dom \
	xmlrpc \
	xsl \
    gd \
    exif

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

#####################################
# PHPUnit:
#####################################
RUN curl -OL https://phar.phpunit.de/phpunit.phar \
    && chmod 755 phpunit.phar \
    && mv phpunit.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/phpunit.phar /usr/local/bin/phpunit

#####################################
# Composer:
#####################################
RUN curl https://getcomposer.org/composer.phar > /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

#####################################
# PHP opcache:
#####################################
ARG ENABLE_OPCACHE=false
ENV ENABLE_OPCACHE ${ENABLE_OPCACHE}
RUN RUN if [ ${INSTALL_XDEBUG} = true ]; then { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini \
;fi

#####################################
# xDebug:
#####################################
ARG INSTALL_XDEBUG=true
ENV INSTALL_XDEBUG ${INSTALL_XDEBUG}
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi

#####################################
# Extract & prepare apps configuration
#####################################
COPY ./container/php.pool.conf /usr/local/etc/php-fpm.d/php.pool.conf
COPY ./container/php.ini.php /usr/local/etc/php/conf.d/php.ini.php

RUN php /usr/local/etc/php/conf.d/php.ini.php > /usr/local/etc/php/conf.d/php.ini && \
    rm /usr/local/etc/php/conf.d/php.ini.php && \
    rm /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/zz-docker.conf

#####################################
# Project files
#####################################
WORKDIR /var/www

RUN chown www-data /var/www

COPY --chown=www-data:www-data . /var/www

RUN rm -rf container

#####################################
# Composer / npm deps / artisan
#####################################
USER www-data

RUN composer install

EXPOSE 9000

CMD ["php-fpm", "-F"]