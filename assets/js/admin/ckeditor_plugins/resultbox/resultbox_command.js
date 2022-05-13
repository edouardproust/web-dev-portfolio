import Command from '@ckeditor/ckeditor5-core/src/command';

export default class InsertResultboxCommand extends Command {
    execute() {
        this.editor.model.change( writer => {
            // Insert <resultbox>*</resultbox> at the current selection position
            // in a way that will result in creating a valid model structure.
                this.editor.model.insertContent( createResultbox( writer ) );
        } );
    }

    refresh() {
        const model = this.editor.model;
        const selection = model.document.selection;
        const allowedIn = model.schema.findAllowedParent( selection.getFirstPosition(), 'resultbox' );

        this.isEnabled = allowedIn !== null;
    }
}

function createResultbox( writer ) {
    const resultbox = writer.createElement( 'resultbox' );
    const resultboxContent = writer.createElement( 'resultboxContent' );
    writer.append( resultboxContent, resultbox );
    writer.appendElement( 'paragraph', resultboxContent );

    return resultbox;
}