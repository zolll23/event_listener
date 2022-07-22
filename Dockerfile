FROM php:7.4

RUN apt-get update && apt-get install -y \
curl \
wget \
git \
procps \
libfreetype6-dev \
libjpeg62-turbo-dev \
libmcrypt-dev \
libxml2-dev \
libzip-dev \
libpng-dev \
libonig-dev \
libsqlite3-dev \
libc-client-dev libkrb5-dev \
gettext \
iputils-ping \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
&& docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip pdo_sqlite gettext imap gd sockets

RUN apt-get install -y libssh2-1-dev libssh2-1\
&& pecl install ssh2-1.2 && docker-php-ext-enable ssh2

RUN pecl install mcrypt-1.0.3
RUN docker-php-ext-enable mcrypt pdo_sqlite imap gettext

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD docker.cfg/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/event_sourcing

#RUN composer install --prefer-source --no-interaction

CMD [ "php", "./examples/demo.php" ]
