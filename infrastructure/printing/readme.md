# Print jobs

The queue is called `ching-shop-print-jobs` in all environments.

## Local

To access the Beanstalkd queue for print jobs on the Vagrant box, we need to
create an SSH tunnel:

```bash
# Tunnel 11333 on host to 11300 on guest:
vagrant ssh -- -L 11333:localhost:11300
```

Then on the host we can interact with Beanstalkd from the host, e.g.:

```bash
echo -e "use ching-shop-print-jobs\r\nstats\r\n" | nc localhost 11333 | grep jobs
echo -e "use ching-shop-print-jobs\r\npeek-ready\r\n" | nc localhost 11333
telnet localhost 11333
```

The reason we can't just use Vagrant port forwarding is that Homestead has
Beanstalkd listening on localhost only, so it won't accept a remote connection
from the host machine. The SSH tunnel lets us access Beanstalkd as if we are
connecting to localhost.

To get an example job on the queue, this test can be used from within the
Vagrant box:

```bash
cd ~/ching-shop
phpunit --filter testStripeCheckout
```

To test the print queue functionality (this will queue and pop a message):

```bash
cd ~/ching-shop
phpunit --filter testOrderAddressPrintJob
```
