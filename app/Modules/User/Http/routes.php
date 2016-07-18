<?php

Route::group(
    ['prefix' => 'user'],
    function () {
        Route::group(
            [
                'prefix'    => 'auth',
                'as'        => 'auth::',
                'namespace' => 'Auth',
            ],
            function () {
                Route::get(
                    'login',
                    [
                        'as'   => 'login',
                        'uses' => 'AuthController@getLogin',
                    ]
                );
                Route::post(
                    'login',
                    [
                        'as'   => 'login.post',
                        'uses' => 'AuthController@postLogin',
                    ]
                );
                Route::get(
                    'logout',
                    [
                        'as'   => 'logout',
                        'uses' => 'AuthController@getLogout',
                    ]
                );
            }
        );
    }
);
