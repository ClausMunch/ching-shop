/**
 * Handle form submissions.
 */
(function initForms() {
    let forms: NodeListOf<Element> = document.querySelectorAll("form");
    for (let form of forms) {
        form.addEventListener("submit", function () {
            handleFormSubmit(this);
            // dispatchEvent(event);
            return true;
        })
    }
})();

/**
 * Handle clicks on button-style links.
 */
(function initButtonLinks() {
    let buttonLinks: NodeListOf<Element> = document.querySelectorAll("a.btn");
    for (let buttonLink of buttonLinks) {
        buttonLink.addEventListener("click", function () {
            disableButton(this);
        });
    }
})();

/**
 * Disable and re-style the submit button of a form.
 * @param {HTMLFormElement} form
 */
function handleFormSubmit(form: HTMLFormElement) {
    disableButton(<HTMLButtonElement>form.querySelector("button[type=submit]"));
}

/**
 * @param {HTMLButtonElement} button
 */
function disableButton(button:HTMLButtonElement) {
    button.disabled = true;
    button.setAttribute(
        "style",
        `min-width: ${button.offsetWidth}px;` + button.getAttribute("style")
    );
    button.innerHTML = "<span class='spin icon icon-spinner2'></span>";
}
