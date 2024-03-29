import 'jquery-countto';
import $ from 'jquery';

const SELECTOR = '.counter';

function exec(element = null){
	if(element) {
		counter(element); 
	} else {
		counterAll();
	}
	
}
export default { exec };

function counter(element) {
	console.log('counter executed');

	let elComma		= element.find('span').attr('data-comma'),
		elSep		= element.find('span').attr('data-sep') || ',',
		elPlaces	= element.find('span').attr('data-places') || 3;

	let elCommaObj	= {
		comma : elComma,
		sep : elSep,
		places : Number( elPlaces )
	}

	if( element.hasClass('counter-instant') ) {
		runCounterInit( element, elCommaObj );
		return;
	}

	let observer = new IntersectionObserver( function(entries, observer) {
		entries.forEach( function(entry) {
			if (entry.isIntersecting) {
				runCounterInit( element, elCommaObj );
				observer.unobserve( entry.target );
			}
		});
	}, {rootMargin: '-50px'});
	observer.observe( element[0] );
}

function counterAll() {
	let elements = $(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	elements.each(function() {
		counter($(this))
	});
}

function runCounterInit (elCounter, elFormat){
	if( elFormat.comma == 'true' ) {
		let reFormat	= '\\B(?=(\\d{'+ elFormat.places +'})+(?!\\d))',
			regExp		= new RegExp( reFormat, "g" );

		elCounter.find('span').countTo({
			formatter: function (value, options) {
				value = value.toFixed( options.decimals );
				value = value.replace( regExp, elFormat.sep );
				return value;
			}
		});
	} else {
		elCounter.find('span').countTo();
	}
};