#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

if ! grep -q 'EDITOR' ~/.bashrc; then
    echo "export EDITOR=vim" >> ~/.bashrc
fi

if ! grep -q 'PHP_IDE_CONFIG' ~/.bashrc; then
    echo "export PHP_IDE_CONFIG='serverName=www.ching-shop.dev'" >> ~/.bashrc
fi

if ! grep -q 'COMPOSER_DISABLE_XDEBUG_WARN' ~/.bashrc; then
    echo "export COMPOSER_DISABLE_XDEBUG_WARN=1" >> ~/.bashrc
fi

if ! grep -q 'PHANTOMJS_EXECUTABLE' ~/.bashrc; then
    echo "export PHANTOMJS_EXECUTABLE=$HOME/ching-shop/node_modules/phantomjs-prebuilt/bin/phantomjs" >> ~/.bashrc
fi

if ! grep -q '.composer/vendor/bin' ~/.bashrc; then
    export PATH=$PATH:$HOME/.composer/vendor/bin/
    echo 'export PATH=$PATH:$HOME/.composer/vendor/bin/' >> ~/.bashrc
fi

if ! grep -q 'xdebug_on' ~/.bashrc; then
    echo "alias xdebug_on=\"export XDEBUG_CONFIG='idekey=PHPSTORM'\"" >> ~/.bashrc
fi

source $HOME/.bashrc

git config --global core.excludesfile ~/.gitignore_global
echo '.idea' > ~/.gitignore_global

composer global require "squizlabs/php_codesniffer=^2.5" --quiet
composer global require "phpmd/phpmd=@stable" --quiet

function appSetup
{
    cd ~/ching-shop
    cp -n .env.example .env
    composer install --quiet --no-interaction
    bundler install
    php ~/ching-shop/artisan config:clear
    php artisan key:generate
    php artisan migrate --seed
    npm install --silent
    gulp --silent
    phpcs --config-set colors 1
    phpcs --config-set severity 1
}
appSetup

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
