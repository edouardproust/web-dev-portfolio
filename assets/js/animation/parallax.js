import skrollr from 'skrollr';
import isMobile from '../app/responsive/isMobile';
import $ from 'jquery';

const SELECTOR = '.parallax,.page-title-parallax,.portfolio-parallax .portfolio-image';

function exec(){
	parallax(); 
}
export default { exec };

function parallax() {

	let elements = $(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	console.log('parallax executed');

	if( !isMobile.any() ){
		window.skrollrInstance = skrollr.init({forceHeight: false});
	} else {
		elements.addClass('mobile-parallax');
	}

}