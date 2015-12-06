<?php

Route::group(
    [
        'prefix'     => 'staff',
        'middleware' => [
            'auth',
            'staff'
        ]
    ],
    function () {
        Route::get('dashboard', [
            'uses' => 'Staff\DashboardController@getIndex',
            'as'   => 'staff.dashboard'
        ]);
        Route::resource('products', 'Staff\ProductController');
        Route::get('php-info', 'Staff\DashboardController@getPhpInfo');
    }
);

Route::controller('auth', 'Auth\AuthController', [
    'getLogin'  => 'auth.login',
    'postLogin' => 'auth.login.post',
    'getLogout' => 'auth.logout',
]);

Route::get('/', function () {
    return view('welcome');
});
