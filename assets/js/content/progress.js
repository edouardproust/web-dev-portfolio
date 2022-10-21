import counter from './counter';
import $ from 'jquery';

const SELECTOR = '.progress';

function exec(){
	progress(); 
}
export default { exec };


function progress() {

	let elements = $(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	console.log('progress executed');

	elements.each(function(){

		let element	= $(this),
			elBar	= element.parent('li'),
			elValue	= elBar.attr('data-percent');

		if( element.parent('.kv-upload-progress').length > 0 || element.children('.progress-bar').length > 0 ) {
			return true;
		}

		let observer = new IntersectionObserver( function(entries, observer){
			entries.forEach( function(entry){
				if (entry.isIntersecting) {
					if (!elBar.hasClass('skills-animated')) {
						console.log('ELEMENT PROGRESS.JS:', element.find('.counter-instant'))
						counter.exec(element.find('.counter-instant'));
						elBar.find('.progress').css({width: elValue + "%"}).addClass('skills-animated');
					}
					observer.unobserve( entry.target );
				}
			});
		}, {rootMargin: '-50px'});
		observer.observe( elBar[0] );
	});

}