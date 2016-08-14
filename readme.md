Ching Shop
==========

[![Build Status](https://travis-ci.org/hughgrigg/ching-shop.svg?branch=master)](https://travis-ci.org/hughgrigg/ching-shop)
[![StyleCI](https://styleci.io/repos/44910529/shield)](https://styleci.io/repos/44910529)
[![Coverage Status](https://coveralls.io/repos/github/hughgrigg/ching-shop/badge.svg?branch=master)](https://coveralls.io/github/hughgrigg/ching-shop?branch=master)
[![Dependency Status](https://dependencyci.com/github/hughgrigg/ching-shop/badge)](https://dependencyci.com/github/hughgrigg/ching-shop)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/e8ff26290e6b48a8995cb6600988cf4b)](https://www.codacy.com/app/hugh_2/ching-shop)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/39464be8-2963-48b8-ad29-a1dc584b68f8/mini.png)](https://insight.sensiolabs.com/projects/39464be8-2963-48b8-ad29-a1dc584b68f8)

Source code for [ching-shop.com](https://www.ching-shop.com).

## Up and running

You'll need [Vagrant](https://www.vagrantup.com/),
[VirtualBox](https://www.virtualbox.org/) and
[NFS](https://help.ubuntu.com/community/SettingUpNFSHowTo) installed.

```bash
git clone git@github.com:hughgrigg/ching-shop.git
cd ching-shop
git clone git@github.com:laravel/homestead.git ./vendor/laravel/homestead
cp Homestead.yaml.example Homestead.yaml
```

Change the `map` key in `Homestead.yaml` to where you have cloned the ching-shop repo.

Add this line to your hosts file (e.g. `/etc/hosts`):

```
192.168.10.10   www.ching-shop.dev
```

Then set up the Vagrant box, ssh into it and check everything is set up:

```bash
vagrant up
vagrant ssh
cd ~/sites/ching-shop
php artisan test
```

You should now be able to access the development site at
https://www.ching-shop.dev

You may want to trust the local certificate, for example with
[these instructions](https://stackoverflow.com/questions/7580508/getting-chrome-to-accept-self-signed-localhost-certificate/18602774#18602774)
for Chrome.

## Running tests

```bash
phpunit --testsuite unit
phpunit --testsuite functional
phpunit --coverage-html build
```

## Static analysis

```bash
phpcs --standard=./tests/analysis/phpcs.xml app
phpmd --strict app text ./tests/analysis/phpmd.xml
```

All tests and static analyses can be run with:

```bash
php artisan test
```

## Deployment
 
```bash
pip install -r requirements.txt
ansible-playbook ./ansible/web.yml
```
