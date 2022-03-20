import isMobile from '../app/responsive/isMobile';

const SELECTOR = '.slider-parallax';

let $body = $('body');
let $slider = $('#slider');
let $sliderParallaxEl = $(SELECTOR);

function exec() {

    if($sliderParallaxEl.length < 1) {
        return;
    }

    sliderDimensions(); console.log('slider::sliderDimensions executed');
    // sliderRun();
    sliderParallax(); console.log('slider::sliderParallax executed');
    sliderElementsFade(); console.log('slider::sliderElementsFade executed');

}
export default {
    exec,
    onScrollSliderParallax,
    sliderDimensions,
    // sliderRun,
    sliderParallax,
    sliderElementsFade
}

function onScrollSliderParallax() {
	sliderParallax();
	sliderElementsFade();
}

function sliderDimensions(){

    let parallaxElHeight	= $sliderParallaxEl.outerHeight(),
        parallaxElWidth		= $sliderParallaxEl.outerWidth(),
        slInner				= $sliderParallaxEl.find('.slider-inner'),
        slSwiperW			= $slider.find('.swiper-wrapper'),
        slSwiperS			= $slider.find('.swiper-slide').first(),
        slFlexHeight		= $slider.hasClass('h-auto') || $slider.hasClass('min-vh-0');

    if( $body.hasClass('device-xl') || $body.hasClass('device-lg') ) {
        setTimeout( function(){
            slInner.height( parallaxElHeight );
            if( slFlexHeight ) {
                parallaxElHeight = $sliderParallaxEl.find('.slider-inner').children().first().outerHeight();
                $sliderParallaxEl.height( parallaxElHeight );
                slInner.height( parallaxElHeight );
            }
        }, 500 );

        if( slFlexHeight ) {
            let slSwiperFC = slSwiperS.children().first();
            if( slSwiperFC.hasClass('container') || slSwiperFC.hasClass('container-fluid') ) {
                slSwiperFC = slSwiperFC.children().first();
            }
            if( slSwiperFC.outerHeight() > slSwiperW.outerHeight() ) {
                slSwiperW.css({ 'height': 'auto' });
            }
        }

        if( $body.hasClass('side-header') ) {
            slInner.width( parallaxElWidth );
        }

        if( !$body.hasClass('stretched') ) {
            parallaxElWidth = $wrapper.outerWidth();
            slInner.width( parallaxElWidth );
        }
    } else {
        slSwiperW.css({ 'height': '' });
        $sliderParallaxEl.css({ 'height': '' });
        slInner.css({ 'width': '', 'height': '' });
    }
}

function sliderParallaxOffset(){
    let sliderParallaxOffsetTop = 0,
        headerHeight = $header.outerHeight();
    if( $body.hasClass('side-header') || $header.next('.include-header').length > 0 ) { headerHeight = 0; }
    if( $pageTitle.length > 0 ) {
        sliderParallaxOffsetTop = $pageTitle.outerHeight() + headerHeight;
    } else {
        sliderParallaxOffsetTop = headerHeight;
    }

    if( $slider.next('#header').length > 0 ) { sliderParallaxOffsetTop = 0; }

    return sliderParallaxOffsetTop;
}

function sliderParallaxSet( xPos, yPos, el ){
    if( el ) {
        el.style.transform = "translate3d(" + xPos + ", " + yPos + "px, 0)";
    }
}

function sliderParallax(){
    if( $sliderParallaxEl.length < 1 ) {
        return;
    }

    let parallaxOffsetTop = sliderParallaxOffset(),
        parallaxElHeight = $sliderParallaxEl.outerHeight(),
        transform, transform2;

    xScrollPosition = window.pageXOffset;
    yScrollPosition = window.pageYOffset;

    if( ( $body.hasClass('device-xl') || $body.hasClass('device-lg') ) && !isMobile.any() ) {
        if( ( parallaxElHeight + parallaxOffsetTop + 50 ) > yScrollPosition ){
            $sliderParallaxEl.addClass('slider-parallax-visible').removeClass('slider-parallax-invisible');
            if ( yScrollPosition > parallaxOffsetTop ) {
                if( $sliderParallaxEl.find('.slider-inner').length > 0 ) {

                    transform = ((yScrollPosition-parallaxOffsetTop) * -.4 );
                    transform2 = ((yScrollPosition-parallaxOffsetTop) * -.15 );

                    sliderParallaxSet( 0, transform, sliderParallaxElInner );
                    sliderParallaxSet( 0, transform2, sliderParallaxElCaption );
                } else {
                    transform = ((yScrollPosition-parallaxOffsetTop) / 1.5 );
                    transform2 = ((yScrollPosition-parallaxOffsetTop) / 7 );

                    sliderParallaxSet( 0, transform, sliderParallaxEl );
                    sliderParallaxSet( 0, transform2, sliderParallaxElCaption );
                }
            } else {
                if( $sliderParallaxEl.find('.slider-inner').length > 0 ) {
                    sliderParallaxSet( 0, 0, sliderParallaxElInner );
                    sliderParallaxSet( 0, 0, sliderParallaxElCaption );
                } else {
                    sliderParallaxSet( 0, 0, sliderParallaxEl );
                    sliderParallaxSet( 0, 0, sliderParallaxElCaption );
                }
            }
        } else {
            $sliderParallaxEl.addClass('slider-parallax-invisible').removeClass('slider-parallax-visible');
        }

        requestAnimationFrame(function(){
            sliderParallax();
            sliderElementsFade();
        });
    } else {
        if( $sliderParallaxEl.find('.slider-inner').length > 0 ) {
            sliderParallaxSet( 0, 0, sliderParallaxElInner );
            sliderParallaxSet( 0, 0, sliderParallaxElCaption );
        } else {
            sliderParallaxSet( 0, 0, sliderParallaxEl );
            sliderParallaxSet( 0, 0, sliderParallaxElCaption );
        }
        $sliderParallaxEl.addClass('slider-parallax-visible').removeClass('slider-parallax-invisible');
    }
}

function sliderElementsFade(){
    if( $sliderParallaxEl.length < 1 ) {
        return;
    }

    if( ( $body.hasClass('device-xl') || $body.hasClass('device-lg') ) && !isMobile.any() ) {
        let parallaxOffsetTop = sliderParallaxOffset(),
            parallaxElHeight = $sliderParallaxEl.outerHeight(),
            tHeaderOffset;

        if( $header.hasClass('transparent-header') || $body.hasClass('side-header') ) {
            tHeaderOffset = 100;
        } else {
            tHeaderOffset = 0;
        }
        $sliderParallaxEl.filter('.slider-parallax-visible').find('.slider-arrow-left,.slider-arrow-right,.slider-caption,.slider-element-fade').css({'opacity': 1 - ( ( ( yScrollPosition - tHeaderOffset ) * 1.85 ) / parallaxElHeight ) });
    } else {
        $sliderParallaxEl.find('.slider-arrow-left,.slider-arrow-right,.slider-caption,.slider-element-fade').css({'opacity': 1});
    }
}
