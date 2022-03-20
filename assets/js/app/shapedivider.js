import $ from 'jquery';

const SELECTOR = '.shape-divider';

function exec(){
	shapeDivider();
}
export default { exec };


function shapeDivider() {

	let elements = $(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	console.log('shapeDivider executed');
	
	elements.each( function(){
		let element	= $(this),
		elShape		= element.attr('data-shape') || 'valley',
		elWidth		= element.attr('data-width') || 100,
		elHeight	= element.attr('data-height') || 100,
		elFill		= element.attr('data-fill'),
		elOut		= element.attr('data-outside') || 'false',
		elPos		= element.attr('data-position') || 'top',
		elId		= 'shape-divider-' + Math.floor( Math.random() * 10000 ),
		shape		= '',
		width, height, fill,
		outside		= '';

		if( elWidth < 100 ) {
			elWidth = 100;
		}

		width	= 'width: calc( '+ Number( elWidth ) +'% + 1.5px );';
		height	= 'height: '+ Number( elHeight ) +'px;';
		fill	= 'fill: '+elFill+';';

		if( elOut == 'true' ) {
			if( elPos == 'bottom' ) {
				outside = '#'+ elId +'.shape-divider { bottom: -'+( Number( elHeight ) - 1 ) +'px; } ';
			} else {
				outside = '#'+ elId +'.shape-divider { top: -'+( Number( elHeight ) - 1 ) +'px; } ';
			}
		}

		let css = outside + '#'+ elId +'.shape-divider svg { '+ width + height +' } #'+ elId +'.shape-divider .shape-divider-fill { '+ fill +' }',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');

		head.appendChild(style);

		style.type = 'text/css';
		style.appendChild(document.createTextNode(css));

		element.attr( 'id', elId );

		switch( elShape ){

			// Add more shapes: https://themes.semicolonweb.com/html/canvas/shape-dividers.html
			
			case 'slant':
				shape = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path class="shape-divider-fill" d="M0,6V0h1000v100L0,6z"></path></svg>';
				break;

			default:
				shape = '';
				break;

		}

		element.html( shape );
		element.find('svg').addClass( 'op-ts' );
		element.find('svg').addClass( 'op-1' );

	});
}