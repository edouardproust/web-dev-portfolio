import $ from 'jquery';

import '../_lib/morphext';
import Typed from 'typed.js';

const SELECTOR = '.text-rotater';

function exec() {
	textRotator(); 
}
export default { exec };


function textRotator() {

	let defaultDataSpeed = 1200;

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('textRotator executed');

	$elements.each( function(){
		let element		= $(this),
			elTyped		= element.attr('data-typed') || 'true',
			elRotator	= element.find('.t-rotate'),
			elAnimation	= element.attr('data-rotate') || 'fade',
			elSpeed		= element.attr('data-speed') || defaultDataSpeed,
			elSep		= element.attr('data-separator') || '|';
		if( elTyped == 'true' ) {
			let elTexts		= element.find('.t-rotate-texts').html().split( elSep ),
				elLoop		= element.attr('data-loop') || 'true',
				elShuffle	= element.attr('data-shuffle'),
				elCur		= element.attr('data-cursor') || 'true',
				elSpeed 	= element.attr('data-speed') || 50,
				elBackSpeed	= element.attr('data-backspeed') || 30,
				elBackDelay	= element.attr('data-backdelay') || 1000;

			if( elLoop == 'true' ) { elLoop = true; } else { elLoop = false; }
			if( elShuffle == 'true' ) { elShuffle = true; } else { elShuffle = false; }
			if( elCur == 'true' ) { elCur = true;} else { elCur = false; }

			elRotator.html( '' ).addClass('plugin-typed-init');
			
			let typed = new Typed( elRotator.get(0) , {
				strings: elTexts,
				typeSpeed: Number( elSpeed ),
				loop: elLoop,
				shuffle: elShuffle,
				showCursor: elCur,
				backSpeed: Number( elBackSpeed ),
				backDelay: Number( elBackDelay )
			});
		} else {
			let pluginData = elRotator.Morphext({
				animation: elAnimation,
				separator: elSep,
				speed: Number(elSpeed)
			});
		}
	});

};
