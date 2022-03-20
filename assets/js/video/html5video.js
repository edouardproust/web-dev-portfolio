import isMobile from '../app/responsive/isMobile';
import $ from 'jquery';

const SELECTOR = '.video-wrap:has(video)';

function exec(){
	html5video(); 
}
export default { exec };

function html5video() {

	let $elements = $(SELECTOR);

	if( $elements.length < 1 ){
		return;
	}
	console.log('html5video executed');

	$elements.each( function(){
		let element = $(this),
			elVideo = element.find('video'),
			divWidth = element.outerWidth(),
			divHeight = element.outerHeight(),
			elWidth = ( (16*divHeight)/9 ),
			elHeight = divHeight;

		if( elWidth < divWidth ) {
			elWidth = divWidth;
			elHeight = ( (9*divWidth)/16 );
		}

		elVideo.css({ width: elWidth+'px', height: elHeight+'px' });

		if( elHeight > divHeight ) {
			elVideo.css({ 'left': '', 'top': -( ( elHeight - divHeight )/2 )+'px' });
		}

		if( elWidth > divWidth ) {
			elVideo.css({ 'top': '', 'left': -( ( elWidth - divWidth )/2 )+'px' });
		}

		if( isMobile.any() && !element.hasClass('no-placeholder') ) {
			let placeholderImg = elVideo.attr('poster');

			if( placeholderImg != '' ) {
				element.append('<div class="video-placeholder" style="background-image: url('+ placeholderImg +');"></div>')
			}

			elVideo.hide();
		}
	});

}