/*jslint node: true */
'use strict';

var elixir = require('laravel-elixir');

elixir(function (mix) {
    mix.sass('ching-shop.scss');
});

elixir(function (mix) {
    mix.styles([
        'ching-shop.css'
    ], 'public/css/ching-shop.css', 'public/css');
});

elixir(function (mix) {
    mix.scripts([
        'bower_components/jquery/dist/jquery.js',
        'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js'
    ], 'public/js/ching-shop.js', 'resources/assets');
});

elixir(function (mix) {
    mix.version([
        'css/ching-shop.css',
        'js/ching-shop.js'
    ]);
});

elixir(function (mix) {
    mix.copy('resources/assets/img', 'public/img');
});

elixir(function (mix) {
    mix.copy(
        'resources/assets/bower_components/bootstrap-sass/assets/fonts',
        'public/build/fonts/'
    );
});
