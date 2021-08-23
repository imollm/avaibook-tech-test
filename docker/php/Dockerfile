FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        git \
        wget \
        unzip \
        libaio1 \
        libpng-dev \
        libzip-dev \
        libpq-dev \
        libssh-dev \
        libonig-dev \
        libxslt1-dev \
    && docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN usermod -u 1000 www-data
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/
