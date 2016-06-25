require("./main.js");

require("../../../node_modules/jquery-ui/sortable.js");
require("../../../node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js");

require("./staff/product.js");

interface Document {
    getCsrfToken(): string;
}

let csrfToken: string = "";
document.getCsrfToken = function getCsrfToken(): string {
    if (!csrfToken.length) {
        let docToken: string = document
            .querySelector("[name=csrf-token]")
            .getAttribute("content");

        if (docToken.length) {
            csrfToken = docToken;
        } else {
            throw new Error("Failed to get CSRF token from document.");
        }
    }

    return csrfToken;
};
