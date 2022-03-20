import 'owl.carousel';
import $ from 'jquery';

const SELECTOR = '.carousel-widget';

function exec(){
	carousel(); 
}
export default { exec };

function carousel() {

	let $elements = $(SELECTOR);

	if( $elements.length < 1 ){
		return;
	}
	console.log('carousel executed');

	$elements.each( function(){
		let element				= $(this),
			elItems				= element.attr('data-items') || 4,
			elItemsXs			= element.attr('data-items-xs') || Number( elItems ),
			elItemsSm			= element.attr('data-items-sm') || Number( elItemsXs ),
			elItemsMd			= element.attr('data-items-md') || Number( elItemsSm ),
			elItemsLg			= element.attr('data-items-lg') || Number( elItemsMd ),
			elItemsXl			= element.attr('data-items-xl') || Number( elItemsLg ),
			elLoop				= element.attr('data-loop'),
			elAutoPlay			= element.attr('data-autoplay'),
			elSpeed				= element.attr('data-speed') || 250,
			elAnimateIn			= element.attr('data-animate-in'),
			elAnimateOut		= element.attr('data-animate-out'),
			elNav				= element.attr('data-nav'),
			elNavPrev			= element.attr('data-nav-prev') || '<i class="icon-angle-left"></i>',
			elNavNext			= element.attr('data-nav-next') || '<i class="icon-angle-right"></i>',
			elPagi				= element.attr('data-pagi'),
			elMargin			= element.attr('data-margin') || 20,
			elStage				= element.attr('data-stage-padding') || 0,
			elMerge				= element.attr('data-merge'),
			elStart				= element.attr('data-start') || 0,
			elRewind			= element.attr('data-rewind'),
			elSlideBy			= element.attr('data-slideby') || 1,
			elCenter			= element.attr('data-center'),
			elLazy				= element.attr('data-lazyload'),
			elVideo				= element.attr('data-video'),
			elRTL				= element.attr('data-rtl'),
			elAutoPlayTime		= 5000,
			elAutoPlayHoverP 	= true;

		if( elSlideBy == 'page' ) {
			elSlideBy = 'page';
		} else {
			elSlideBy = Number(elSlideBy);
		}

		if( elLoop == 'true' ){ elLoop = true; } else { elLoop = false; }
		if( !elAutoPlay ){
			elAutoPlay = false;
			elAutoPlayHoverP = false;
		} else {
			elAutoPlayTime = Number(elAutoPlay);
			elAutoPlay = true;
		}
		if( !elAnimateIn ) { elAnimateIn = false; }
		if( !elAnimateOut ) { elAnimateOut = false; }
		if( elNav == 'false' ){ elNav = false; } else { elNav = true; }
		if( elPagi == 'false' ){ elPagi = false; } else { elPagi = true; }
		if( elRewind == 'true' ){ elRewind = true; } else { elRewind = false; }
		if( elMerge == 'true' ){ elMerge = true; } else { elMerge = false; }
		if( elCenter == 'true' ){ elCenter = true; } else { elCenter = false; }
		if( elLazy == 'true' ){ elLazy = true; } else { elLazy = false; }
		if( elVideo == 'true' ){ elVideo = true; } else { elVideo = false; }
		if( elRTL == 'true' || $('body').hasClass('rtl') ){ elRTL = true; } else { elRTL = false; }

		let carousel = element.owlCarousel({
			margin: Number(elMargin),
			loop: elLoop,
			stagePadding: Number(elStage),
			merge: elMerge,
			startPosition: Number(elStart),
			rewind: elRewind,
			slideBy: elSlideBy,
			center: elCenter,
			lazyLoad: elLazy,
			nav: elNav,
			navText: [elNavPrev,elNavNext],
			autoplay: elAutoPlay,
			autoplayTimeout: elAutoPlayTime,
			autoplayHoverPause: elAutoPlayHoverP,
			dots: elPagi,
			smartSpeed: Number(elSpeed),
			fluidSpeed: Number(elSpeed),
			video: elVideo,
			animateIn: elAnimateIn,
			animateOut: elAnimateOut,
			rtl: elRTL,
			responsive:{
				0:{ items: elItemsXs },
				576:{ items: elItemsSm },
				768:{ items: elItemsMd },
				992:{ items: elItemsLg },
				1200:{ items: elItemsXl }
			},
			onInitialized: function(){
				if( element.find('.owl-dot').length > 0 ) {
					element.addClass('with-carousel-dots');
				}
			}
		});

		$(window).on( 'lazyLoadLoaded', function(){
			if( element.find('.lazy').length == element.find('.lazy.lazy-loaded').length ) {
				lazyLoadInstance.update();
				setTimeout( function(){
					carousel.trigger( 'refresh.owl.carousel' );
				}, 500 );
			}
		});

	});

}