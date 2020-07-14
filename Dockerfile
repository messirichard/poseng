FROM php:7.2-apache

RUN apt-get update -y && apt-get install -y sendmail libpng-dev && \
    apt-get update && \
    apt-get install -y \ 
      zlib1g-dev && \
    docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli && \
    docker-php-ext-install gd && \
    docker-php-ext-enable gd && \
    apt-get update && apt-get install -y libgmp-dev && \
    ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h && \
    docker-php-ext-install gmp && \
    docker-php-ext-enable gmp && \
    apt-get update && \
    apt-get -y install \
        libmagickwand-dev \
        --no-install-recommends && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    rm -r /var/lib/apt/lists/* && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    docker-php-ext-install zip && \
    docker-php-ext-enable zip && \
    a2dismod ssl && \
    a2enmod headers && \
    cd /etc/apache2 && \
    echo "ServerTokens Prod" >> apache2.conf && \
    echo "ServerSignature Off" >> apache2.conf

COPY dir.conf /etc/apache2/mods-enabled/dir.conf

RUN a2enmod rewrite

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY src /var/www/
RUN chown -R www-data:www-data /var/www/

CMD [ "apache2-foreground" ]
