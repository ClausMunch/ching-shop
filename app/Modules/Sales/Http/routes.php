<?php

use ChingShop\Modules\Sales\Domain\Order;

Route::group(
    [
        'prefix' => 'shopping',
    ],
    function () {
        Route::post(
            'add-to-basket',
            [
                'as'   => 'sales.customer.add-to-basket',
                'uses' => 'Customer\BasketController@addProductOptionAction',
            ]
        );
        Route::get(
            'basket',
            [
                'as'   => 'sales.customer.basket',
                'uses' => 'Customer\BasketController@viewBasketAction',
            ]
        )->middleware('customer');
        Route::post(
            'remove-from-basket',
            [
                'as'   => 'sales.customer.remove-from-basket',
                'uses' => 'Customer\BasketController@removeBasketItemAction',
            ]
        );

        Route::group(
            [
                'prefix' => 'checkout',
            ],
            function () {
                Route::get(
                    'address',
                    [
                        'as'   => 'sales.customer.checkout.address',
                        'uses' => 'Customer\CheckoutController@addressAction',
                    ]
                )->middleware(['customer', 'checkout']);
                Route::post(
                    'save-address',
                    [
                        'as'   => 'sales.customer.checkout.save-address',
                        'uses' => 'Customer\CheckoutController@saveAddressAction',
                    ]
                );
                Route::get(
                    'payment-method',
                    [
                        'as'   => 'sales.customer.checkout.choose-payment',
                        'uses' => 'Customer\CheckoutController@choosePaymentAction',
                    ]
                )->middleware(['customer', 'checkout']);
                Route::post(
                    'paypal/express-checkout',
                    [
                        'as'   => 'sales.customer.paypal.start',
                        'uses' => 'Customer\PayPalController@startAction',
                    ]
                );
                Route::get(
                    'paypal/return',
                    [
                        'as'   => 'sales.customer.paypal.return',
                        'uses' => 'Customer\PayPalController@returnAction',
                    ]
                );
                Route::get(
                    'paypal/cancel',
                    [
                        'as'   => 'sales.customer.paypal.cancel',
                        'uses' => 'Customer\PayPalController@cancelAction',
                    ]
                );
            }
        );

        Route::group(
            [
                'prefix' => 'orders',
            ],
            function () {
                Route::fakeIdModel('order', Order::class);
                Route::get(
                    '{order}',
                    [
                        'as'   => 'sales.customer.order.view',
                        'uses' => 'Customer\OrderController@viewAction',
                    ]
                )->middleware('customer');
            }
        );
    }
);
