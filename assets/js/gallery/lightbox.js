import "magnific-popup";
import $ from 'jquery';

const SELECTOR = '[data-lightbox]';

function exec(){
	lightbox();
}
export default { exec }

function lightbox() {

	let elements = $(SELECTOR)
	elements = elements.filter(':not(.customjs)');

	if( elements.length < 1 ) return;
	console.log('lightbox executed');

	elements.each( function(){
		let element			= $(this),
			elType			= element.attr('data-lightbox'),
			elCloseButton	= element.attr('data-close-button') || 'outside',
			elDisableUnder	= element.attr('data-disable-under') || 600,
			$body			= $('body');

		if( elCloseButton == 'inside' ) {
			elCloseButton = true;
		} else {
			elCloseButton = false;
		}

		if( elType == 'image' ) {
			element.magnificPopup({
				type: 'image',
				closeOnContentClick: true,
				closeBtnInside: elCloseButton,
				fixedContentPos: true,
				mainClass: 'mfp-no-margins mfp-fade', // class to remove default margin from left and right side
				image: {
					verticalFit: true
				}
			});
		}

		if( elType == 'gallery' ) {
			if( element.find('a[data-lightbox="gallery-item"]').parent('.clone').hasClass('clone') ) {
				element.find('a[data-lightbox="gallery-item"]').parent('.clone').find('a[data-lightbox="gallery-item"]').attr('data-lightbox','');
			}

			if( element.find('a[data-lightbox="gallery-item"]').parents('.cloned').hasClass('cloned') ) {
				element.find('a[data-lightbox="gallery-item"]').parents('.cloned').find('a[data-lightbox="gallery-item"]').attr('data-lightbox','');
			}

			element.magnificPopup({
				delegate: 'a[data-lightbox="gallery-item"]',
				type: 'image',
				closeOnContentClick: true,
				closeBtnInside: elCloseButton,
				fixedContentPos: true,
				mainClass: 'mfp-no-margins mfp-fade', // class to remove default margin from left and right side
				image: {
					verticalFit: true
				},
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				}
			});
		}

		if( elType == 'iframe' ) {
			element.magnificPopup({
				disableOn: Number( elDisableUnder ),
				type: 'iframe',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false
			});
		}

		if( elType == 'inline' ) {
			element.magnificPopup({
				type: 'inline',
				mainClass: 'mfp-no-margins mfp-fade',
				closeBtnInside: elCloseButton,
				fixedContentPos: true,
				overflowY: 'scroll'
			});
		}

		if( elType == 'ajax' ) {
			element.magnificPopup({
				type: 'ajax',
				closeBtnInside: elCloseButton,
				autoFocusLast: false,
				callbacks: {
					ajaxContentAdded: function(mfpResponse) {
						SEMICOLON.widget.loadFlexSlider();
						SEMICOLON.initialize.resizeVideos();
						SEMICOLON.widget.masonryThumbs();
						SEMICOLON.widget.cartQuantity();
					},
					open: function() {
						$body.addClass('ohidden');
					},
					close: function() {
						$body.removeClass('ohidden');
					}
				}
			});
		}

		if( elType == 'ajax-gallery' ) {
			element.magnificPopup({
				delegate: 'a[data-lightbox="ajax-gallery-item"]',
				type: 'ajax',
				closeBtnInside: elCloseButton,
				autoFocusLast: false,
				gallery: {
					enabled: true,
					preload: 0,
					navigateByImgClick: false
				},
				callbacks: {
					ajaxContentAdded: function(mfpResponse) {
						SEMICOLON.widget.loadFlexSlider();
						SEMICOLON.initialize.resizeVideos();
						SEMICOLON.widget.masonryThumbs();
						SEMICOLON.widget.cartQuantity();
					},
					open: function() {
						$body.addClass('ohidden');
					},
					close: function() {
						$body.removeClass('ohidden');
					}
				}
			});
		}

		element.on( 'mfpOpen', function(){
			let lightboxItem	= $.magnificPopup.instance.currItem.el,
				lightboxClass	= $( lightboxItem ).attr('data-lightbox-class'),
				lightboxBgClass	= $( lightboxItem ).attr('data-lightbox-bg-class');

			if( lightboxClass != '' ) {
				$( $.magnificPopup.instance.container ).addClass( lightboxClass );
			}

			if( lightboxBgClass != '' ) {
				$( $.magnificPopup.instance.bgOverlay ).addClass( lightboxBgClass );
			}
		});
	});

}