#!/usr/bin/env bash

if [ ! -d ~/.bash_it ]; then
    echo 'Installing bash-it'
    git clone --depth=1 https://github.com/Bash-it/bash-it.git ~/.bash_it
    ~/.bash_it/install.sh
    sed -i -e 's/bobby/nwinkler/' ~/.bashrc
fi

function appSetup
{
    cd ~/ching-shop
    cp -n .env.example .env
    php ~/ching-shop/artisan config:clear
    php artisan key:generate
    php artisan migrate --seed
    npm install
    bower install
    gulp
}
appSetup
