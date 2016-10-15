#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

export DEBIAN_FRONTEND=noninteractive

add-apt-repository ppa:ondrej/php -y
apt-get update --quiet --fix-missing
apt-get dist-upgrade --quiet --yes --allow-change-held-packages \
    -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"

apt-get install htop imagemagick ruby --quiet --yes \
    -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"
apt-get install php-imagick php7.0-mbstring php7.0-gmp --quiet --yes \
    -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"

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
        apt-get remove hhvm --yes --force-yes
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
        CONFIG="
xdebug.remote_enable=on
xdebug.remote_log=/tmp/xdebug.log
xdebug.remote_port=9001
xdebug.remote_connect_back=1
xdebug.remote_host=192.168.10.1
xdebug.idekey=PHPSTORM
xdebug.force_error_reporting=1
xdebug.force_display_errors=1
"
        for ITEM in ${CONFIG}; do
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
installPHPRedis

function setUpSupervisor
{
    apt-get install supervisor --yes --force-yes
    cp -f /home/vagrant/ching-shop/vagrant/supervisor.conf /etc/supervisor/conf.d/
    mkdir -p /var/log/ching-shop && touch /var/log/ching-shop/worker.log
    supervisorctl reread
    supervisorctl update
    supervisorctl restart ching-shop-worker:*
}
setUpSupervisor

function installElasticSearch
{
    wget -qO - https://packages.elastic.co/GPG-KEY-elasticsearch | apt-key add -
    echo "deb https://packages.elastic.co/elasticsearch/2.x/debian stable main" \
        | tee -a /etc/apt/sources.list.d/elasticsearch-2.x.list
    add-apt-repository -y ppa:webupd8team/java
    apt-get update
    echo debconf shared/accepted-oracle-license-v1-1 select true \
        | debconf-set-selections
    echo debconf shared/accepted-oracle-license-v1-1 seen true \
        | debconf-set-selections
    apt-get install -y oracle-java8-installer
    java -version
    apt-get install -y elasticsearch
    update-rc.d elasticsearch defaults 95 10
    /etc/init.d/elasticsearch start
}
installElasticSearch

service php7.0-fpm restart
service nginx restart
