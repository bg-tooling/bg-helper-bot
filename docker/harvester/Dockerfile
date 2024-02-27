FROM php:8.3-cli

# By default production values. Overridde this vars in your local docker-compose for using in develop or test mode
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV TZ="Europe/Moscow"

# Installing software
RUN apt-get update \
    && apt-get -y --no-install-recommends install git unzip wget vim tcpdump iproute2 coreutils iputils-ping \
	dnsutils tzdata openssh-client net-tools gnupg libpq-dev 

	
# Install Composer and make it available in the PATH
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install and configure opcahce
RUN docker-php-ext-install opcache sockets

# If you want to use Xdebug and OPCache together, you must have the zend_extension line for Xdebug below the line for OPCache
# or in a file starting with a higher number (ie. 99-xdebug.ini vs 20-opcache.ini), otherwise they won't work properly together.
ADD ./docker/harvester/common.ini "${PHP_INI_DIR}/conf.d/zz-common.ini"
ADD ./docker/harvester/opcache.ini "${PHP_INI_DIR}/conf.d/xx-opcache.ini"

RUN printf '[PHP]\ndate.timezone = "${TZ}"\n' > "${PHP_INI_DIR}/conf.d/tzone.ini"

COPY ./ /var/www/app
RUN chown -R www-data:www-data /var/www/app/var

USER www-data
WORKDIR /var/www/app

CMD php /var/www/app/bin/harvester.php
