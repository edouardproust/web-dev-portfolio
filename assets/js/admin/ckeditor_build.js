const DEBUG = false;
const SELECTOR = '.ckeditorField';
const ALERT_SELECTOR = '.invalid-feedback';
const CK_EDITABLE_SELECTOR = '[role="textbox"]';
// The editor won't be build if the div contains one of these classes:
const FORBIDDEN_CLASSES = [ 
    ALERT_SELECTOR 
]; 

export default function buildEditors(ClassicEditor, toolsConfig) 
{
    let elements = document.querySelectorAll(SELECTOR);
    if(elements.length < 1) return; 

    elements.forEach((element) => {
        if(!isForbidden(element)) {
            buildOneEditor(ClassicEditor, toolsConfig, element);
        }
    });
    buildSuccessLog(elements.length);
}

function buildOneEditor(ClassicEditor, toolsConfig, element) 
{
    ClassicEditor
        .create(element, toolsConfig)
        .then(editor => {
            let editableEl = element.parentNode.querySelector(CK_EDITABLE_SELECTOR);
            let alertEl = element.parentNode.querySelector(ALERT_SELECTOR);

            prepareFormSumbit(editor, element);
            wordCount(editor, element, alertEl);
            customStyle(element, editableEl); // must be at last position
            
        })
        .catch( error => {
            console.error( error.stack );
        });
}

/**
 * On editabelEl change: edit element (easyAdmin field) with the content of the editableEl content (CKEditor)
*/
function prepareFormSumbit(editor, element)
{   
    if(DEBUG) element.style.display = "block";

    // Bug fix: if the hidden field is blank (new post), the code after doesn't work
    if(element.value < 1) element.value = '<p></p>'
    // Inject clean code into the native hidden field (attached to easyAdmin)
    document.querySelector('.content-body > form')
        .addEventListener('submit', () => {
            element.value = editor.getData();
        });
}

function wordCount(editor, element, alertEl)
{
    // create a wrapper
    let divsWrapper = document.createElement('div');
    element.parentNode.appendChild(divsWrapper);
    // create a word-cound div
    const wordCountWrapper = document.createElement('div');
    wordCountWrapper.classList.add('word-count');
    // append divs to wrapper
    divsWrapper.appendChild(wordCountWrapper);
    if(alertEl !== null) {
        divsWrapper.appendChild(alertEl)
    }
    // fill word-cound div with data
    const wordCountPlugin = editor.plugins.get( 'WordCount' );
    wordCountWrapper.appendChild( wordCountPlugin.wordCountContainer );
}

function customStyle(element, editableEl)
{
    // editable field: height & corners
    editableEl.setAttribute('data-editable-el', true);
    // words count
    let wordCountEl = element.parentNode.querySelector('.word-count')
    wordCountEl.childNodes[0].classList.add('ck-toolbar');
}

/**
 * Check if the the current div target contains one of the FORBIDDEN_CLASSES
 * @returns 'true' if the div contains one of the FORBIDDEN_CLASSES, 'false' otherwise
 */
 function isForbidden(element) {
    let forbidden = false;
    FORBIDDEN_CLASSES.forEach((item) => {
        // substring is used to remove the first char ('.' for a class or '#' for an id)
        if(element.classList.contains(item.substring(1))) { 
            forbidden = true;
        }
    });
    return forbidden;
}

/**
 * Display a confirmation log when script has been successfully loaded
 * @param {Number} elementsNb 
 */
function buildSuccessLog(elementsNb) 
{
    console.log('CKEditor: ' + elementsNb + ' editor' + (elementsNb > 1 ? 's' : '') + ' built!');
}