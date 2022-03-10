import $ from 'jquery'
import '../_lib/isotope';

const SELECTOR = '.grid-filter,.custom-filter';

function exec(){
	gridFilter(); 
}
export default { exec };


function gridFilter() {

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('gridFilter executed');

	$elements.each(function() {
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