<?php

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
        )->middleware(['customer', 'suggestions']);
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
                    'stripe/payment',
                    [
                        'as'   => 'sales.customer.stripe.pay',
                        'uses' => 'Customer\StripeController@payAction',
                    ]
                );
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
                Route::get(
                    '{orderId}',
                    [
                        'as'   => 'sales.customer.order.view',
                        'uses' => 'Customer\OrderController@viewAction',
                    ]
                )->middleware(['customer', 'suggestions']);
            }
        );

        Route::group(
            [
                'prefix'     => 'staff',
                'middleware' => [
                    'auth',
                    'staff',
                ],
            ],
            function () {
                Route::resource('orders', 'Staff\OrderController');
            }
        );
    }
);
