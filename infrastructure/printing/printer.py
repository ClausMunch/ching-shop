from pystalkd.Beanstalkd import Connection
import json
from cairosvg import svg2png
import os
import time
import io
import boto3
import argparse


class BeanstalkQueue:
    connection = None

    def __init__(self, host='localhost', port=11333):
        self.host = host
        self.port = port

    @property
    def beanstalk(self):
        if self.connection is None:
            self.connection = Connection(host=self.host, port=self.port)
            self.connection.use('ching-shop-print-jobs')
            self.connection.watch('ching-shop-print-jobs')

        return self.connection

    def pop(self):
        return self.beanstalk.reserve(timeout=0)


class SqsQueue:
    sqs_queue = None
    buffer = []

    def __init__(self, queue_name='ching-shop-print-jobs'):
        self.queue_name = queue_name

    @property
    def sqs(self):
        if self.sqs_queue is None:
            sqs = boto3.resource('sqs')
            self.sqs_queue = sqs.get_queue_by_name(QueueName=self.queue_name)
        return self.sqs_queue

    def pop(self):
        while not self.buffer:
            self.buffer = self.sqs.receive_messages()
        return self.buffer.pop(0)


class PrintWorker:
    queue = None
    printer = None

    def __init__(self, source_queue, printer):
        self.queue = source_queue
        self.printer = printer

    def run(self):
        while True:
            self.print(job=self.queue.pop())

    def print(self, job):
        if not job:
            return
        decoded = json.loads(job.body)
        self.printer.print(order_id=x_str(decoded.get('order_id')), address=decoded.get('address'))
        job.delete()


class Printer:

    @staticmethod
    def print(order_id, address):
        print('Preparing address for order #{}'.format(order_id))
        print(
            '\n'.join(filter(
                None,
                [
                    x_str(address.get('name')),
                    x_str(address.get('line_one')),
                    x_str(address.get('line_two')),
                    x_str(address.get('city')),
                    x_str(address.get('post_code')),
                    x_str(address.get('country_code')),
                ]
            ))
        )
        print('Printing address for order #{}'.format(order_id))
        label_path = AddressLabel(address).label_path()
        print('Waiting for printer at /dev/usb/lp0 ...')
        while not os.path.exists('/dev/usb/lp0'):
            time.sleep(1)
        os.system('brother_ql_print {} /dev/usb/lp0'.format(label_path))
        print('\n')


class AddressLabel:
    address = None

    def __init__(self, address):
        self.address = address

    def __str__(self):
        template_path = os.path.join(
            os.path.dirname(__file__),
            'address-label-template.svg'
        )
        with io.open(template_path, 'r', encoding='utf-8') as f:
            template = f.read()
            template = template.replace('##NAME##',
                                        x_str(self.address.get('name')))
            template = template.replace('##LINE_ONE##',
                                        x_str(self.address.get('line_one')))
            template = template.replace('##LINE_TWO##',
                                        x_str(self.address.get('line_two')))
            template = template.replace('##CITY##',
                                        x_str(self.address.get('city')))
            template = template.replace('##POST_CODE##',
                                        x_str(self.address.get('post_code')))
            template = template.replace('##COUNTRY_CODE##',
                                        x_str(self.address.get('country_code')))
            f.close()
        return str(template)

    def image_path(self):
        png_out_path = '/tmp/cs-address-{}.png'.format(x_str(self.address.get('id')))
        with open(png_out_path, 'wb') as png_out:
            print('Writing address image to {}'.format(png_out_path))
            svg2png(bytestring=bytes(str(self), 'UTF-8'), write_to=png_out)
            png_out.close()
        return png_out_path

    def label_path(self):
        raster_out_path = '/tmp/cs-address-{}.bin'.format(x_str(self.address.get('id')))
        print('Writing address label to {}'.format(raster_out_path))
        os.system(
            'brother_ql_create --model QL-570 {} > {}'.format(
                self.image_path(), raster_out_path
            )
        )
        return raster_out_path


def x_str(s):
    return '' if s is None else str(s)

parser = argparse.ArgumentParser(description='Print address labels from a queue.')
parser.add_argument('--source', '-s', choices=('beanstalkd', 'sqs'),
                    default='beanstalkd', help='Source queue to use.')

args = parser.parse_args()
if args.source == 'beanstalkd':
    queue = BeanstalkQueue()
elif args.source == 'sqs':
    queue = SqsQueue()
else:
    raise ValueError('Unknown source queue {}'.format(args.source))

worker = PrintWorker(source_queue=queue, printer=Printer())
print('Listening on {}'.format(args.source))
worker.run()
