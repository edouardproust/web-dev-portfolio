import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

const ICON = '<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512"><path d="M10,6H23a1,1,0,0,0,0-2H10a1,1,0,0,0,0,2Z"/><path d="M23,11H10a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2Z"/><path d="M23,18H10a1,1,0,0,0,0,2H23a1,1,0,0,0,0-2Z"/><path d="M6.087,6a.5.5,0,0,0,.353-.854L4,2.707a1,1,0,0,0-1.414,0L.147,5.146A.5.5,0,0,0,.5,6H2.294V18H.5a.5.5,0,0,0-.354.854l2.44,2.439a1,1,0,0,0,1.414,0L6.44,18.854A.5.5,0,0,0,6.087,18H4.294V6Z"/></svg>';

export default class AccordionUI extends Plugin {
    init() {
        const editor = this.editor;
        const t = editor.t;

        // The "accordion" button must be registered among the UI components of the editor
        // to be displayed in the toolbar.
        editor.ui.componentFactory.add( 'accordion', locale => {
            // The state of the button will be bound to the widget command.
            const command = editor.commands.get( 'insertAccordion' );

            // The button will be an instance of ButtonView.
            const buttonView = new ButtonView( locale );

            buttonView.set( {
                // The t() function helps localize the editor. All strings enclosed in t() can be
                // translated and change when the language of the editor changes.
                label: t( 'Accordion' ),
                withText: false,
                tooltip: 'Insert accordion',
                icon: ICON
            } );

            // Bind the state of the button to the command.
            buttonView.bind( 'isOn', 'isEnabled' ).to( command, 'value', 'isEnabled' );

            // Execute the command when the button is clicked (executed).
            this.listenTo( buttonView, 'execute', () => editor.execute( 'insertAccordion' ) );

            return buttonView;
        } );
    }
}