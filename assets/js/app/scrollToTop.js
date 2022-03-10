import $ from 'jquery';
import 'jquery.easing';
import isMobile from '../app/responsive/isMobile';

const SELECTOR = '#gotoTop';

let $window = $(window);
let $body = $('body');
let $goToTopEl = $(SELECTOR);
let $header = $('#header');
let $pagemenu = $('#page-menu');

function exec() {

    if($goToTopEl.lenght < 1) {
        return;
    }
    console.log('scrollToTop executed');

    onScrollEvent(); 
    goToTop();
    goToTopScroll();
    topScrollOffset();
}

function onScrollEvent() {
    window.addEventListener( 'scroll', function(){
        goToTopScroll();
    }, { passive: true });
}

function goToTop(){
    let elementScrollSpeed = $goToTopEl.attr('data-speed'),
        elementScrollEasing = $goToTopEl.attr('data-easing');

    if( !elementScrollSpeed ) { elementScrollSpeed = 20; }
    if( !elementScrollEasing ) { elementScrollEasing = 'easeOutQuad'; }

    $goToTopEl.off( 'click' ).on( 'click', function() {
        $('body,html').stop(true).animate({
            'scrollTop': 0
        }, Number( elementScrollSpeed ), elementScrollEasing );
        return false;
    });
}

function goToTopScroll(){
    let elementMobile = $goToTopEl.attr('data-mobile'),
        elementOffset = $goToTopEl.attr('data-offset');

    if( !elementOffset ) { elementOffset = 450; }

    if( elementMobile != 'true' && ( $body.hasClass('device-sm') || $body.hasClass('device-xs') ) ) { return; }

    if( $window.scrollTop() > Number(elementOffset) ) {
        $goToTopEl.fadeIn();
        $body.addClass('gototop-active');
    } else {
        $goToTopEl.fadeOut();
        $body.removeClass('gototop-active');
    }
}

function topScrollOffset() {
    let topOffsetScroll = 0;

    if( ( $body.hasClass('device-xl') || $body.hasClass('device-lg') ) && !isMobile.any() ) {
        if( $header.hasClass('sticky-header') ) {
            if( $pagemenu.hasClass('dots-menu') ) { topOffsetScroll = 100; } else { topOffsetScroll = 144; }
        } else {
            if( $pagemenu.hasClass('dots-menu') ) { topOffsetScroll = 140; } else { topOffsetScroll = 184; }
        }

        if( !$pagemenu.length ) {
            if( $header.hasClass('sticky-header') ) { topOffsetScroll = 100; } else { topOffsetScroll = 140; }
        }
    } else {
        topOffsetScroll = 40;
    }

    return topOffsetScroll;
}


export default { 
    exec,
    goToTop,
    goToTopScroll,
    topScrollOffset
};