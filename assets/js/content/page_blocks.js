export default { exec };

import hljs from 'highlight.js';
import lightbox from '../gallery/lightbox';
import accordions from "../../js/content/accordions";

/** 
 * CKFinder allowed files
 * (must correspond to the ones previously defined in `assets/ckfider/config.php`, under the "Resource Types" section)
 */
const ALLOWED_FILE_TYPES = {
    result: [ 'php' ],
    images: ['ico','webp','bmp','gif','jpeg','jpg','png','svg','tif','tiff'],
    scripts: ['htm','html','css','js','phps','sql','txt', 'c', 'h', 'py'],
    documents: ['csv','doc','docx','odt','ods','pdf','xls','xlsx'],
    videos: ['mp4','webm'],
    audio_clips: ['mid','mp3','wav'],
    archives: ['rar','zip','7z','gz','gzip','rar','tar','tgz']
};

const UPLOADS_STYLES = {
    images: 
        '<figure class="image">' + 
            // '<a href="#">' + // to open lightbox onclick
                '<img src="{{ fileUrl }}">' +
            // '</a>' + 
        '</figure>',
    scripts: 
        '<pre>' + 
            '<code class="language-{{ extension }} hljs" data-file-url="{{ fileUrl }}"></code>' +
        '</pre>',
    result: 
        '<iframe class="content-result" src="{{ fileUrl }}" data-file-url="{{ fileUrl }}" title="Result"></iframe>',
    documents: 
        '<div class="content-button-container">' +
            '<a href="{{ fileUrl }}" target="_blank">' + 
                '<button type="button" class="button content-button m-0">Download document</button>' +
            '</a>' +
        '</div>',
    videos:
        '<div class="content-video-container">' + 
            '<video width="auto" height="auto" controls>' + 
                '<source src="{{ fileUrl }}" type="video/{{ extension }}">' + 
                'Your browser does not support the video tag.' +
            '</video>' + 
        '</div>',
    audio_clips:
        '<div class="content-audio-container">' + 
            '<audio controls class="content-audio">' + 
                '<source src="{{ fileUrl }}" type="audio/{{ extension }}">' +           
                'Your browser does not support the audio element.' + 
            '</audio>' +
        '</div>',
    archives:
        '<div class="content-button-container">' +
            '<a href="{{ fileUrl }}" target="_blank">' + 
                '<button type="button" class="button content-button m-0">Download archive</button>' +
            '</a>' + 
        '</div>',
    default: '<a href="{{ fileUrl }}" target="_blank">File</a>'
};

const EMBED_CONVERTERS = {
    youtube: {
        urlCorrectBase: 'https://www.youtube.com/embed/', // Wrong one: https://www.youtube.com/watch?v=OzIpdEYUtn8
        embedCode: // Config tool: https://www.classynemesis.com/projects/ytembed/
            '<iframe ' +
                'width="560" ' +
                'height="315" ' +
                'src="{{ mediaUrl }}?autoplay=1&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&color=white"' + 
                'frameborder="0" ' +
            '></iframe>'
    },
    vimeo: {
        urlCorrectBase: 'https://player.vimeo.com/video/', // Wrong one: 'https://vimeo.com/696472656',
        embedCode: 
            '<iframe ' +
                'src="{{ mediaUrl }}" ' +
                'width="640"' +
                'height="360"' +
                'frameborder="0"' +
                'allow="autoplay; fullscreen"' +
                'allowfullscreen' +
            '></iframe>'
    }
};

const EXTENSION_REPLACE = {
    phps: 'php',
    h: 'c'
};

function exec() {
    uploadedFile();
    resultBox();
    codeBlock();
    singleImage();
    mediaEmbed();
    accordion(accordions, 'accordion-bg');
}

/**
 * Render all uploaded files on the page
 */
function uploadedFile()
{
    document.querySelector('#content')
        .querySelectorAll('a')
        .forEach((aElement) => {
            // verify that the file has been included by CKFinder
            if(aElement.classList.length === 0 && aElement.parentNode.tagName !== 'FIGURE') {
                let embedDiv = uploadedFile__buildOne(aElement, EXTENSION_REPLACE);
                if(embedDiv) uploadedFile__applyStyle(embedDiv);
            }
        });
}

/**
 * Render all code blocks on the page
 */
async function codeBlock() 
{
    const SELECTOR = 'code';

    let elements = document.querySelectorAll(SELECTOR);
    if(elements.length < 1) return;

    for(let element of elements) {
        if(element.classList.length > 0 && element.classList[0].startsWith('language-')) { // to avoid conflict with the inline `code` markup
            if(element.getAttribute('data-file-url')) {
                await uploadedFile__fetchContent(element);
            }
            codeBlock__buildOne(element);
        }
    }
    hljs.highlightAll(); console.log('highlight.js loaded!');
}

/**
 * Render all single images on the page
 */
function singleImage() 
{
    let elements = document.querySelectorAll('figure.image');
    if(elements.length < 1) return;

    elements.forEach((element) => {
        let img = element.querySelector('img');
        let caption = element.querySelector('figcaption');
        let imgContainer = singleImage__buildOne(element, img, caption);
        singleImage__size(element, imgContainer);
        singleImage__horizontalAlign(element);
        singleImage__clickable(element, imgContainer, img, caption);
    });
    lightbox.exec();
}

/**
 * Render all embeded medias on the page
 */
function mediaEmbed() 
{
    const containers = document.querySelectorAll('figure.media');
    if(containers.length < 1) return;

    containers.forEach((container) => {
        const mediaUrl = container.querySelector('oembed[url]').getAttribute('url');
        const plateform = mediaEmbed__getPlateform(mediaUrl);
        if(plateform) {
            const correctUrl = mediaEmbed__translateUrl(mediaUrl, plateform)
            container.innerHTML = EMBED_CONVERTERS[plateform].embedCode.replace('{{ mediaUrl }}', correctUrl);
        } else {
            console.log(
                'Error (Media embed converter system): The media url provided is related ' + 
                'to a plateform that is not in our converters library yet.  You must create a new ' +
                'converter in order to process this media.'
            );
        }
    });
}

/**
 * Render all accordions on the page
 * 
 * @param {CallableFunction} accordions 
 * @param {String|null} additionalClass 'accordion-bg', 'accordion-border' or null (default: null)
 * @returns 
 */
function accordion(accordions, additionalClass = null)
{
    const SELECTOR_CLASS = 'accordion-container';

    let elements = document.getElementsByClassName(SELECTOR_CLASS);
    if(elements.length < 1) return;

    const parent = elements[0].parentNode;

    let groups = accordion__groupItems(parent, SELECTOR_CLASS);
    groups.forEach((group) => {
        accordion__buildOne(group, parent, additionalClass);
    });
    accordions.exec();
}

/**
 * Render all result boxes on the page
 */
async function resultBox()
{
    const SELECTOR = '.resultbox-container';

    let elements = document.querySelectorAll(SELECTOR);
    if(elements.length < 1) return;

    for(let element of elements) {
        // build structure
        let headerDiv = document.createElement('div');
        headerDiv.classList.add('resultbox-header');
        headerDiv.innerText = "RESULT";
        let bodyDiv = document.createElement('div');
        bodyDiv.classList.add('resultbox-body');
        bodyDiv.innerHTML = element.querySelector('.resultbox-content').innerHTML;
        element.removeChild(element.querySelector('.resultbox-content'));
        element.appendChild(headerDiv);
        element.appendChild(bodyDiv);
        bodyDiv.querySelectorAll('p').forEach((pEl) => {
            if(pEl.innerHTML.length < 1) bodyDiv.removeChild(pEl);
        });
        
        // if contains files
        let embedDivs = element.querySelectorAll('.ckfinder-embed')
        if(embedDivs.length > 0) {
            for(let embedDiv of embedDivs) {
                let type = embedDiv.getAttribute('data-ckfinder-embed-type');
                if(type === 'result') { // script result
                    uploadedFile__fetchContent(embedDiv.querySelector('.content-result'));

                    var frame = embedDiv.querySelector("iframe.content-result");
                    frame.style.height = frame.contentWindow.document.body.scrollHeight+"px";
                    // console.log(frame)
                    // frame.onload = function() {
                    //     frame.style.height = frame.contentWindow.document.body.scrollHeight + 'px';
                    //     // frame.style.width  = frame.contentWindow.document.body.scrollWidth+'px';
                    // }
                }
            }
        }
    }
}


// Nested functions =========================================================

/**
 * Build the embed div structure for 1 element, with usefull classes for furter styling
 * - Example of a div created:
 * `<div class="ckfinder-embed html" data-ckfinder-embed="/uploads/ckfinder/scripts/test.html"></div>`
 * @param {HTMLElement} aElement The HTMLElement to be analysed and modified byt the method
 * @returns {HTMLElement} The newly created  `div.ckfinder-embed` element
 */
function uploadedFile__buildOne(aElement, extensionReplace = [])
{
    let extension = uploadedFile__isAllowed(aElement.innerText);
    let embedDiv = null;
    if(extension) {
        embedDiv = document.createElement('div');
        embedDiv.classList.add('ckfinder-embed');
        embedDiv.setAttribute('data-ckfinder-embed', true);
        embedDiv.setAttribute('data-ckfinder-embed-url', aElement.innerText);
        embedDiv.setAttribute('data-ckfinder-embed-type', uploadedFile__getType(extension));
        if(Object.keys(extensionReplace).includes(extension)) extension = extensionReplace[extension];
        embedDiv.setAttribute('data-ckfinder-embed-extension', extension);
        aElement.replaceWith(embedDiv);
    };
    return embedDiv;
}

/**
 * Apply custom styling for 1 element
 * @param {HTMLElement} embedDiv The `div.ckfinder-embed` element
 */
function uploadedFile__applyStyle(embedDiv) 
{
    // clean the `embedContainer` in case it is not empty
    embedDiv.innerHTML = '';
    // get data on file
    let fileUrl = embedDiv.getAttribute('data-ckfinder-embed-url');
    let type = embedDiv.getAttribute('data-ckfinder-embed-type');
    let extension = embedDiv.getAttribute('data-ckfinder-embed-extension');
    // make some extensions replacement when needed
    if(extension === 'htm') extension = 'html';
    if(extension === 'txt' && type === 'scripts') extension = 'plaintext';
    // Fill `embedContainer` with the custom content
    embedDiv.innerHTML = UPLOADS_STYLES[type]
        .replace('{{ fileUrl }}', fileUrl)
        .replace('{{ extension }}', extension)
}

/**
 * Get the file type based on its extension
 * @param {String} extension The file's extension (eg. 'png', 'jpg', 'pdf',...)
 * @return {string|null} The file type (eg. 'images', 'videos',...) or null if extension is not allowed
 */
function uploadedFile__getType(extension) 
{
    // others
    let matchType = null;
    Object.keys(ALLOWED_FILE_TYPES).forEach((type) => {
        const extensions = ALLOWED_FILE_TYPES[type];
        if(extensions.includes(extension)) matchType = type;
    });
    return matchType;
}

/**
 * Check if the file is allowed (by comparing its extension to the list of allowed ones)
 * @param {String} file the file's name or path
 * @returns {String|null} The file's extension if it is allowed (eg. 'jpg', 'png', 'pdf',...), null otherwise
 */
function uploadedFile__isAllowed(file) 
{
    // buil an array containing all allowed extensions
    let allowedFileExtensions = [];
    Object.keys(ALLOWED_FILE_TYPES).forEach((type) => {
        const extensions = ALLOWED_FILE_TYPES[type];
        extensions.forEach((extension) => {
            allowedFileExtensions.push(extension);
        });
    })
    // check if the fil's extension is in it
    let extension =  file.slice((file.lastIndexOf(".") - 1 >>> 0) + 2);
    if(allowedFileExtensions.includes(extension)) {
        return extension;
    }
    return null;
}

/**
 * Build the HTML structure inside the `code.hljs` element, 
 * which will be used for further styling of the code
 * @param {HTMLElement} element The `code.hljs` element
 */
function codeBlock__buildOne(codeEl)
{
    // Create structure
    let preDiv = codeEl.parentNode;
    let codeContainer = document.createElement('div');
    let codeHeader = document.createElement('div');
    let codeBody = document.createElement('div');
    preDiv.replaceWith(codeContainer);
    codeContainer.classList.add('code-container');
    codeHeader.classList.add('code-header');
    codeBody.classList.add('code-body');
    codeContainer.appendChild(codeHeader);
    codeContainer.appendChild(codeBody);

    // Fill with the (escaped) content
    codeEl.innerHTML = uploadedFile__escapeContent(codeEl.innerHTML);
    codeBody.appendChild(preDiv);
    
    // Header content
    let langClass = codeEl.classList[0];
    let langName = langClass.substring(langClass.indexOf('-') + 1);
    if(langName === 'plaintext') langName = 'Plain text';
    if(langName === 'py') langName = 'Python';
    codeHeader.innerText = langName.toUpperCase();
}

async function uploadedFile__fetchContent(codeElement) {
        let scriptUrl = codeElement.getAttribute('data-file-url');
        await fetch(scriptUrl)
        .then((result) => { return result.text(); })
        .then((content) => { 
            codeElement.innerText = content;
        });
}

function uploadedFile__escapeContent(string)
{
    let parts = string.split(/<br\s*\/?>/gim);
    return parts.join('\n');
}

/**
 * Build the HTML structure inside the `figure.image` element, 
 * which will be used for further styling of the block
 * @param {HTMLElement} element The `figure.image` element
 * @param {HTMLElement} img The `img` element
 * @param {HTMLElement} caption The `figcaption` element
 * @returns The `img-container` div just created 
 * (containing all the figure structure)
 */
function singleImage__buildOne(element, img, caption)
{
    let imgContainer = document.createElement('div');
    imgContainer.classList.add('img-container')
    element.appendChild(imgContainer);
    imgContainer.appendChild(img)
    if(caption) imgContainer.appendChild(caption);
    return imgContainer;
}

/**
 * Apply/enforce the size of the image, 
 * - if CKEditor injected the class `image_resized` to the `figure` container, 
 * - and even if the image is low res
 * @param {HTMLElement} element The `figure.image` element
 * @param {HTMLElement} imgContainer The `div.img-container` element
 */
function singleImage__size(element, imgContainer)
{
    if(element.classList.contains('image_resized')) {
        imgContainer.style.width = element.style.width;
        element.style.width = '';
    }
}

/**
 * Set the hz alignement of a single image (1 on a row), 
 * - based on class injected by CKEditor in the `figure` container 
 * (eg. `image-style-block-align-left`)
 * @param {HTMLElement} element The `figure.image` element
 */
function singleImage__horizontalAlign(element)
{
    // Image horizontal align
    const alignLeft = element.classList.contains('image-style-block-align-left') || element.classList.contains('image-style-align-left');
    const alignCenter = element.classList.contains('image-style-align-center');
    const alignRight = element.classList.contains('image-style-block-align-right') || element.classList.contains('image-style-align-right');
    
    if(alignLeft) element.style.justifyContent = 'left';
    else if(alignCenter) element.style.justifyContent = 'center';
    else if(alignRight) element.style.justifyContent = 'right';
}

/**
 * Adapt the structure inside the `figure` container if the image is clickable 
 * @param {HTMLElement} element The `figure.image` element
 * @param {HTMLElement} img The `img` element
 * @param {HTMLElement} imgContainer The `div.img-container` element
 * @param {HTMLElement} caption The `figcaption` element
 */
function singleImage__clickable(element, imgContainer, img, caption)
{
    // open lightbox if image link = "#"
    if(element.childNodes[0].tagName === "A") {

        let aElement = element.childNodes[0];
        aElement.classList.add('img-clickable')

        aElement.appendChild(img);
        imgContainer.appendChild(aElement);
        if(caption) {
            imgContainer.removeChild(caption);
            imgContainer.appendChild(caption);
        }

        if(aElement.getAttribute('href') !== "#") {
            aElement.setAttribute('target', '_blank'); // open in a new window
        } else {
            singleImage__clickable__lightbox(element, img, caption, aElement);
        }
    }
}

/**
 * Build the lighbox HTML structure for clickable images that use lighbox (with a "#" link) 
 * @param {HTMLElement} element The `figure.image` element
 * @param {HTMLElement} img The `img` element
 * @param {HTMLElement} caption The `figcaption` element
 * @param {HTMLElement} aElement The `a.img-clickable` element
 */
function singleImage__clickable__lightbox(element, img, caption, aElement) 
{
    let imgUrl = img.getAttribute('src');
    aElement.setAttribute('href', imgUrl);
    aElement.setAttribute('data-lightbox', 'image');
    // lightbox styling
    element.addEventListener('click', (e) => {
        let mfpContent = document.querySelector('.mfp-content')
        let mfpFigure = mfpContent.querySelector('.mfp-figure');
        let mfpImg = mfpContent.querySelector('.mfp-img');
        let mfpCaption = mfpContent.querySelector('figcaption');
        if(caption) { // rearange caption layout
            mfpCaption.parentNode.removeChild(mfpCaption);
            mfpCaption.classList.add('lightbox-caption');
            mfpCaption.innerHTML = caption.innerText; 
            mfpCaption.style.maxWidth = mfpImg.offsetWidth + 'px';
            let captionSave = mfpCaption;
            mfpContent.appendChild(captionSave);
        } else { // remove caption structure
            let div = document.createElement('div');
            div.innerHTML = mfpFigure.querySelector('figure').innerHTML;
            mfpFigure.replaceWith(div);
            div.removeChild(div.querySelector('figcaption'))
        }
        mfpFigure.classList.add('lightbox-figure');
        mfpImg.classList.add('lightbox-img');
    });
}

/**
 * Return the plateform id (on success) or false (failure)
 * @param {String} url The media url
 * @returns {String} The plateform name (eg. 'youtube', 'vimeo',...)
 */
function mediaEmbed__getPlateform(url) {
    let plateform = false;
    Object.keys(EMBED_CONVERTERS).forEach((plateformId) => {
        if(url.includes(plateformId)) {
            plateform = plateformId
        };
    });
    return plateform;
}

/**
 * Convert the browser url ("wrong" one) into the embed url ("good" one)
 * @param {String} url The media url
 * @param {String} plateform 
 * @returns 
 */
function mediaEmbed__translateUrl(url, plateform) {
    if(plateform === 'youtube') { // Goal: https://www.youtube.com/watch?v=OzIpdEYUtn8 => 'https://www.youtube.com/embed/OzIpdEYUtn8'
        let urlBase = url.replace( url.split("v=")[1], '' ); // check if the baseUrl is alreadycorrect
        if(urlBase !== EMBED_CONVERTERS[plateform].urlCorrectBase) { // if not...
            url = url.replace('watch?v=', 'embed/'); // ...filter url to make it match the correct one
        }
    }
    else if(plateform === 'vimeo') { // Goal: https://vimeo.com/696472656' => 'https://player.vimeo.com/video/696472656'
        let urlBase = url.replace( url.split(".com/")[1], '' ); 
        if(urlBase !== EMBED_CONVERTERS[plateform].urlCorrectBase) {
            url = url.replace('vimeo.com', 'player.vimeo.com/video');
        }
    }
    return url;
}

/**
 * Group accordion items that are adjacent
 * @returns {Array} Array of accordions items (HTMLElements)
 */
function accordion__groupItems(parent, selectorClass)
{
    const childs = parent.childNodes;

    let accordionEls = [];
    let groupIndex = 0;
    accordionEls[groupIndex] = [];
    childs.forEach((child, index) => {
        if(child.classList.contains(selectorClass)) {
            // si c'est index 0 
            if(index > 0 && !childs[index - 1].classList.contains(selectorClass)) {
                if(accordionEls[groupIndex].length > 0) {
                    groupIndex += 1;
                    accordionEls[groupIndex] = [];
                }
            }
            accordionEls[groupIndex].push(child);
        }
    });
    return accordionEls;
}
 
/**
 * Wrap groups of accordion items into a container, and build the structure for further styling (with css)
 * @param {Array} accordionEls Arrayof accordions items (HTMLElements)
 */
function accordion__buildOne(group, parent, additionalClass)
{
    let containerDiv = document.createElement('div');
    containerDiv.classList.add('accordion');
    if(additionalClass) containerDiv.classList.add(additionalClass);
    containerDiv.setAttribute('data-collapsible', true);
    parent.insertBefore(containerDiv, group[0]);
    group.forEach((item) => {
        let titleEl = item.querySelector('div.accordion-title');
        let contentEl = item.querySelector('div.accordion-content');

        let headerDiv = document.createElement('div');
        headerDiv.classList.add('accordion-header');
        headerDiv.innerHTML = 
            '<div class="accordion-icon">' + 
                '<i class="accordion-closed icon-ok-circle"></i>' + 
                '<i class="accordion-open icon-remove-circle"></i>' +
            '</div>';
        if(titleEl) headerDiv.appendChild(titleEl);
        containerDiv.appendChild(headerDiv);
        if(contentEl) containerDiv.appendChild(contentEl);
    });
}