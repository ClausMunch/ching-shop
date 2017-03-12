#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

phpcs -v --standard=./tests/analysis/phpcs.xml app
phpmd --strict app text ./tests/analysis/phpmd.xml
gulp scss-lint
gulp ts-lint
php artisan route:cache
php artisan config:clear
gulp test-database
phpunit -c phpunit.xml --coverage-html build --coverage-clover build/clover.xml
