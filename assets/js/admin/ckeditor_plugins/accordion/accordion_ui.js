import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

const ICON = '<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M542 4781 c-61 -21 -114 -61 -159 -120 -65 -84 -64 -69 -61 -897 l3 -749 23 -46 c37 -75 87 -125 160 -161 l67 -33 1985 0 1985 0 67 33 c73 36 123 86 160 161 l23 46 0 770 0 770 -28 56 c-32 66 -99 132 -165 162 l-47 22 -1980 2 c-1898 2 -1982 2 -2033 -16z m3938 -941 l0 -690 -1920 0 -1920 0 0 690 0 690 1920 0 1920 0 0 -690z"/><path d="M1142 4139 c-65 -42 -89 -131 -54 -200 31 -61 319 -385 359 -404 46 -22 120 -22 167 0 38 18 330 345 360 403 34 66 11 155 -53 198 -42 28 -115 33 -161 9 -16 -8 -74 -66 -129 -129 l-100 -115 -101 115 c-56 63 -114 121 -130 129 -43 22 -118 19 -158 -6z"/><path d="M569 2336 c-98 -26 -173 -89 -221 -186 l-23 -45 0 -770 c0 -768 0 -770 22 -817 30 -66 96 -133 162 -165 l56 -28 1995 0 1995 0 47 22 c66 30 133 96 165 162 l28 56 0 770 0 770 -23 46 c-37 75 -87 125 -160 161 l-67 33 -1965 2 c-1536 1 -1975 -1 -2011 -11z m3911 -994 l0 -689 -772 -6 c-425 -4 -1286 -7 -1914 -7 l-1141 0 -6 272 c-4 149 -7 462 -7 695 l0 423 1920 0 1920 0 0 -688z"/><path d="M1174 1659 c-68 -20 -114 -106 -100 -185 11 -56 321 -413 383 -440 48 -21 114 -17 161 9 47 26 335 352 359 407 39 90 -22 201 -120 217 -72 11 -113 -15 -222 -142 -54 -63 -101 -115 -104 -115 -4 0 -50 51 -104 114 -120 140 -161 162 -253 135z"/></g></svg>';

export default class AccordionItemUI extends Plugin {
    init() {
        const editor = this.editor;
        const t = editor.t;

        // The "accordionItem" button must be registered among the UI components of the editor
        // to be displayed in the toolbar.
        editor.ui.componentFactory.add( 'accordionItem', locale => {
            // The state of the button will be bound to the widget command.
            const command = editor.commands.get( 'insertAccordionItem' );

            // The button will be an instance of ButtonView.
            const buttonView = new ButtonView( locale );

            buttonView.set( {
                // The t() function helps localize the editor. All strings enclosed in t() can be
                // translated and change when the language of the editor changes.
                label: t( 'Accordion Item' ),
                withText: false,
                tooltip: 'Insert an accordion item',
                icon: ICON
            } );

            // Bind the state of the button to the command.
            buttonView.bind( 'isOn', 'isEnabled' ).to( command, 'value', 'isEnabled' );

            // Execute the command when the button is clicked (executed).
            this.listenTo( buttonView, 'execute', () => editor.execute( 'insertAccordionItem' ) );

            return buttonView;
        } );
    }
}
