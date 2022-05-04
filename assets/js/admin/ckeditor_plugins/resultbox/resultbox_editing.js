import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import { toWidget, toWidgetEditable } from '@ckeditor/ckeditor5-widget/src/utils';
import Widget from '@ckeditor/ckeditor5-widget/src/widget';
import InsertResultboxCommand from './resultbox_command';  

export default class ResultboxEditing extends Plugin {
    static get requires() {
        return [ Widget ];
    }

    init() {
        this._defineSchema();
        this._defineConverters();
        this.editor.commands.add( 'insertResultbox', new InsertResultboxCommand( this.editor ) );
    }

    _defineSchema() {
        const schema = this.editor.model.schema;

        schema.register( 'resultbox', {
            isObject: true,
            allowWhere: '$block'
        } );

        schema.register( 'resultboxContent', {
            isLimit: true,
            allowIn: 'resultbox',
            allowContentOf: '$root'
        } );

        schema.addChildCheck( ( context, childDefinition ) => {
            if ( context.endsWith( 'resultboxContent' ) && childDefinition.name == 'resultbox' ) {
                return false;
            }
        } );
    }

    _defineConverters() {
        const conversion = this.editor.conversion;

        // <resultbox> converters
        conversion.for( 'upcast' ).elementToElement( {
            model: 'resultbox',
            view: {
                name: 'div',
                classes: 'resultbox-container'
            }
        } );
        conversion.for( 'dataDowncast' ).elementToElement( {
            model: 'resultbox',
            view: {
                name: 'div',
                classes: 'resultbox-container'
            }
        } );
        conversion.for( 'editingDowncast' ).elementToElement( {
            model: 'resultbox',
            view: ( modelElement, { writer: viewWriter } ) => {
                const section = viewWriter.createContainerElement( 'div', { class: 'ck-resultbox-container' } );

                return toWidget( section, viewWriter, { label: 'resultbox widget' } );
            }
        } );

        // <resultboxContent> converters
        conversion.for( 'upcast' ).elementToElement( {
            model: 'resultboxContent',
            view: {
                name: 'div',
                classes: 'resultbox-content'
            }
        } );
        conversion.for( 'dataDowncast' ).elementToElement( {
            model: 'resultboxContent',
            view: {
                name: 'div',
                classes: 'resultbox-content'
            }
        } );
        conversion.for( 'editingDowncast' ).elementToElement( {
            model: 'resultboxContent',
            view: ( modelElement, { writer: viewWriter } ) => {
                // Note: You use a more specialized createEditableElement() method here.
                const div = viewWriter.createEditableElement( 'div', { class: 'ck-resultbox-content' } );

                return toWidgetEditable( div, viewWriter );
            }
        } );
    }
}


