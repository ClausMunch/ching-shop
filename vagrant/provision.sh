#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

if [ ! -d ~/.bash_it ]; then
    echo 'Installing bash-it'
    git clone --depth=1 https://github.com/Bash-it/bash-it.git ~/.bash_it
    ~/.bash_it/install.sh
    sed -i -e 's/bobby/nwinkler/' ~/.bashrc
fi

if ! grep -q 'gulp --completion' ~/.bashrc; then
    echo $'\n# Gulp tab completion' >> ~/.bashrc
    echo 'eval "$(gulp --completion=bash)"' >> ~/.bashrc
fi

if ! grep -q 'EDITOR' ~/.bashrc; then
    echo "export EDITOR=vim" >> ~/.bashrc
fi

if ! grep -q 'PHP_IDE_CONFIG' ~/.bashrc; then
    echo "export PHP_IDE_CONFIG='serverName=www.ching-shop.dev'" >> ~/.bashrc
fi

if ! grep -q 'COMPOSER_DISABLE_XDEBUG_WARN' ~/.bashrc; then
    echo "export COMPOSER_DISABLE_XDEBUG_WARN=1" >> ~/.bashrc
fi

function appSetup
{
    cd ~/ching-shop
    cp -n .env.example .env
    composer install
    php ~/ching-shop/artisan config:clear
    php artisan key:generate
    php artisan migrate --seed
    npm install
    gulp
    phpcs --config-set colors 1
    phpcs --config-set severity 1
}
appSetup
