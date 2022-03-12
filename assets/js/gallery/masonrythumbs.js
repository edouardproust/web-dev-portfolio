import $ from 'jquery';

const SELECTOR = '.masonry-thumbs';

function exec(){
	masonryThumbs(); 
}
export default { exec };

function masonryThumbs() {

	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');
	
	if( $elements.length < 1 ){
		return;
	}
	console.log('masonryThumbs executed');

	$elements.each( function(){
		let element = $(this),
			elBig = element.attr('data-big');

		element.children().css({ 'width': '' });

		let firstElementWidth = element.children().eq(0).outerWidth();

		element.isotope({
			masonry: {
				columnWidth: firstElementWidth
			}
		});

		if( elBig ) {
			elBig = elBig.split(",");
			let elBigNum = '',
				bigi = '';
			for( bigi = 0; bigi < elBig.length; bigi++ ){
				elBigNum = Number(elBig[bigi]) - 1;
				element.children().eq(elBigNum).addClass('grid-item-big');
			}
		}

		element.find('.grid-item-big').css({ width: firstElementWidth*2 + 'px' });

		setTimeout( function(){
			element.isotope( 'layout' );
		}, 500);
	});

}