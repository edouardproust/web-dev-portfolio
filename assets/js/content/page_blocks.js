import hljs from 'highlight.js';
import lightbox from '../gallery/lightbox';
import '../helper';

function exec() {
    ckfinderEmbedStructureBuilding();
    ckfinderEmbedStyling();
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

        let img = element.querySelector('img');
        let caption = element.querySelector('figcaption');

        // create a container
        let imgContainer = document.createElement('div');
        imgContainer.classList.add('img-container')
        element.appendChild(imgContainer);
        imgContainer.appendChild(img)
        if(caption) imgContainer.appendChild(caption);

        // Image predefined size (force to adapt even if the image is low res)
        if(element.classList.contains('image_resized')) {
            imgContainer.style.width = element.style.width;
            element.style.width = '';
        }

        // Image horizontal align
        const alignLeft = element.classList.contains('image-style-block-align-left') || element.classList.contains('image-style-align-left');
        const alignCenter = element.classList.contains('image-style-align-center');
        const alignRight = element.classList.contains('image-style-block-align-right') || element.classList.contains('image-style-align-right');
        
        if(alignLeft) element.style.justifyContent = 'left';
        else if(alignCenter) element.style.justifyContent = 'center';
        else if(alignRight) element.style.justifyContent = 'right';

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

/**
 * Get an structured object of allowed file types in CKFinder
 * 
 * 
 * @returns {Object} 
 * ```bash
 * { 
 *     extensions: ['ico', 'jpg'],
 *     types: {
 *         images: ['ico', 'jpg',...],
 *         ...
 *     }
 * }
 * ```
 */
function ckfinderEmbedAllowedFiles(getterFn = null, extension = null) 
{
    /** 
     * CKFinder allowed files
     * (must correspond to the ones previously defined in `assets/ckfider/config.php`, under the "Resource Types" section)
     */
    const allowedFileTypes = {
        images: ['ico','webp','bmp','gif','jpeg','jpg','png','svg','tif','tiff'],
        scripts: [/*'htm','html','css','js','php','sql',*/'txt'],
        documents: ['csv','doc','docx','odt','ods','pdf','xls','xlsx'],
        videos: ['mp4','webm'],
        audio_clips: ['mid','mp3','wav'],
        archives: ['rar','zip','7z','gz','gzip','rar','tar','tgz']
    };

    if(getterFn) {
        if(getterFn === 'getExtensions') return getExtensions();
        else if(getterFn === 'getType' && extension) return getType(extension);
        else if(getterFn === 'getTypes') return allowedFileTypes; // same as ckfinderEmbedAllowedFiles(null)
        }
    return allowedFileTypes;

    // Get the file type based on its extension
    function getType(extension) {
        let matchType = null;
        Object.keys(allowedFileTypes).forEach((type) => {
            const extensions = allowedFileTypes[type];
            if(extensions.includes(extension)) matchType = type;
        });
        return matchType;
    }

    // Get an array containing all the allowed extensions
    function getExtensions() {
        let allowedFileExtensions = [];
        Object.keys(allowedFileTypes).forEach((type) => {
            const extensions = allowedFileTypes[type];
            extensions.forEach((extension) => {
                allowedFileExtensions.push(extension);
            });
        })
        return allowedFileExtensions;
    }
}



/**
 * Create custom divs for each files uploaded using ckfinder.
 * The divs have all the classes and contains all the data usefull for further custom styling.
 * - Example of a div created:
 * ```bash
 * <div class="ckfinder-embed-container">
 *    <div class="ckfinder-embed html" data-ckfinder-embed="/uploads/ckfinder/scripts/test.html"></div>
 * </div>
 * ```
 */
function ckfinderEmbedStructureBuilding()
{

    const content = document.querySelector('#content');
    let uploadedFilesLinks = content.querySelectorAll('a');
    const allowedFileExtensions = ckfinderEmbedAllowedFiles('getExtensions');
    // check that the file hasn't been already taken care of CKEditor
    uploadedFilesLinks.forEach((fileLink) => {
        buildEmbedDiv(fileLink);
    });

    /**
     * Build the embed div structure with usefull classes for furter styling
     * (if the div has not been build because of unmet requirements on the 'fileLink' paramter)
     * @param {HTMLElement} fileLink The HTMLElement to be analysed and modified byt the method
     */
    function buildEmbedDiv(fileLink) {
        if(fileLink.classList.length === 0 && fileLink.parentNode.tagName !== 'FIGURE') {
            let file = fileLink.innerText;
            // check that the file has an extension
            let extension =  file.slice((file.lastIndexOf(".") - 1 >>> 0) + 2);
            // build the HTML structure
            if(allowedFileExtensions.includes(extension)) {
                let embedContainerDiv = document.createElement('div');
                embedContainerDiv.classList.add('ckfinder-embed-container');
                let embedDiv = document.createElement('div');
                embedDiv.classList.add('ckfinder-embed');
                embedDiv.classList.add(extension);
                embedDiv.setAttribute('data-ckfinder-embed', fileLink.innerText);
                embedContainerDiv.appendChild(embedDiv);
                fileLink.replaceWith(embedContainerDiv);
                // debug (fill the div with content)
                let fileUrl = embedDiv.getAttribute('data-ckfinder-embed');
                embedDiv.innerHTML = '<a href="' + fileUrl + '" target="_blank">' + fileUrl + '</a>';
            };
        }
    }

}

/**
 * Apply custom styling based on the classes previously generated on the embedDiv
 * - eg. `class="ckfinder-embed txt"`
 * - If not, the div will be ignored
 */
function ckfinderEmbedStyling() 
{
    const embedContainerDivs = document.querySelectorAll('.ckfinder-embed-container');

    // get file type and style the dic accordingly

    for (const div of embedContainerDivs) {

        let embedDiv = div.childNodes[0];
        // clean the div in case it is not empty
        embedDiv.innerHTML = '';
        // get data on file
        let extension = embedDiv.classList[1];
        let type = ckfinderEmbedAllowedFiles('getType', extension);
        let fileUrl = embedDiv.getAttribute('data-ckfinder-embed');
        // make some extensions replacement when needed
        let extraClass = null;
        if(extension === 'htm') extension = 'html'
        if(extension === 'txt' && type === 'scripts') {
            extraClass = 'no-highlight';
            extension = 'text'
        }
        if(extraClass) extraClass = ' class="' + extraClass + '"';
        // Fill div with the custom content
        embedDiv.innerHTML = getDivHtml(type, extension, fileUrl, extraClass);

    };

    function getDivHtml(type, extension, fileUrl, extraClass = null) {
        let divHtml;

        // images
        if(type === 'images') {
            divHtml = 
                '<figure class="image">' + 
                    // '<a href="#">' + // to open lightbox onclick
                        '<img src="' + fileUrl + '">' +
                    // '</a>'
                '</figure>';
        } 

        // scripts
        else if(type === 'scripts') { 
            // console.log(await fileGetContents(fileUrl));
            divHtml = 
                '<pre>' + 
                    '<code class="language-' + extension + ' hljs' + extraClass + '">' + 
                        fileUrl;
                    '</code>' +
                '</pre>';
        }
        // documents
        else if(type === 'documents') {
            divHtml = 
                '<div class="content-button-container">' +
                    '<a href="' + fileUrl + '" target="_blank">' + 
                        '<button type="button" class="button content-button m-0">Download document</button>' +
                    '</a>' +
                '</div>';
        }
        // videos
        else if(type === 'videos') {

            divHtml = 
                '<div class="content-video-container">' + 
                    '<video width="auto" height="auto" controls>' + 
                        '<source src="' + fileUrl + '" type="video/' + extension + '">' + 
                        'Your browser does not support the video tag.' +
                    '</video>' + 
                '</div>';
        }
        // audio_clips
        else if(type === 'audio_clips') {
            divHtml =
                '<div class="content-audio-container">' + 
                    '<audio controls class="content-audio">' + 
                        '<source src="' + fileUrl +'" type="audio/' + extension + '">' +           
                        'Your browser does not support the audio element.' + 
                    '</audio>'
                '</div>';
        }
        // archives
        else if(type === 'archives') {
            divHtml = 
                '<div class="content-button-container">' +
                    '<a href="' + fileUrl + '" target="_blank">' + 
                        '<button type="button" class="button content-button m-0">Download archive</button>' +
                    '</a>' + 
                '</div>';
        // Others
        } else {
            divHtml = '<a href="' + fileUrl + '" target="_blank">File</a>';
        }

        return divHtml;
    }
}