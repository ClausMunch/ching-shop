window.addEventListener("load", () => {
    let productId: number = <number>$("#product-options").data("product-id");

    let $optionLabels: JQuery = $(".product-option-label");
    $optionLabels.on("blur paste", updateOptionLabel);
    $optionLabels.keydown(
        (event: BaseJQueryEventObject) => {
            if (event.which === 13) {
                event.preventDefault();
                $(event.target).blur();
                return false;
            }

            return true;
        }
    );

    interface Document {
        getCsrfToken: () => {};
    }

    /**
     * @param {HTMLElement} label
     */
    function updateOptionLabel() {
        let label: HTMLElement = this;

        if (label.innerText === label.getAttribute("data-original")) {
            return;
        }

        let $label = $(label);

        $label.removeClass("bg-danger");
        $label.addClass("bg-info");
        let optionId = label.getAttribute("data-option-id");
        $.ajax(
            `/staff/products/${productId}/options/${optionId}/label`,
            {
                data: JSON.stringify({
                    label: label.innerText,
                    _token: document.getCsrfToken()
                }),
                method: "PUT",
                contentType: "application/json",
                dataType: "json",
                success: () => {
                    label.setAttribute("data-original", label.innerText);
                    $label.removeClass("bg-info");
                },
                error: () => {
                    label.innerText = label.getAttribute("data-original");
                    $label.removeClass("bg-info");
                    $label.addClass("bg-danger");
                }
            }
        );
    }
});
