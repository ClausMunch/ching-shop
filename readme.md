Ching Shop
==========

Source code for [ching-shop.com](https://www.ching-shop.com).

## Up and running

You'll need [Vagrant](https://www.vagrantup.com/),
[VirtualBox](https://www.virtualbox.org/) and
[NFS](https://help.ubuntu.com/community/SettingUpNFSHowTo) installed.

```bash
git clone git@github.com:hughgrigg/ching-shop.git
cd ching-shop
vagrant up
```

Add this line to your hosts file (e.g. `/etc/hosts`):

```
192.168.10.10   www.ching-shop.dev
```

You should now be able to access the development site at
http://www.ching-shop.dev, and to ssh into the box with `vagrant ssh`.
