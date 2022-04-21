import hljs from 'highlight.js';

const SELECTOR = '.hljs';

function exec() {
    highlight();
    customStyle();
}
export default { exec };

function highlight() {
    hljs.highlightAll(); console.log('highlight.js loaded!');
}

function customStyle() {
    let elements = document.querySelectorAll(SELECTOR);
    elements.forEach((element) => {
        // ...
    });
}