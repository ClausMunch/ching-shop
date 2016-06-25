window.addEventListener("load", function () {
    // @see resources/assets/ts/src/main.ts:11
    // noinspection TypeScriptUnresolvedFunction
    $("#tag-ids").multiselect({
        enableFiltering: true,
        checkboxName: "tag-ids[]"
    });
});

require("./images.js");
