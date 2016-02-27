Ching Shop
==========

[![Build Status](https://travis-ci.org/hughgrigg/ching-shop.svg)](https://travis-ci.org/hughgrigg/ching-shop)
[![StyleCI](https://styleci.io/repos/44910529/shield)](https://styleci.io/repos/44910529)

Source code for [ching-shop.com](https://www.ching-shop.com).

## Up and running

You'll need [Vagrant](https://www.vagrantup.com/),
[VirtualBox](https://www.virtualbox.org/) and
[NFS](https://help.ubuntu.com/community/SettingUpNFSHowTo) installed.

```bash
git clone git@github.com:hughgrigg/ching-shop.git
cd ching-shop
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
gulp
phpunit
```

You should now be able to access the development site at
https://www.ching-shop.dev

You may want to trust the local certificate, for example with
[these instructions](https://stackoverflow.com/questions/7580508/getting-chrome-to-accept-self-signed-localhost-certificate/18602774#18602774)
for Chrome.
