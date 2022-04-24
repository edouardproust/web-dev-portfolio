import hljs from 'highlight.js';
import lightbox from '../gallery/lightbox';

function exec() {
    codeBlock();
    singleImageBlock();
}
export default { exec };

function codeBlock() {
    
    // load highlight.js
    hljs.highlightAll(); console.log('highlight.js loaded!');

    // custom styling
    let elements = document.querySelectorAll('code.hljs');
    if(elements.length < 1) return;
    elements.forEach((element) => {

        // Create structure
        let preDiv = element.parentNode;
        let preDivOuterHtml = preDiv.outerHTML;
        let codeContainer = document.createElement('div');
        let codeHeader = document.createElement('div');
        let codeBody = document.createElement('div');
        preDiv.replaceWith(codeContainer);
        codeContainer.classList.add('code-container');
        codeHeader.classList.add('code-header');
        codeBody.classList.add('code-body');
        codeContainer.appendChild(codeHeader);
        codeContainer.appendChild(codeBody);

        // Fill with content
        codeBody.innerHTML = preDivOuterHtml;
        let langClass = codeBody.querySelector('code').classList[0];
        codeHeader.innerText = langClass.substring(langClass.indexOf('-') + 1).toUpperCase();

    });
}

function singleImageBlock() {

    // open lightbox if image link = "#"
    let elements = document.querySelectorAll('figure.image');
    
    if(elements.length < 1) return;
    elements.forEach((element) => {
        if(element.childNodes[0].tagName === "A") {
            let aDiv = element.childNodes[0]
            if(aDiv.getAttribute('href') === "#") {
                let imgUrl = element.querySelector('img').getAttribute('src');
                aDiv.setAttribute('href', imgUrl);
                aDiv.setAttribute('data-lightbox', 'image');
            }
        }

    });
    lightbox.exec();
    
}