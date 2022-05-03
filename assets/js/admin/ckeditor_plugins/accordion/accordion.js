import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import AccordionEditing from './accordion_editing';
import AccordionUI from './accordion_ui';

export default class Accordion extends Plugin {
    static get requires() {
        return [ AccordionEditing, AccordionUI ];
    }
}