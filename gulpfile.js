/*jslint node: true */
"use strict";

//noinspection JSUnresolvedVariable
process.env.DISABLE_NOTIFIER = true;

var elixir = require("laravel-elixir");
var gulp   = require("gulp");
var shell  = require("gulp-shell");

elixir(function(mix) {
    mix.sass("staff.scss");
    mix.sass("customer.scss");
});

elixir(function(mix) {
    mix.styles([
        "staff.css"
    ], "public/css/staff.css", "public/css");
    mix.styles([
        "customer.css"
    ], "public/css/customer.css", "public/css");
});

elixir(function(mix) {
    mix.browserify("staff.js");
    mix.browserify("customer.js");
});

elixir(function(mix) {
    mix.version([
        "css/staff.css",
        "css/customer.css",
        "js/main.js",
        "js/staff.js",
        "js/customer.js"
    ]);
});

elixir(function(mix) {
    mix.copy("resources/assets/img", "public/img");
});

elixir(function(mix) {
    mix.copy(
        "./node_modules/bootstrap-sass/assets/fonts",
        "public/build/fonts/"
    );
});

elixir(function(mix) {
    mix.task("generate-test-db");
});

gulp.task("generate-test-db", shell.task(
    [
        "rm -f ./database/test_db.sqlite",
        "touch ./database/test_db.sqlite",
        "php artisan migrate:refresh --seed --database=testing --env=testing"
    ],
    {
        verbose: true
    }
));
