/**
 * @info Build config options: https://ckeditor.com/docs/ckeditor5/latest/api/module_core_editor_editorconfig-EditorConfig.html
*/
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor.js';

import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment.js';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat.js';
import AutoImage from '@ckeditor/ckeditor5-image/src/autoimage.js';
import AutoLink from '@ckeditor/ckeditor5-link/src/autolink.js';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote.js';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold.js';
import Code from '@ckeditor/ckeditor5-basic-styles/src/code.js';
import CodeBlock from '@ckeditor/ckeditor5-code-block/src/codeblock.js';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials.js';
import FindAndReplace from '@ckeditor/ckeditor5-find-and-replace/src/findandreplace.js';
import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor.js';
import FontSize from '@ckeditor/ckeditor5-font/src/fontsize.js';
import Heading from '@ckeditor/ckeditor5-heading/src/heading.js';
import HorizontalLine from '@ckeditor/ckeditor5-horizontal-line/src/horizontalline.js';
import Image from '@ckeditor/ckeditor5-image/src/image.js';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption.js';
import ImageInsert from '@ckeditor/ckeditor5-image/src/imageinsert.js';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize.js';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle.js';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar.js';
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload.js';
import ImageResizeEditing from '@ckeditor/ckeditor5-image/src/imageresize/imageresizeediting.js';
import ImageResizeHandles from '@ckeditor/ckeditor5-image/src/imageresize/imageresizehandles.js';
// import Indent from '@ckeditor/ckeditor5-indent/src/indent.js';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic.js';
import Link from '@ckeditor/ckeditor5-link/src/link.js';
import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage.js';
import List from '@ckeditor/ckeditor5-list/src/list.js';
import ListProperties from '@ckeditor/ckeditor5-list/src/listproperties.js';
import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed.js';
import MediaEmbedToolbar from '@ckeditor/ckeditor5-media-embed/src/mediaembedtoolbar.js';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph.js';
import RemoveFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat.js';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting.js';
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough.js';
import Table from '@ckeditor/ckeditor5-table/src/table.js';
import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';
import TableProperties from '@ckeditor/ckeditor5-table/src/tableproperties';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar.js';
import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation.js';
import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline.js';
import WordCount from '@ckeditor/ckeditor5-word-count/src/wordcount';

class Editor extends ClassicEditor {}

// Plugins to include in the build.
Editor.builtinPlugins = [
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	BlockQuote,
	Bold,
	Code,
	CodeBlock,
	Essentials,
	FindAndReplace,
	FontColor,
	FontSize,
	Heading,
	HorizontalLine,
	Image,
	ImageCaption,
	ImageInsert,
	ImageResize,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	ImageResizeEditing,
	ImageResizeHandles,
	// Indent,
	Italic,
	Link,
	LinkImage,
	List,
	ListProperties,
	MediaEmbed,
	// MediaEmbedToolbar,
	Paragraph,
	RemoveFormat,
	SourceEditing,
	Strikethrough,
	Table,
	TableCellProperties,
	TableProperties,
	TableToolbar,
	TextTransformation,
	Underline,
	WordCount
];

// Editor configuration.
Editor.defaultConfig = {
	// toolbar - https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html
	toolbar: {
		shouldNotGroupWhenFull: true,
		items: [
			'sourceEditing',
			'undo',
			'redo',
			'|',
			'heading',
			'|',
			'alignment',
			'bold',
			'italic',
			// 'removeFormat',
			// '|',
			// 'outdent',
			// 'indent',
			'|',
			'imageInsert',
			// 'imageUpload',
			'codeBlock',
			'code',
			'bulletedList',
			'numberedList',
			'|',
			'link',
			'insertTable',
			'mediaEmbed',
			'blockQuote',
			'horizontalLine',
			'|',
			'strikethrough',
			'underline',
			'fontSize',
			'fontColor',
			'|',
			'findAndReplace'
		]
	},
	language: 'en',
	image: {
		resizeUnit: "%",
		resizeOptions: [ {
			name: 'resizeImage:original',
			value: null
		},{
			name: 'resizeImage:100',
			value: '100'
		},{
			name: 'resizeImage:75',
			value: '75'
		},{
			name: 'resizeImage:50',
			value: '50'
		},{
			name: 'resizeImage:25',
			value: '25'
		} ],
		toolbar: [
			'linkImage',
            'toggleImageCaption',
            'imageTextAlternative',
			// 'imageStyle:inline',
			{
                name: 'imageStyle:alignBlockDropdown',
				title: 'Align images as a block',
                items: [ 'imageStyle:alignBlockLeft', 'imageStyle:alignBlockRight' ],
                defaultItem: 'imageStyle:alignBlockLeft'
            },{
                name: 'imageStyle:alignDropdown',
				title: 'Align image surrounded by text',
                items: [ 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight' ],
                defaultItem: 'imageStyle:alignCenter'
            },
			'ResizeImage'
		],
	},
	codeBlock: {
        languages: [
            { language: 'html', label: 'HTML' },
            { language: 'css', label: 'CSS' },
            { language: 'php', label: 'PHP' },
            { language: 'javascript', label: 'JavaScript' },
        ]
    },
    wordCount: {
        displayWords: false
    },
	table: {
		contentToolbar: [
			'tableColumn',
			'tableRow',
			'mergeTableCells',
			'tableCellProperties',
			'tableProperties'
		]
	}
};

export default Editor;