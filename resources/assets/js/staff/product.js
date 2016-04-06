$(function() {
    "use strict";

    var $sortable = $(".product-images-sortable");
    $sortable.sortable({
        stop: changeImageOrder
    });
    $sortable.disableSelection();

    /**
     * @param {jQuery.Event} event
     * @param {HTMLElement} event.target
     */
    function changeImageOrder(event) {
        var productId = event.target.getAttribute('data-product-id');
        var token = event.target.getAttribute('data-token');
        if (!productId || !token) {
            return;
        }
        var imageOrder = {};
        [].slice.call(event.target.childNodes).forEach(function(node, pos) {
            imageOrder[node.getAttribute('data-image-id')] = pos;
        });
        if (!imageOrder) {
            return;
        }
        jQuery.ajax(
            '/staff/products/' + productId + '/image-order',
            {
                data: JSON.stringify({
                    imageOrder: imageOrder,
                    _token: token
                }),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json'
            }
        );
    }
});
