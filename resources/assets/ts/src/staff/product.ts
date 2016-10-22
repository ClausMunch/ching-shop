window.addEventListener("load", function () {
    // @see resources/assets/ts/src/main.ts:11
    // noinspection TypeScriptUnresolvedFunction
    $("#tag-ids").multiselect({
        enableFiltering: true,
        checkboxName: "tag-ids[]"
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

    $(".form-control.slug").change(function () {
        this.value = toSlug(this.value);
    });

    function toSlug(text: string) {
        return text.toString().toLowerCase()
            .replace(/(\s+|--+|[^a-z0-9])/g, "-")
            .replace(/([^\w\-]+|^-+|-+$|&|\bwith\b|\band\b)/g, "");
    }

    $("#use-name").click(function () {
        $("#slug").val($("#name").val()).change();
    });
});

require("./images.js");
require("./product-options.js");
