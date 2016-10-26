import underscoreString = require("underscore.string");
window.addEventListener("load", function () {
    // @see resources/assets/ts/src/main.ts:11
    // noinspection TypeScriptUnresolvedFunction
    $("#tag-ids").multiselect({
        enableFiltering: true,
        checkboxName: "tag-ids[]"
    });
    $("#category-id").multiselect({
        enableFiltering: true,
        checkboxName: "category-id"
    });

    $(".form-control.counted").change(countedChange).keyup(countedChange);

    function countedChange() {
        let input: JQuery = $(this);
        let counter: JQuery = $(`.counter[for=${input.attr("name")}]`);
        counter.text(input.val().length);
        if (Math.abs(input.data("ideal-length") - input.val().length) < 15) {
            input.addClass("bg-success");
            counter.addClass("text-success");
        } else {
            counter.removeClass("text-success");
        }
    }

    let slugify = require("underscore.string/slugify");
    $(".form-control.slug").change(function () {
        this.value = slugify(this.value.replace(/(with|and|in|on)/, ""));
    });

    $("#use-name").click(function () {
        $("#slug").val($("#name").val()).change();
    });
});

require("./images.js");
require("./product-options.js");
