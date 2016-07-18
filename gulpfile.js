/*jslint node: true */
"use strict";

//noinspection JSUnresolvedVariable
process.env.DISABLE_NOTIFIER = true;

var elixir      = require("laravel-elixir");
var gulp        = require("gulp");
var clean       = require('gulp-clean');
var shell       = require("gulp-shell");
var typings     = require("gulp-typings");
var ts          = require("gulp-typescript");
var Task        = elixir.Task;

elixir.config.css.autoprefix = {
    enabled: true,
    options: {
        cascade: true,
        browsers: ['last 3 versions', '> 1%']
    }
};

elixir.extend("typings", function () {
    new Task("typings", function () {
        return gulp.src("./resources/assets/ts/typings.json").pipe(typings());
    });
});

elixir.extend("typescript", function () {
    new Task("typescript", function () {
        return gulp.src("./resources/assets/ts/src/**/*.ts")
            .pipe(ts(ts.createProject("./resources/assets/ts/tsconfig.json")))
            .pipe(gulp.dest("./resources/assets/js/"));
    });
});

elixir(function (mix) {
    mix.task("test-database");
    mix.task("clean")
        .sass("staff.scss")
        .sass("customer.scss")
        .styles(
            ["staff.css"],
            "public/css/staff.css",
            "public/css"
        )
        .styles(
            ["customer.css"],
            "public/css/customer.css",
            "public/css"
        )
        .typings()
        .typescript()
        .browserify("staff.js")
        .browserify("staff/product-options.js")
        .browserify("customer.js")
        .version([
            "css/staff.css",
            "css/customer.css",
            "js/staff.js",
            "js/product-options.js",
            "js/customer.js"
        ])
        .copy("resources/assets/img", "public/img")
        .copy(
            "./node_modules/bootstrap-sass/assets/fonts",
            "public/build/fonts/"
        );
});

gulp.task("clean", function () {
    return gulp.src(
        [
            "./resources/assets/js/*",
            "./public/build/*",
            "./public/css/*",
            "./public/js/*"
        ],
        {read: false}
    ).pipe(clean());
});

gulp.task("test-database", shell.task(
    [
        "rm -f ./database/test_db.sqlite",
        "touch ./database/test_db.sqlite",
        "php artisan migrate:refresh --seed --database=testing --env=testing"
    ],
    {
        verbose: true
    }
));
