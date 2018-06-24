# PHP Docker image for Yii 2.0 Framework runtime
# ==============================================

FROM php:7.2-fpm

# add own php.ini configs
COPY configs/base.ini /usr/local/etc/php/conf.d

# command line goodies
RUN echo "export LS_OPTIONS='--color=auto'" >> /root/.bashrc
RUN echo "alias ls='ls \$LS_OPTIONS'" >> /root/.bashrc
RUN echo "alias ll='ls \$LS_OPTIONS -l'" >> /root/.bashrc
RUN echo "alias l='ls \$LS_OPTIONS -lA'" >> /root/.bashrc

# Install system packages for PHP extensions recommended for Yii 2.0 Framework
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            g++ \
            git \
            curl \
            imagemagick \
            libfreetype6-dev \
            libcurl3-dev \
            libicu-dev \
            libfreetype6-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libmagickwand-dev \
            libpq-dev \
            libpng-dev \
            libxml2-dev \
            zlib1g-dev \
            mysql-client \
            openssh-client \
            nano \
            unzip \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions required for Yii 2.0 Framework
RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pdo_pgsql

# Install PECL extensions
# see http://stackoverflow.com/a/8154466/291573) for usage of `printf`
RUN printf "\n" | pecl install \
        imagick \
        mongodb && \
    docker-php-ext-enable \
        imagick \
        mongodb