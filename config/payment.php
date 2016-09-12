<?php

return [

    'paypal' => [
        'mode'       => env('PAYPAL_MODE', 'sandbox'),
        'acct1'      => [
            'ClientId'     => env('PAYPAL_CLIENT_ID'),
            'ClientSecret' => env('PAYPAL_CLIENT_SECRET'),
        ],
        'base-url'   => env(
            'PAYPAL_BASE_URL',
            'https://www.sandbox.paypal.com/'
        ),
        'test-buyer' => [
            'email'    => env('PAYPAL_TEST_EMAIL'),
            'password' => env('PAYPAL_TEST_PASSWORD'),
        ],
    ],

    'stripe' => [
    ],

];
