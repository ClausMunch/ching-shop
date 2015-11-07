<?php

Route::group(
    [
        'prefix'     => 'staff',
        'middleware' => [
            'auth',
        ]
    ],
    function () {
        Route::controller('dashboard', 'Staff\DashboardController', [
            'getIndex' => 'staff.dashboard'
        ]);
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
