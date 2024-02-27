FROM ghcr.io/roadrunner-server/roadrunner:2023.3.11 AS roadrunner
FROM php:8.3-cli

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV TZ="Europe/Moscow"

# Installing software
RUN apt-get update \
    && apt-get -y --no-install-recommends install git unzip wget vim tzdata openssh-client net-tools gnupg libpq-dev

# Install and configure opcahce
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
     && docker-php-ext-install pgsql pdo pdo_pgsql opcache sockets \
     && docker-php-ext-enable pgsql pdo_pgsql

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr

# Install Composer and make it available in the PATH
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN git config --global --add safe.directory /var/www/app

COPY ./ /var/www/app
RUN chown -R www-data:www-data /var/www/app/var

# If you want to use Xdebug and OPCache together, you must have the zend_extension line for Xdebug below the line for OPCache
# or in a file starting with a higher number (ie. 99-xdebug.ini vs 20-opcache.ini), otherwise they won't work properly together.
ADD ./docker/harvester/common.ini "${PHP_INI_DIR}/conf.d/zz-common.ini"
ADD ./docker/harvester/opcache.ini "${PHP_INI_DIR}/conf.d/xx-opcache.ini"

RUN printf '[PHP]\ndate.timezone = "${TZ}"\n' > "${PHP_INI_DIR}/conf.d/tzone.ini"

USER www-data
WORKDIR /var/www/app

CMD rr serve -c ./tools/.rr.yaml
