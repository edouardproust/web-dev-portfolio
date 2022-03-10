import $ from 'jquery';
import '../_lib/isotope.js';

const SELECTOR = '.grid-container';

function exec() {
	gridContainerInit();
	gridFilterInit(); 
}
export default { exec };

function gridContainerInit() {

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('gallery::ridContainerInit executed');

	$elements.each( function(){
		let element			= $(this),
			elTransition	= element.attr('data-transition') || '0.65s',
			elLayoutMode	= element.attr('data-layout') || 'masonry',
			elStagger		= element.attr('data-stagger') || 0,
			elBase			= element.attr('data-basewidth') || '.portfolio-item:not(.wide):eq(0)',
			elOriginLeft	= true,
			elGrid;

		if( $('body').hasClass('rtl') ) { elOriginLeft = false; }

		if( element.hasClass('portfolio') || element.hasClass('post-timeline') ){
			elGrid = element.isotope({
				layoutMode: elLayoutMode,
				isOriginLeft: elOriginLeft,
				transitionDuration: elTransition,
				stagger: Number( elStagger ),
				percentPosition: true,
				masonry: {
					columnWidth: element.find( elBase )[0]
				}
			});
		} else {
			elGrid = element.isotope({
				layoutMode: elLayoutMode,
				isOriginLeft: elOriginLeft,
				transitionDuration: elTransition,
				stagger: Number( elStagger ),
				percentPosition: true,
			});
		}

		if( element.data('isotope') ) {
			element.addClass('has-init-isotope');
		}

		let elementInterval = setInterval( function(){
			if( element.find('.lazy.lazy-loaded').length == element.find('.lazy').length ) {
				setTimeout( function(){
					element.filter('.has-init-isotope').isotope('layout');
				}, 800 );
				clearInterval( elementInterval );
			}
		}, 1000);

		let resizeTimer;

		$(window).on( 'resize', function() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function() {
				element.filter('.has-init-isotope').isotope('layout');
			}, 250);
		});

		$(window).on( 'lazyLoadLoaded', function(){
			element.filter('.has-init-isotope').isotope('layout');
		});

	});
}

function gridFilterInit(){

	let $elements = $('.grid-filter,.custom-filter');
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('gallery::gridFilterInit executed');

	$elements.each( function(){
		let element		= $(this),
			elCon		= element.attr('data-container'),
			elActClass	= element.attr('data-active-class'),
			elDefFilter	= element.attr('data-default');

		if( !elActClass ) { elActClass = 'activeFilter'; }

		element.find('a').off( 'click' ).on( 'click', function(){
			element.find('li').removeClass( elActClass );
			$(this).parent('li').addClass( elActClass );
			let selector = $(this).attr('data-filter');
			$(elCon).isotope({ filter: selector });
			return false;
		});

		if( elDefFilter ) {
			element.find('li').removeClass( elActClass );
			element.find('[data-filter="'+ elDefFilter +'"]').parent('li').addClass( elActClass );
			$(elCon).isotope({ filter: elDefFilter });
		}
	});

	$('.grid-shuffle').off( 'click' ).on( 'click', function(){
		let element = $(this),
			elCon = element.attr('data-container');

		$(elCon).isotope('shuffle');
	});
}
