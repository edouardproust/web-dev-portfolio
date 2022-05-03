import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import SimpleBoxEditing from './simplebox_editing';
import SimpleBoxUI from './simplebox_ui';

export default class SimpleBox extends Plugin {
    static get requires() {
        return [ SimpleBoxEditing, SimpleBoxUI ];
    }
}