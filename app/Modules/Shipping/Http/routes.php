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
            }
        );
    }
);
