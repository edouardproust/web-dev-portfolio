import $ from 'jquery';
import '../_lib/isotope.js';

const SELECTOR = '.grid-container';

function exec() {
	gridContainerInit();
}
export default { exec };

function gridContainerInit() {

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('gallery::gridContainerInit executed');

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

		let elementInterval = setInterval( function(){
			if( element.find('.lazy.lazy-loaded').length == element.find('.lazy').length ) {
				setTimeout( function(){
					element.isotope('layout');
				}, 800 );
				clearInterval( elementInterval );
			}
		}, 1000);

		let resizeTimer;
	});
}