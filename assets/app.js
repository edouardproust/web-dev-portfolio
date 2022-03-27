/*
 * These files are used on EVERY pages of the website
 */

// style
    import './app.scss';
// scripts

    import menueasing from './js/app/menueasing';
    import scrollToTop from './js/app/scrollToTop';
    import shapedivider from './js/app/shapedivider';

    // performance
    // - lazyload: https://themes.semicolonweb.com/html/canvas/lazy-loading.html

    // responsive
    import isMobile from './js/app/responsive/isMobile';
    import jrespond from './js/app/responsive/jrespond';

    // load libraries
    import 'bootstrap';

    // exec

    menueasing.exec();
    scrollToTop.exec();
    shapedivider.exec();
    isMobile.exec();
    jrespond.exec();

    // custom scripts

    import './js/snippets';
