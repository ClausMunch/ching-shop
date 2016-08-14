/// <reference path="../typings/browser/index.d.ts" />

require("./main.js");
require("./modules/button");

let mainImage: HTMLImageElement = <HTMLImageElement>document.getElementById(
    "product-main-image"
);

initThumbnails(document.getElementsByClassName("product-thumbnail"));

let productOptionChoice = <HTMLOptionElement>document.getElementById(
    "product-option-choice"
);
if (productOptionChoice) {
    productOptionChoice.addEventListener("change", selectOption);
}

/**
 * @param {NodeList} thumbnails
 */
function initThumbnails(thumbnails) {
    for (let thumbnail of thumbnails) {
        initThumbnail(thumbnail);
    }
}

/**
 * @param {HTMLElement} thumbnail
 */
function initThumbnail(thumbnail) {
    thumbnail.removeAttribute("href");
    thumbnail.addEventListener("click", viewThumbnail);
}

/**
 * Handle a click on a product thumbnail.
 */
function viewThumbnail() {
    // Focus this image in the main image view.
    focusThumbnail(this);

    // Select this in the drop-down if available.
    let optionId = this.getAttribute("data-option-id");
    if (!optionId) {
        return;
    }
    let optionSelect: HTMLSelectElement;
    optionSelect = <HTMLSelectElement>document.getElementById(
        "product-option-choice"
    );
    for (let option: HTMLOptionElement, i = 0;
         option = <HTMLOptionElement>optionSelect.options[i];
         i++
    ) {
        if (option.value === optionId) {
            optionSelect.selectedIndex = i;
            break;
        }
    }
}

/**
 * @param {HTMLAnchorElement} thumbnail
 */
function focusThumbnail(thumbnail: HTMLAnchorElement) {
    let thisImage:HTMLImageElement;
    let thumbnails: NodeListOf<Element> = document.querySelectorAll(
        ".product-thumbnail"
    );
    for (let otherThumbnail of thumbnails) {
        otherThumbnail.removeAttribute("data-selected");
    }
    thumbnail.setAttribute("data-selected", "true");
    thisImage = <HTMLImageElement>thumbnail.querySelector(".img-thumbnail");
    mainImage.src = thisImage.src;
    mainImage.alt = thisImage.alt;
    if (thisImage.srcset) {
        mainImage.srcset = thisImage.srcset;
    }
}

/**
 * Handle selection of a product option from the drop-down.
 */
function selectOption() {
    // Focus the first relevant product option image.
    let thumbnails: NodeListOf<Element> = document.querySelectorAll(
        ".product-thumbnail"
    );
    for (let thumbnail of thumbnails) {
        if (thumbnail.getAttribute("data-option-id") === this.value) {
            focusThumbnail(thumbnail);
            break;
        }
    }
}

