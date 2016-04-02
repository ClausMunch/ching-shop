#!/usr/bin/env bash

if [ -z "$(php -i | grep redis.ini)" ]; then
    mkdir -p ~/installs
    cd ~/installs/
    git clone git@github.com:phpredis/phpredis.git
    cd phpredis
    git checkout php7
    phpize
    ./configure --enable-redis-igbinary
    make && make install
    make test
    echo "extension=redis.so" > /etc/php/7.0/fpm/conf.d/20-redis.ini
    echo "extension=redis.so" > /etc/php/7.0/cli/conf.d/20-redis.ini
fi
