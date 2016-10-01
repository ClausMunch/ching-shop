#!/usr/bin/env bash

phpcs -v --standard=./tests/analysis/phpcs.xml app
phpmd --strict app text ./tests/analysis/phpmd.xml
gulp scss-lint
gulp ts-lint
php artisan route:cache
php artisan config:clear
phpunit --testsuite unit --repeat 3
gulp test-database
phpunit --coverage-html build --coverage-clover build/clover.xml
