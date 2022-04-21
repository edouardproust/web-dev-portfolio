/**
 * Doc:
 * - Config Toolbar: https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html
 */

import ClassicEditor from '../../../public/build/ckeditor/builds/full/src/ckeditor';

const SELECTOR = '.ckeditorField';
const DEBUG = false;

async function exec() 
{
    let elements = document.querySelectorAll(SELECTOR);
    let elementsNb = elements.length;
    if(elementsNb < 1) {
        return; 
    }
    elements.forEach((element) => {
        buildEditor(element);
        customStyle(element);
        if(DEBUG) buildSuccessLog(elementsNb);
        setFieldData(element);
        prepareFormSumbit(element);
    });
}
export default { exec };

function buildEditor(element) 
{
    ClassicEditor
        // Note that you do not have to specify the plugin and toolbar configuration — using defaults from the build.
        .create( element )
        .then( editor => {
            if(DEBUG) console.log( 'Editor was initialized', editor );
            wordCount(editor, element);
        } )
        .catch( error => {
            if(DEBUG) console.error( error.stack );
        } );
}

function customStyle(element)
{
    setTimeout(() => {
        let ckedirorField = element.parentNode.querySelector('.ck-editor');
        ckedirorField.setAttribute('rows', 40);
    }, 1);
    setTimeout(() => {
        let wordCount = element.parentNode.querySelector('.word-count')
        console.log(element.parentNode.querySelector('.word-count'))
        wordCount.childNodes[0].classList.add('ck-toolbar');
    }, 1000);
}

/**
 * Display a confirmation log when script has been successfully loaded
 * @param {int} elementsNb 
 */
function buildSuccessLog(elementsNb) 
{
    console.log('CKEditor: ' + elementsNb + ' editor' + (elementsNb > 1 ? 's' : '') + ' built!');
}

/**
 * Fill the original field (EasyAdmin) with content typed by the user in the CKEditor field.
 * @param {object} element 
 */
function prepareFormSumbit(element)
{
    setTimeout(() => {
        let editableEl = element.parentNode.querySelector('.ck-editor__editable ');
    
        if(DEBUG) {
            element.style.display = 'block';
            console.log(element);
        }

        // look for updates in createdField
        let editableElBefore = editableEl.innerHTML;
        setInterval(() => {
            if(editableElBefore !== editableEl.innerHTML) {
                element.value = editableEl.innerHTML;
                editableElBefore = editableEl.innerHTML;
            }
        }, 1);

    }, 1);
}

/**
 * On form load: the editable element (CKEditor) from database (data of the original EasyAdmin field)
 * @link https://ckeditor.com/docs/ckeditor5/latest/support/faq.html#how-to-get-the-editor-instance-object-from-the-dom-element
 * @param {object|null} element If set on null and tseveral instances exist, the first one will be returned. Default: null 
*/
async function setFieldData(element) 
{
    setTimeout(() => {
        let editableEl = element.parentNode.querySelector('.ck-editor__editable ');
        const instance = editableEl.ckeditorInstance;
        instance.setData(element.value);
    }, 1);
}

function wordCount(editor, element)
{
    setTimeout(() => {
        console.log(element.parentNode)
        const wordCountWrapper = document.createElement('div');
        wordCountWrapper.classList.add('word-count');
        element.parentNode.appendChild(wordCountWrapper);
        console.log(element.parentNode);

        const wordCountPlugin = editor.plugins.get( 'WordCount' );

        wordCountWrapper.appendChild( wordCountPlugin.wordCountContainer );
    }, 1);
}