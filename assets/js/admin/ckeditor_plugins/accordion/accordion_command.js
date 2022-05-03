import Command from '@ckeditor/ckeditor5-core/src/command';

export default class InsertAccordionItemCommand extends Command {
    execute() {
        this.editor.model.change( writer => {
            // Insert <accordionItem>*</accordionItem> at the current selection position
            // in a way that will result in creating a valid model structure.
                this.editor.model.insertContent( createAccordionItem( writer ) );
        } );
    }

    refresh() {
        const model = this.editor.model;
        const selection = model.document.selection;
        const allowedIn = model.schema.findAllowedParent( selection.getFirstPosition(), 'accordionItem' );

        this.isEnabled = allowedIn !== null;
    }
}

function createAccordionItem( writer ) {
    const accordionItem = writer.createElement( 'accordionItem' );
    const accordionItemTitle = writer.createElement( 'accordionItemTitle' );
    const accordionItemContent = writer.createElement( 'accordionItemContent' );

    writer.append( accordionItemTitle, accordionItem );
    writer.append( accordionItemContent, accordionItem );

    // There must be at least one paragraph for the content to be editable.
    // See https://github.com/ckeditor/ckeditor5/issues/1464.
    writer.appendElement( 'paragraph', accordionItemContent );

    return accordionItem;
}