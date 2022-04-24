import ClassicEditor from '../../../public/build/ckeditor/builds/full/src/ckeditor';
import CKEditorInspector from '@ckeditor/ckeditor5-inspector';

const SELECTOR = '.ckeditorField';
const ALERT_SELECTOR = '.invalid-feedback';
const CK_EDITABLE_SELECTOR = '[role="textbox"]';
const FORBIDDEN_CLASSES = [ ALERT_SELECTOR ]; // The editor won't be build if the div also contains one of these classes
const DEBUG = false;

function exec() 
{
    let elements = document.querySelectorAll(SELECTOR);
    let elementsNb = elements.length;
    if(elementsNb < 1) {
        return; 
    }
    elements.forEach((element) => {
        if(!isForbidden(element)) {
            buildEditor(element);
        }
    });
    if(DEBUG) buildSuccessLog(elementsNb);
}
export default { exec };

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

function buildEditor(element) 
{
    ClassicEditor
        // Build config: https://ckeditor.com/docs/ckeditor5/latest/api/module_core_editor_editorconfig-EditorConfig.html
        .create(element)
        .then(editor => {
            CKEditorInspector.attach( editor );
            // selectors
            let editableEl = element.parentNode.querySelector(CK_EDITABLE_SELECTOR);
            let alertEl = element.parentNode.querySelector(ALERT_SELECTOR);
            // functions
            setFieldData(element, editableEl);
            prepareFormSumbit(editor, element);
            wordCount(editor, element, alertEl);
            customStyle(element, editableEl); // must be at last position
        })
        .catch( error => {
            if(DEBUG) console.error( error.stack );
        });
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
 * On form load: the editable element (CKEditor) from database (data of the original EasyAdmin field)
 * @link https://ckeditor.com/docs/ckeditor5/latest/support/faq.html#how-to-get-the-editor-instance-object-from-the-dom-element
*/
function setFieldData(element, editableEl) 
{
    const instance = editableEl.ckeditorInstance;
    instance.setData(element.value);
}

/**
 * On editabelEl change: edit element (easyAdmin field) with the content of the editableEl content (CKEditor)
*/
function prepareFormSumbit(editor, element)
{    // Debug: show hidden field
    if(DEBUG) element.style.display = "block";
    // Inject clean code into the native hidden field (attached to easyAdmin)
    document.querySelector('.content-body > form')
        .addEventListener('submit', (e) => {
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

/**
 * Display a confirmation log when script has been successfully loaded
 * @param {Number} elementsNb 
 */
function buildSuccessLog(elementsNb) 
{
    console.log('CKEditor: ' + elementsNb + ' editor' + (elementsNb > 1 ? 's' : '') + ' built!');
}

