import Command from '@ckeditor/ckeditor5-core/src/command';

export default class InsertAccordionCommand extends Command {
    execute() {
        this.editor.model.change( writer => {
            // Insert <accordion>*</accordion> at the current selection position
            // in a way that will result in creating a valid model structure.
            this.editor.model.insertContent( createAccordion( writer ) );
        } );
    }

    refresh() {
        const model = this.editor.model;
        const selection = model.document.selection;
        const allowedIn = model.schema.findAllowedParent( selection.getFirstPosition(), 'accordion' );

        this.isEnabled = allowedIn !== null;
    }
}

function createAccordion( writer ) {
    const accordion = writer.createElement( 'accordion' );
    const accordionTitle = writer.createElement( 'accordionTitle' );
    const accordionDescription = writer.createElement( 'accordionDescription' );

    writer.append( accordionTitle, accordion );
    writer.append( accordionDescription, accordion );

    // There must be at least one paragraph for the description to be editable.
    // See https://github.com/ckeditor/ckeditor5/issues/1464.
    writer.appendElement( 'paragraph', accordionDescription );

    return accordion;
}