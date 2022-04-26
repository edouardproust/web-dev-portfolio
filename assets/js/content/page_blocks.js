import hljs from 'highlight.js';
import lightbox from '../gallery/lightbox';

function exec() {
    codeBlock();
    singleImage();
    mediaEmbed();
}
export default { exec };

function codeBlock() 
{
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

function singleImage() 
{
    let elements = document.querySelectorAll('figure.image');
    if(elements.length < 1) return;

    elements.forEach((element) => {
        // open lightbox if image link = "#"
        if(element.childNodes[0].tagName === "A") {
            let aDiv = element.childNodes[0]
            if(aDiv.getAttribute('href') !== "#") {
                aDiv.setAttribute('target', '_blank'); // open in a new window
            } else {
                let imgUrl = element.querySelector('img').getAttribute('src');
                aDiv.setAttribute('href', imgUrl);
                aDiv.setAttribute('data-lightbox', 'image');
                // lightbox styling
                element.addEventListener('click', (e) => {
                    let lightboxContent = document.querySelector('.mfp-content')
                    let lightboxFigure = lightboxContent.querySelector('.mfp-figure');
                    let img = lightboxContent.querySelector('.mfp-img');
                    let caption = element.querySelector('figcaption');
                    let lightCaption = lightboxContent.querySelector('figcaption');
                    if(caption) { // rearange caption layout
                        lightCaption.parentNode.removeChild(lightCaption);
                        lightCaption.classList.add('lightbox-caption');
                        lightCaption.innerHTML = caption.innerText; 
                        lightCaption.style.maxWidth = img.offsetWidth + 'px';
                        let captionSave = lightCaption;
                        lightboxContent.appendChild(captionSave);
                    } else { // remove caption structure
                        let div = document.createElement('div');
                        div.innerHTML = lightboxFigure.querySelector('figure').innerHTML;
                        lightboxFigure.replaceWith(div);
                        div.removeChild(div.querySelector('figcaption'))
                    }
                    console.log('nope')
                    lightboxFigure.classList.add('lightbox-figure');
                    img.classList.add('lightbox-img');
                });
            }
        }
    });
    lightbox.exec();
}

function mediaEmbed() 
{
    const NEEDLE = '{{ mediaUrl }}';
    const CONVERTERS = {
        youtube: '<iframe width="560" height="315" src="{{ mediaUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        vimeo: '<iframe src="{{ mediaUrl }}" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>'
    }

    const containers = document.querySelectorAll('figure.media');
    if(containers.length < 1) return;

    containers.forEach((container) => {

        const mediaUrl = container.querySelector('oembed[url]').getAttribute('url');
        const embedCode = getEmbedCode(mediaUrl);
        if(embedCode !== null) {
            console.log('================== EMBED CODE ====================', embedCode);
            container.innerText = embedCode;
        }
        
        function getEmbedCode(mediaUrl) {
            let match = false;
            Object.keys(CONVERTERS).forEach((plateformName) => {
                if(mediaUrl.includes(plateformName)) match = plateformName;
            });
            if(match !== false) {
                return CONVERTERS[match].replace(NEEDLE, mediaUrl);
            } else {
                console.log('Error (Media embed converter system): The media url provided is related to a plateform that is not in our converters library yet.  You must create a new converter in order to process this media.');
                return null;
            }
        }

    });
}