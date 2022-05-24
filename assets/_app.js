/*
 * These files are used on EVERY pages of the website
 */

import './sass/_app.scss';

import menueasing from './js/app/menueasing'; menueasing.exec();
import scrollToTop from './js/app/scrollToTop'; scrollToTop.exec();

// responsive
import isMobile from './js/app/responsive/isMobile'; isMobile.exec();
import jrespond from './js/app/responsive/jrespond'; jrespond.exec();


// Footer links

let footerLinkGroups = document.querySelectorAll('.copyright-links');
footerLinkGroups.forEach((group) => {
    let links = group.querySelectorAll('a');
    links.forEach((link, index) => {
        if(index !== 0) {
            link.innerHTML = '<span class="footer-sep">' + link.innerText + '</span>'; 
        }
    });
});