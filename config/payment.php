<?php

return [

    'paypal' => [
        'acct1.ClientId'     => env('PAYPAL_CLIENT_ID'),
        'acct1.ClientSecret' => env('PAYPAL_CLIENT_SECRET'),
        'mode'               => env('PAYPAL_MODE', 'sandbox'),
        'base-url'           => env(
            'PAYPAL_BASE_URL',
            'https://www.sandbox.paypal.com/'
        )
    ],

    'stripe' => [
    ],

];
