for (let el of [].slice.call(document.querySelectorAll(".no-js"))) {
    el.className += " hidden";
}
for (let el of [].slice.call(document.querySelectorAll(".js-only"))) {
    el.className = el.className.replace(/hidden/, "");
}
