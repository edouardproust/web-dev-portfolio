import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ResultboxEditing from './resultbox_editing';
import ResultboxUI from './resultbox_ui';

import './resultbox.css';

export default class Resultbox extends Plugin {
    static get requires() {
        return [ ResultboxEditing, ResultboxUI ];
    }
}