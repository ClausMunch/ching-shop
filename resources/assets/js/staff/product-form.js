(function() {
    'use strict';

    var addImageButton;
    var imageSection;

    addImageButton = document.getElementById('add-image');
    imageSection = document.getElementById('images');
    if (!addImageButton || !imageSection) {
        return;
    }

    addImageButton.addEventListener('click', appendImageInputs);
    function appendImageInputs() {

    }

}());
