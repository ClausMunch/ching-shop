(function() {
    'use strict';

    var addImageButton;
    var imageSection;

    window.console.log('product form!');

    addImageButton = document.getElementById('add-image');
    imageSection = document.getElementById('images');
    if (!addImageButton || !imageSection) {
        return;
    }

    addImageButton.addEventListener('click', appendImageInputs);
    function appendImageInputs() {

    }

}());
