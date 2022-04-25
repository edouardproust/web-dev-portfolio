/**
 * Documentation:
 * - Editor config: https://ckeditor.com/docs/ckeditor5/latest/api/module_core_editor_editorconfig-EditorConfig.html
*/

export default { exec };

/** Import Features scripts */

    import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
    // Built-in plugins
    import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
    import AutoImage from '@ckeditor/ckeditor5-image/src/autoimage';
    import AutoLink from '@ckeditor/ckeditor5-link/src/autolink';
    import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote';
    import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
    import Code from '@ckeditor/ckeditor5-basic-styles/src/code';
    import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
    import Heading from '@ckeditor/ckeditor5-heading/src/heading';
    import Image from '@ckeditor/ckeditor5-image/src/image';
    import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
    import ImageTextAlternative from '@ckeditor/ckeditor5-image/src/imagetextalternative'
    import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize.js';
    import ImageInsert from '@ckeditor/ckeditor5-image/src/imageinsert';
    import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
    import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
    import Link from '@ckeditor/ckeditor5-link/src/link.js';
    import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage.js';
    import List from '@ckeditor/ckeditor5-list/src/list.js';
    import ListProperties from '@ckeditor/ckeditor5-list/src/listproperties.js';
    import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed.js';
    import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
    import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough.js';
    import Table from '@ckeditor/ckeditor5-table/src/table.js';
    import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';
    import TableProperties from '@ckeditor/ckeditor5-table/src/tableproperties';
    import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar.js';
    import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation.js';
    import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline.js';

    // Extra plugins (not on build / added by me with `npm run install`)

    import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
    import CodeBlock from '@ckeditor/ckeditor5-code-block/src/codeblock';
    import FindAndReplace from '@ckeditor/ckeditor5-find-and-replace/src/findandreplace';
    import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor';
    import FontSize from '@ckeditor/ckeditor5-font/src/fontsize';
    import HorizontalLine from '@ckeditor/ckeditor5-horizontal-line/src/horizontalline';
    import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting.js';
    import WordCount from '@ckeditor/ckeditor5-word-count/src/wordcount';

/** Load features */

    ClassicEditor.builtinPlugins = [
        Autoformat, // markdown
        AutoImage, // recognize images in the input
        AutoLink, // recognizes links in the input field
        BlockQuote,
        Bold, 
        Code,
        Essentials,
        Italic,
        Heading,
        ImageInsert, // insert image from upload or url
        Link,
        Image, // show images inside textarea
        ImageCaption,// inline toolbar btn
        ImageResize, // Inline tooblar dropdown
        ImageToolbar, // inline toolbar (container)
        ImageTextAlternative, // inline toolbar btn
        LinkImage, // inline toolbar btn
        List,
        ListProperties,
        MediaEmbed,
        Paragraph,
        Strikethrough,
        Table,
        TableCellProperties,
        TableProperties,
        TableToolbar,
        TextTransformation,
        Underline,
        // Extra plugins (added to package.json)
        Alignment,
        CodeBlock,
        FindAndReplace,
        FontColor,
        FontSize,
        HorizontalLine,
        SourceEditing,
        WordCount
    ];

/** Config tools & toolbars in the editor */

    const TOOLS_CONFIG = {
        // Buttons order
        toolbar: {
            items: [ 
                'undo', 'redo', 
                '|', 'heading', 'alignment',  'fontSize', 'fontColor',
                '|', 'bold', 'italic', 'code', 'underline', 'strikethrough',
                '|', 'link', 'codeBlock', 'imageInsert', 'mediaEmbed', 'blockQuote', 'horizontalLine', 'insertTable',
                '|', 'bulletedList', 'numberedList',
                '|', 'findAndReplace', 'sourceEditing'
            ],
            shouldNotGroupWhenFull: true,
        },
        // Features config
        wordCount: {
            displayWords: false
        },
        list: {
            properties: {
                styles: true,
                startIndex: true,
                reversed: true
            }
        },
        codeBlock: {
            languages: [
                { language: 'html', label: 'HTML' },
                { language: 'css', label: 'CSS' },
                { language: 'php', label: 'PHP' },
                { language: 'javascript', label: 'JavaScript' },
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells',
                'tableCellProperties',
                'tableProperties'
            ]
        },
        image: {
            resizeUnit: "%",
            resizeOptions: [ 
                { name: 'resizeImage:original', value: null },
                { name: 'resizeImage:100', value: '100' },
                { name: 'resizeImage:75', value: '75' },
                { name: 'resizeImage:50', value: '50' },
                { name: 'resizeImage:25', value: '25' } 
            ],
            toolbar: [
                'linkImage',
                'toggleImageCaption',
                'imageTextAlternative',
                'ResizeImage'
            ],
        },
        // Headings : https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#heading-levels
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' }
            ]
        }
    }

/** Build all editors */

import buildEditors from './ckeditor_build';

function exec() {
    buildEditors(ClassicEditor, TOOLS_CONFIG);
}
