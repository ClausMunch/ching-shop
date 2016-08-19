#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

add-apt-repository ppa:ondrej/php -y
apt-get update --fix-missing
apt-get dist-upgrade -y

apt-get install htop imagemagick -y
apt-get install php-imagick php7.0-mbstring -y
composer global require "squizlabs/php_codesniffer=^2.5"
composer global require "phpmd/phpmd=@stable"

gem install bundler

function installPHPUnit
{
    if [ ! $(type -P phpunit) ]; then
        ln -s /home/vagrant/ching-shop/vendor/phpunit/phpunit/phpunit /usr/local/bin/phpunit
    fi
}
installPHPUnit

function removeHHVM
{
    if [ $(type -P hhvm) ]; then
        /usr/share/hhvm/uninstall_fastcgi.sh
        apt-get remove hhvm -y
    fi
}
removeHHVM

function installXdebug
{
    if [ -z "$(php -i | grep xdebug)" ]; then
        mkdir -p ~/installs
        cd ~/installs/
        wget https://xdebug.org/files/xdebug-2.4.0.tgz
        tar -xvzf xdebug-2.4.0.tgz
        cd xdebug-2.4.0
        phpize
        ./configure
        make
        mkdir -p /usr/lib/php/xdebug/
        cp modules/xdebug.so /usr/lib/php/xdebug/
        echo 'zend_extension=/usr/lib/php/xdebug/xdebug.so' >> /etc/php/7.0/fpm/php.ini
        echo 'zend_extension=/usr/lib/php/xdebug/xdebug.so' >> /etc/php/7.0/cli/php.ini
        export CONFIG = "
xdebug.remote_enable=on
xdebug.remote_log=/tmp/xdebug.log
xdebug.remote_port=9001
xdebug.remote_connect_back=1
xdebug.remote_host=192.168.10.1
xdebug.idekey=PHPSTORM
xdebug.force_error_reporting=1
xdebug.force_display_errors=1
"
        for ITEM in CONFIG; do
            echo ${ITEM} >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
            echo ${ITEM} >> /etc/php/7.0/cli/conf.d/20-xdebug.ini
        done
    fi
}
installXdebug

function installPHPRedis
{
    if [ -z "$(php -i | grep redis.ini)" ]; then
        mkdir -p ~/installs
        cd ~/installs/
        git clone https://github.com/phpredis/phpredis.git
        cd phpredis
        git checkout php7
        phpize
        ./configure
        make && make install
        make test
        echo "extension=redis.so" > /etc/php/7.0/fpm/conf.d/20-redis.ini
        echo "extension=redis.so" > /etc/php/7.0/cli/conf.d/20-redis.ini
    fi
}

function setUpSupervisor
{
    apt-get install supervisor -y
    cp -f /home/vagrant/ching-shop/vagrant/supervisor.conf /etc/supervisor/conf.d/
    mkdir -p /var/log/ching-shop && touch /var/log/ching-shop/worker.log
    supervisorctl reread
    supervisorctl update
    supervisorctl restart ching-shop-worker:*
}
setUpSupervisor

service php7.0-fpm restart
service nginx restart
