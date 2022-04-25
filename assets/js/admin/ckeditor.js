/**
 * Documentation:
 * - Editor config: https://ckeditor.com/docs/ckeditor5/latest/api/module_core_editor_editorconfig-EditorConfig.html
*/

export default { exec };

import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
// Built-in plugins
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import AutoImage from '@ckeditor/ckeditor5-image/src/autoimage';
import AutoLink from '@ckeditor/ckeditor5-link/src/autolink.js';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote.js';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Code from '@ckeditor/ckeditor5-basic-styles/src/code.js';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
// Extra plugins (not on build / added by me with `npm run install`)
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import CodeBlock from '@ckeditor/ckeditor5-code-block/src/codeblock.js';
import WordCount from '@ckeditor/ckeditor5-word-count/src/wordcount';
// Build editors
import buildEditors from './ckeditor_build';

ClassicEditor.builtinPlugins = [ 
    // toolbar buttons
    Alignment,
    BlockQuote,
    Bold, 
    Code,
    CodeBlock,
    Italic,
    // no toolbar btn   
    Autoformat,
    AutoImage,
    AutoLink,
    Essentials,
    Paragraph,     
    // extras plugins w/ no toolbar btn
    WordCount,
];

const EDITOR_CONFIG = {
    toolbar: [ 'bold', 'italic', 'alignment', 'blockQuote', 'code', 'codeBlock' ],
    wordCount: {
        displayWords: false
    },
}

function exec() {
    buildEditors(ClassicEditor, EDITOR_CONFIG);
}
