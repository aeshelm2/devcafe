FROM php:8.1-apache
ARG USER=ecom
ARG UID=1000
ARG GID=1000

ENV PHP_EXTENSIONS bcmath bz2 calendar exif gd gettext intl mysqli opcache pdo_mysql redis soap sockets sodium sysvmsg sysvsem sysvshm xsl zip pcntl

# Install dependencies
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
    apt-utils \
    sendmail-bin \
    sendmail \
    sudo \
    iproute2 \
    git \
    gnupg2 \
    ca-certificates \
    lsb-release \
    software-properties-common \
    libbz2-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libgeoip-dev \
    wget \
    libgmp-dev \
    libgpgme11-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    libc-client-dev \
    libkrb5-dev \
    libicu-dev \
    libldap2-dev \
    libpspell-dev \
    # librecode0 \
    # librecode-dev \
    libtidy-dev \
    libxslt1-dev \
    libyaml-dev \
    libzip-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Configure the gd library
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure \
  imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-configure \
  opcache --enable-opcache
  
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    gettext \
    gmp \
    imap \
    intl \
    mysqli \
    opcache \
    pdo_mysql \
    pspell \
    # recode \
    shmop \
    soap \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    tidy \
    # xmlrpc \
    xsl \
    zip \
    pcntl

COPY ./conf/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# CONFIGURE MAILHOG IN PHP
RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g ${UID} ${USER} \
    && useradd -g ${GID} -u ${UID} -d /var/www -s /bin/bash ${USER} \
    && usermod -aG sudo ${USER}

ENV APACHE_RUN_USER ${USER}
ENV APACHE_RUN_GROUP ${USER}

COPY ./conf/php.ini /usr/local/etc/php/
# COPY --chown=${USER} ./composer.json /var/www/html/ecommerce
# COPY --chown=${USER} ./auth.json /var/www/html/ecommerce

USER ${USER}:${USER}