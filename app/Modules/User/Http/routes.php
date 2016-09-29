<?php

Route::group(
    ['prefix' => 'user'],
    function () {
        Auth::routes();
    }
);
