import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import AccordionItemEditing from './accordion_editing';
import AccordionItemUI from './accordion_ui';

export default class AccordionItem extends Plugin {
    static get requires() {
        return [ AccordionItemEditing, AccordionItemUI ];
    }
}