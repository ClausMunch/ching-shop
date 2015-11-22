#!/usr/bin/env bash

if [ ! $(type -P phpunit) ]; then
    echo 'Installing phpunit'
    wget https://phar.phpunit.de/phpunit.phar > /dev/null
    chmod +x phpunit.phar
    mv phpunit.phar /usr/local/bin/phpunit
fi
