/*
 * These files are used on EVERY pages of the website
 */

import './sass/_app.scss';

import menueasing from './js/app/menueasing'; menueasing.exec();
import scrollToTop from './js/app/scrollToTop'; scrollToTop.exec();

// responsive
import isMobile from './js/app/responsive/isMobile'; isMobile.exec();
import jrespond from './js/app/responsive/jrespond'; jrespond.exec();