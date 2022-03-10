import $ from 'jquery';
import '../_lib/jquery-ui';

const SELECTOR = '.tabs,[data-plugin="tabs"]';

function exec() {
	buildTabs(); 
}


function buildTabs() {
	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');

	if( $elements.length < 1 ){
		return;
	}
	console.log('tabs::buildTabs executed');

	$elements.each( function(){
		let element		= $(this),
			elAction	= element.attr('data-action') || 'click',
			elSpeed		= element.attr('data-speed') || 400,
			elActive	= element.attr('data-active') || 1;

		elActive = elActive - 1;

		let windowHash = window.location.hash;
		if( $(windowHash).length > 0 && element.find(windowHash).length > 0 ) {
			elActive = $( windowHash ).index();
		}

		element.tabs({
			event: elAction,
			active: Number(elActive),
			show: {
				effect: 'fade',
				duration: Number(elSpeed)
			},
			activate: function( event, ui ) {
				$(ui.newPanel).find( '.flexslider .slide' ).resize();
			}
		});

		tabsResponsive( element );
		tabsResponsiveResizeInit( element );

		$(window).on( 'scwWindowResize', function() {
			tabsResponsiveResizeInit( element );
		});
	});

}

function tabsResponsive($tabsEl){

	$tabsEl = $tabsEl.filter('.tabs-responsive');

	if( $tabsEl.length < 1 ){
		return;
	}

	$tabsEl.each( function(){
		let element = $(this),
			elementNav = element.find('.tab-nav'),
			elementContent = element.find('.tab-container');

		elementNav.children('li').each( function(){
			let navEl = $(this),
				navElAnchor = navEl.children('a'),
				navElTarget = navElAnchor.attr('href'),
				navElContent = navElAnchor.html();

			elementContent.find(navElTarget).before('<div class="accordion-header d-none"><div class="accordion-icon"><i class="accordion-closed icon-ok-circle"></i><i class="accordion-open icon-remove-circle"></i></div><div class="accordion-title">'+navElContent+'</div></div>');
		});
	});
}

function tabsResponsiveResizeInit($tabsEl){

	let $body	= $('body');
	$tabsEl		= $tabsEl.filter('.tabs-responsive');

	if( $tabsEl.length < 1 ){
		return;
	}

	$tabsEl.each( function(){
		let element = $(this),
			elActive = element.tabs( 'option', 'active' ) + 1,
			elementAccStyle = element.attr('data-accordion-style');

		if( $body.hasClass('device-sm') || $body.hasClass('device-xs') ) {

			element.find('.tab-nav').addClass('d-none');
			element.find('.tab-container').addClass( 'accordion '+ elementAccStyle ).attr('data-active', elActive);
			element.find('.tab-content').addClass('accordion-content');
			element.find('.accordion-header').removeClass('d-none');
			SEMICOLON.widget.accordions({ 'parent': element });

		} else if( $body.hasClass('device-md') || $body.hasClass('device-lg') || $body.hasClass('device-xl') ) {

			element.find('.tab-nav').removeClass('d-none');
			element.find('.tab-container').removeClass( 'accordion '+ elementAccStyle ).attr('data-active', '');
			elActive = element.find('.acctitlec').next('.tab-content').index();
			element.find('.tab-content').removeClass('accordion-content');
			element.find('.accordion-header').addClass('d-none');
			element.tabs('refresh');
			if( elActive > 0 ) {
				element.tabs( 'option', 'active', ( ( elActive - 1 ) / 2 ) );
			}

		}
	});
}

export default { 
	exec,
	buildTabs,
	tabsResponsive,
	tabsResponsiveResizeInit
};
