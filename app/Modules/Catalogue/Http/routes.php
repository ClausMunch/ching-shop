<?php

Route::get(
    'christmas-cards',
    [
        'uses' => 'LandingController@christmasCardsAction',
        'as'   => 'christmas-cards',
    ]
)->middleware(['customer']);

Route::group(
    ['prefix' => 'catalogue'],
    function () {
        Route::get(
            'search',
            [
                'uses' => 'SearchController@searchAction',
                'as'   => 'catalogue.search',
            ]
        )->middleware(['customer']);
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
                    'products/images',
                    [
                        'uses' => 'Staff\ImageController@index',
                        'as'   => 'catalogue.staff.products.images.index',
                    ]
                );
                Route::post(
                    'products/images/transfer-local',
                    [
                        'uses' => 'Staff\ImageController@transferLocalImages',
                        'as'   => 'catalogue.staff.products.images.transfer-local',
                    ]
                );
                Route::delete(
                    'products/images/{id}',
                    [
                        'uses' => 'Staff\ImageController@destroy',
                        'as'   => 'catalogue.staff.products.images.destroy',
                    ]
                );
                Route::resource('products', 'Staff\ProductController');
                Route::delete(
                    'product/{productId}/image/{imageId}',
                    [
                        'uses' => 'Staff\ProductController@detachImage',
                        'as'   => 'products.detach-image',
                    ]
                );
                Route::post(
                    'products/{sku}/price',
                    [
                        'uses' => 'Staff\PriceController@setProductPrice',
                        'as'   => 'products.price',
                    ]
                );
                Route::post(
                    'products/{id}/post-option',
                    [
                        'uses' => 'Staff\ProductOptionController@postNew',
                        'as'   => 'catalogue.staff.products.post-option',
                    ]
                );
                Route::put(
                    'products/{productId}/options/{optionId}/label',
                    [
                        'uses' => 'Staff\ProductOptionController@putLabel',
                        'as'   => 'catalogue.staff.products.options.put-label',
                    ]
                );
                Route::put(
                    'products/options/{optionId}/colour',
                    [
                        'uses' => 'Staff\ProductOptionController@putColour',
                        'as'   => 'catalogue.staff.products.options.put-colour',
                    ]
                );
                Route::post(
                    'products/{sku}/images',
                    [
                        'uses' => 'Staff\ProductController@postProductImages',
                        'as'   => 'catalogue.staff.products.post-images',
                    ]
                );
                Route::put(
                    'products/{sku}/image-order',
                    [
                        'uses' => 'Staff\ProductController@putImageOrder',
                        'as'   => 'products.image-order',
                    ]
                );
                Route::put(
                    'products/options/{id}/image-order',
                    [
                        'uses' => 'Staff\ProductOptionController@putImageOrder',
                        'as'   => 'catalogue.staff.products.options.image-order',
                    ]
                );
                Route::put(
                    'products/options/{id}/stock',
                    [
                        'uses' => 'Staff\StockController@putStock',
                        'as'   => 'catalogue.staff.products.options.stock',
                    ]
                );
                Route::delete(
                    'products/options/{optionId}/image/{imageId}',
                    [
                        'uses' => 'Staff\ProductOptionController@detachImage',
                        'as'   => 'catalogue.staff.products.options.detach-image',
                    ]
                );
                Route::resource('tags', 'Staff\TagController');
                Route::put(
                    'products/{sku}/tags',
                    [
                        'uses' => 'Staff\TagController@putProductTags',
                        'as'   => 'catalogue.staff.products.put-tags',
                    ]
                );
            }
        );
    }
);
