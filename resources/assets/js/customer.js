require("./main.js");

(function() {

    var mainImage = document.getElementById('product-main-image');

    initThumbnails(document.getElementsByClassName('product-thumbnail'));

    /**
     * @param {NodeList} thumbnails
     */
    function initThumbnails(thumbnails) {
        forEach(thumbnails, initThumbnail);
    }

    /**
     * @param {HTMLElement} thumbnail
     */
    function initThumbnail(thumbnail) {
        thumbnail.removeAttribute('href');
        thumbnail.addEventListener('click', viewThumbnail);
    }

    /**
     * Handle a click on a product thumbnail
     */
    function viewThumbnail() {
        var thisImage;
        forEach(
            document.querySelectorAll('.product-thumbnail'),
            function(otherThumbnail) {
                otherThumbnail.removeAttribute('data-selected');
            }
        );
        this.setAttribute('data-selected', 'true');
        thisImage = this.querySelector('.img-thumbnail');
        mainImage.src = thisImage.src;
        mainImage.alt = thisImage.alt;
        if (thisImage.srcset) {
            mainImage.srcset = thisImage.srcset;
        }
    }

    /**
     *
     * @param {array|NodeList} elements
     * @param {function} func
     */
    function forEach(elements, func) {
        if (!elements.length) {
            return;
        }
        Array.prototype.forEach.call(elements, func);
    }

}());
