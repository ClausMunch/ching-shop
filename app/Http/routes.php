<?php

Route::group(
    ['middleware' => ['web']],
    function () {

        Route::group(
            [
                'namespace'  => 'Customer',
                'middleware' => 'customer',
            ],
            function () {
                Route::group(
                    [
                        'prefix'     => 'product',
                        'as'         => 'product::',
                    ],
                    function () {
                        Route::get(
                            '{id}/{slug}',
                            [
                                'as'   => 'view',
                                'uses' => 'ProductController@viewAction',
                            ]
                        );
                    }
                );
                Route::group(
                    [
                        'prefix'     => 'tag',
                        'as'         => 'tag::',
                    ],
                    function () {
                        Route::get(
                            '{id}/{name}',
                            [
                                'as'   => 'view',
                                'uses' => 'TagController@viewAction',
                            ]
                        );
                    }
                );
            }
        );

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

        Route::group(
            [
                'prefix'     => 'staff',
                'middleware' => [
                    'auth',
                    'staff',
                ],
            ],
            function () {
                Route::get(
                    'dashboard',
                    [
                        'uses' => 'Staff\DashboardController@getIndex',
                        'as'   => 'staff.dashboard',
                    ]
                );
                Route::get(
                    'products/images',
                    [
                        'uses' => 'Staff\ImageController@index',
                        'as'   => 'staff.products.images.index',
                    ]
                );
                Route::post(
                    'products/images/transfer-local',
                    [
                        'uses' => 'Staff\ImageController@transferLocalImages',
                        'as'   => 'staff.products.images.transfer-local',
                    ]
                );
                Route::delete(
                    'products/images/{id}',
                    [
                        'uses' => 'Staff\ImageController@destroy',
                        'as'   => 'staff.products.images.destroy',
                    ]
                );
                Route::resource('products', 'Staff\ProductController');
                Route::delete(
                    'product/{productId}/image/{imageId}',
                    [
                        'uses' => 'Staff\ProductController@detachImage',
                        'as'   => 'staff.products.detach-image',
                    ]
                );
                Route::post(
                    'products/{sku}/price',
                    [
                        'uses' => 'Staff\PriceController@setProductPrice',
                        'as'   => 'staff.products.price',
                    ]
                );
                Route::post(
                    'products/{id}/post-option',
                    [
                        'uses' => 'Staff\ProductOptionController@postNew',
                        'as'   => 'staff.products.post-option',
                    ]
                );
                Route::put(
                    'products/{productId}/options/{optionId}/label',
                    [
                        'uses' => 'Staff\ProductOptionController@putLabel',
                        'as'   => 'staff.products.options.put-label',
                    ]
                );
                Route::put(
                    'products/options/{optionId}/colour',
                    [
                        'uses' => 'Staff\ProductOptionController@putColour',
                        'as'   => 'staff.products.options.put-colour',
                    ]
                );
                Route::post(
                    'products/{sku}/images',
                    [
                        'uses' => 'Staff\ProductController@postProductImages',
                        'as'   => 'staff.products.post-images',
                    ]
                );
                Route::get('php-info', 'Staff\DashboardController@getPhpInfo');
                Route::put(
                    'products/{sku}/image-order',
                    [
                        'uses' => 'Staff\ProductController@putImageOrder',
                        'as'   => 'staff.products.image-order',
                    ]
                );
                Route::put(
                    'products/options/{id}/image-order',
                    [
                        'uses' => 'Staff\ProductOptionController@putImageOrder',
                        'as'   => 'staff.products.options.image-order',
                    ]
                );
                Route::delete(
                    'products/options/{optionId}/image/{imageId}',
                    [
                        'uses' => 'Staff\ProductOptionController@detachImage',
                        'as'   => 'staff.products.options.detach-image',
                    ]
                );
                Route::resource('tags', 'Staff\TagController');
                Route::put(
                    'products/{sku}/tags',
                    [
                        'uses' => 'Staff\TagController@putProductTags',
                        'as'   => 'staff.products.put-tags',
                    ]
                );
            }
        );

        Route::group(
            [
                'namespace'  => 'Customer',
                'middleware' => 'customer',
            ],
            function () {
                Route::get(
                    'cards',
                    [
                        'as'   => 'customer.cards',
                        'uses' => 'CategoriesController@viewAction',
                    ]
                );
                Route::get(
                    '{path}',
                    [
                        'uses' => 'StaticController@pageAction',
                        'as'   => 'customer.static',
                    ]
                );
            }
        );

        Route::get(
            '/',
            [
                'uses'       => 'Customer\RootController@getIndex',
                'middleware' => 'customer',
            ]
        );

        Route::get('home', 'Customer\RootController@getIndex');

    }
);
