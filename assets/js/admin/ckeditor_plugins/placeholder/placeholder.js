import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import PlaceholderEditing from './placeholder_editing';
import PlaceholderUI from './placeholder_ui';

import './placeholder.css';

export default class Placeholder extends Plugin {
    static get requires() {
        return [ PlaceholderEditing, PlaceholderUI ];
    }
}