import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import { toWidget, toWidgetEditable } from '@ckeditor/ckeditor5-widget/src/utils';
import Widget from '@ckeditor/ckeditor5-widget/src/widget';
import InsertAccordionItemCommand from './accordion_command';  

export default class AccordionItemEditing extends Plugin {
    static get requires() {
        return [ Widget ];
    }

    init() {
        this._defineSchema();
        this._defineConverters();
        this.editor.commands.add( 'insertAccordionItem', new InsertAccordionItemCommand( this.editor ) );
    }

    _defineSchema() {
        const schema = this.editor.model.schema;

        schema.register( 'accordionItem', {
            // Behaves like a self-contained object (e.g. an image).
            isObject: true,

            // Allow in places where other blocks are allowed (e.g. directly in the root).
            allowWhere: '$block'
        } );

        schema.register( 'accordionItemTitle', {
            // Cannot be split or left by the caret.
            isLimit: true,

            allowIn: 'accordionItem',

            // Allow content which is allowed in blocks (i.e. text with attributes).
            allowContentOf: '$block'
        } );

        schema.register( 'accordionItemContent', {
            // Cannot be split or left by the caret.
            isLimit: true,

            allowIn: 'accordionItem',

            // Allow content which is allowed in the root (e.g. paragraphs).
            allowContentOf: '$root'
        } );

        schema.addChildCheck( ( context, childDefinition ) => {
            if ( context.endsWith( 'accordionItemContent' ) && childDefinition.name == 'accordionItem' ) {
                return false;
            }
        } );
    }

    _defineConverters() {
        const conversion = this.editor.conversion;

        // <accordionItem> converters
        conversion.for( 'upcast' ).elementToElement( {
            model: 'accordionItem',
            view: {
                name: 'div',
                classes: 'ck-accordion-item'
            }
        } );
        conversion.for( 'dataDowncast' ).elementToElement( {
            model: 'accordionItem',
            view: {
                name: 'div',
                classes: 'ck-accordion-item'
            }
        } );
        conversion.for( 'editingDowncast' ).elementToElement( {
            model: 'accordionItem',
            view: ( modelElement, { writer: viewWriter } ) => {
                const section = viewWriter.createContainerElement( 'div', { class: 'ck-accordion-item' } );

                return toWidget( section, viewWriter, { label: 'accordion item widget' } );
            }
        } );

        // <accordionItemTitle> converters
        conversion.for( 'upcast' ).elementToElement( {
            model: 'accordionItemTitle',
            view: {
                name: 'div',
                classes: 'accordion-title'
            }
        } );
        conversion.for( 'dataDowncast' ).elementToElement( {
            model: 'accordionItemTitle',
            view: {
                name: 'div',
                classes: 'accordion-title'
            }
        } );
        conversion.for( 'editingDowncast' ).elementToElement( {
            model: 'accordionItemTitle',
            view: ( modelElement, { writer: viewWriter } ) => {
                // Note: You use a more specialized createEditableElement() method here.
                const h1 = viewWriter.createEditableElement( 'h5', { class: 'accordion-title' } );

                return toWidgetEditable( h1, viewWriter );
            }
        } );

        // <accordionItemContent> converters
        conversion.for( 'upcast' ).elementToElement( {
            model: 'accordionItemContent',
            view: {
                name: 'div',
                classes: 'accordion-content'
            }
        } );
        conversion.for( 'dataDowncast' ).elementToElement( {
            model: 'accordionItemContent',
            view: {
                name: 'div',
                classes: 'accordion-content'
            }
        } );
        conversion.for( 'editingDowncast' ).elementToElement( {
            model: 'accordionItemContent',
            view: ( modelElement, { writer: viewWriter } ) => {
                // Note: You use a more specialized createEditableElement() method here.
                const div = viewWriter.createEditableElement( 'div', { class: 'accordion-content' } );

                return toWidgetEditable( div, viewWriter );
            }
        } );
    }
}


