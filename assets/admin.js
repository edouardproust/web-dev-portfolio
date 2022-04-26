/*
 * Admin side (made withEasyAdmin Bundle)
 */

import './admin.scss';
import ckeditorBuild from './js/admin/ckeditor_config';
import uiHacks from './js/admin/ui_hacks';
import lightbox from './js/gallery/lightbox';

ckeditorBuild.exec();
uiHacks.exec();
lightbox.exec();