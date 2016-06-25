let $sortable = $(".product-images-sortable");
$sortable.sortable({
    start:       startSort,
    stop:        changeImageOrder,
    receive:     changeImageOrder,
    connectWith: ".connect-sortable",
});

function startSort() {
    $sortable.addClass("open");
}

/**
 * @param {JQueryEventObject} event
 */
function changeImageOrder(event: JQueryEventObject) {
    $sortable.removeClass("open");
    let sortAction: string = event.target.getAttribute("data-sort-action");
    if (!sortAction) {
        return;
    }
    let imageOrder: Object = {};
    [].slice.call(event.target.childNodes).forEach(
        function(node: HTMLElement, pos: number) {
            imageOrder[node.getAttribute("data-image-id")] = pos;
        }
    );
    if (!imageOrder) {
        return;
    }
    // @see
    // noinspection TypeScriptUnresolvedFunction
    jQuery.ajax(
        sortAction,
        {
            data: JSON.stringify({
                imageOrder: imageOrder,
                _token: document.getCsrfToken()
            }),
            method: "PUT",
            contentType: "application/json",
            dataType: "json"
        }
    );
}
