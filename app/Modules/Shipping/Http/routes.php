<?php

Route::group(
    [
        'prefix' => 'shipping',
    ],
    function () {
        Route::group(
            [
                'prefix'     => 'staff',
                'middleware' => [
                    'auth',
                    'staff',
                    'web',
                ],
            ],
            function () {
                Route::resource('dispatches', 'Staff\DispatchController');
                Route::post(
                    'print-address',
                    [
                        'as'   => 'print-address',
                        'uses' => 'Staff\DispatchController@printAddress',
                    ]
                );
                Route::get(
                    'print-address',
                    [
                        'as'   => 'print-address-form',
                        'uses' => 'Staff\DispatchController@printAddressForm',
                    ]
                );
                Route::post(
                    'print-generic-address',
                    [
                        'as'   => 'print-generic-address',
                        'uses' => 'Staff\DispatchController@printGenericAddress',
                    ]
                );
            }
        );
    }
);
