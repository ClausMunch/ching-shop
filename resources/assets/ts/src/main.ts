require("./element-switch.js");

window.jQuery = window.$ = require(
    "../../../node_modules/jquery/dist/jquery.js")
;

window.Bootstrap = require(
    "../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.js"
);

interface JQuery {
    multiselect(options: Object): JQuery;
}

interface Window {
    jQuery: JQueryStatic;
    $: JQueryStatic;
    Bootstrap: any;
}
