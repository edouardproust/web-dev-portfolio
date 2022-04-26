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
    const YOUTUBE = 'youtube';
    const VIMEO = 'vimeo';

    const EMBED_NEEDLE = '{{ embedNeedle }}';
    
    const CONVERTERS = {
        youtube: {
            urlCorrectBase: 'https://www.youtube.com/embed/', // Wrong one: https://www.youtube.com/watch?v=OzIpdEYUtn8
            embedCode: // Config tool: https://www.classynemesis.com/projects/ytembed/
                '<iframe ' +
                    'width="560" ' +
                    'height="315" ' +
                    'src="{{ embedNeedle }}?autoplay=1&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&color=white"' + 
                    'frameborder="0" ' +
                '></iframe>'
        },
        vimeo: {
            urlCorrectBase: 'https://player.vimeo.com/video/', // Wrong one: 'https://vimeo.com/696472656',
            embedCode: 
                '<iframe ' +
                    'src="{{ embedNeedle }}" ' +
                    'width="640"' +
                    'height="360"' +
                    'frameborder="0"' +
                    'allow="autoplay; fullscreen"' +
                    'allowfullscreen' +
                '></iframe>'
        }
    }

    const containers = document.querySelectorAll('figure.media');
    if(containers.length < 1) return;

    containers.forEach((container) => {

        const mediaUrl = container.querySelector('oembed[url]').getAttribute('url');
        const embedCode = getEmbedCode(mediaUrl);
        container.innerHTML = embedCode;
    
        function getEmbedCode(url) {
            const plateform = getPlateform(url);
            if(plateform) {
                const correctUrl = getCorrectUrl(url, plateform)
                return CONVERTERS[plateform].embedCode.replace(EMBED_NEEDLE, correctUrl);
            } else {
                console.log('Error (Media embed converter system): The media url provided is related ' + 
                    'to a plateform that is not in our converters library yet.  You must create a new ' +
                    'converter in order to process this media.');
                return null;
            }
            
            // Return the plateform id (on success) or false (failure)
            function getPlateform(url) {
                let plateform = false;
                Object.keys(CONVERTERS).forEach((plateformId) => {
                    if(url.includes(plateformId)) {
                        plateform = plateformId
                    };
                });
                return plateform;
            }

            // Convert the browser url ("wrong" one) into the embed url ("good" one)
            function getCorrectUrl(url, plateform) {
                if(plateform === YOUTUBE) { // Goal: https://www.youtube.com/watch?v=OzIpdEYUtn8 => 'https://www.youtube.com/embed/OzIpdEYUtn8'
                    let urlBase = url.replace( url.split("v=")[1], '' ); // check if the baseUrl is alreadycorrect
                    if(urlBase !== CONVERTERS[plateform].urlCorrectBase) { // if not...
                        url = url.replace('watch?v=', 'embed/'); // ...filter url to make it match the correct one
                    }
                }
                else if(plateform === VIMEO) { // Goal: https://vimeo.com/696472656' => 'https://player.vimeo.com/video/696472656'
                    let urlBase = url.replace( url.split(".com/")[1], '' ); 
                    if(urlBase !== CONVERTERS[plateform].urlCorrectBase) {
                        url = url.replace('vimeo.com', 'player.vimeo.com/video');
                    }
                }
                return url;
            }
        }

    });
}