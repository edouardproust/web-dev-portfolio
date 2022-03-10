import $ from 'jquery';

const SELECTOR = '.toggle';

function exec() {
	toggle(); 
}
export default { exec };


function toggle() {

	let defaultDataSpeed = 300;

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('toggle executed');

	$elements.each( function(){
		let element = $(this),
			elSpeed = element.attr('data-speed') || defaultDataSpeed,
			elState = element.attr('data-state');

		if( elState != 'open' ){
			element.children('.toggle-content').hide();
		} else {
			element.addClass('toggle-active').children('.toggle-content').slideDown( Number(elSpeed) );
		}

		element.children('.toggle-header').off( 'click' ).on( 'click', function(){
			element.toggleClass('toggle-active').children('.toggle-content').slideToggle( Number(elSpeed) );
			return;
		});
	});

};

