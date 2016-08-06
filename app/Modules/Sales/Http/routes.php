<?php

Route::group(
    [
        'prefix' => 'sales',
    ],
    function () {
        Route::group(
            [],
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
            }
        );
    }
);
