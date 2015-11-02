<?php

Route::group(
    [
        'as'         => 'staff::',
        'prefix'     => 'staff',
        'middleware' => [
            'auth',
        ]
    ],
    function () {
        Route::get('dashboard', ['as' => 'dashboard', function () {
            return 'staff dashboard';
        }]);
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
