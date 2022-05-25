import 'flexslider';
import $ from 'jquery';

const SELECTOR = '.fslider';
const SELECTOR_LAZY = '.lazy';
const SELECTOR_HIDE_LEFT_BTN = '.hide-left-btn';

// module
function exec(){
	let $elements = $(SELECTOR);
	$elements = $elements.filter(':not(.customjs)');
	if( $elements.length < 1 ) return;
	flexSlider($elements);
}
export default { exec };

async function flexSlider($elements) {
	console.log('flexSlider executed');
	$elements.each(function() {
		let element			= $(this),
			elAnimation		= element.attr('data-animation') || 'slide',
			elEasing		= element.attr('data-easing') || 'swing',
			elDirection		= element.attr('data-direction') || 'horizontal',
			elReverse		= element.attr('data-reverse'),
			elSlideshow		= element.attr('data-slideshow'),
			elPause			= element.attr('data-pause') || 5000,
			elSpeed			= element.attr('data-speed') || 600,
			elVideo			= element.attr('data-video'),
			elPagi			= element.attr('data-pagi'),
			elArrows		= element.attr('data-arrows'),
			elArrowLeft		= element.attr('data-arrow-left') || 'icon-angle-left',
			elArrowRight	= element.attr('data-arrow-right') || 'icon-angle-right',
			elThumbs		= element.attr('data-thumbs'),
			elHover			= element.attr('data-hover'),
			elSheight		= element.attr('data-smooth-height') || false,
			elTouch			= element.attr('data-touch'),
			elUseCSS		= false,
			hideLeftBtn     = element.find(SELECTOR_HIDE_LEFT_BTN).length > 0,
			sliderMaxHeight = element.attr('data-max-height');

		if( elEasing == 'swing' ) {
			elEasing = 'swing';
			elUseCSS = true;
		}
		if( elReverse == 'true' ) { elReverse = true; } else { elReverse = false; }
		if( elSlideshow == 'true' ) { elSlideshow = true; } else { elSlideshow = false; }
		if( !elVideo ) { elVideo = false; }
		if( elSheight == 'false' ) { elSheight = false; } else { elSheight = true; }
		if( elDirection == 'vertical' ) { elSheight = false; }
		if( elPagi == 'false' ) { elPagi = false; } else { elPagi = true; }
		if( elThumbs == 'true' ) { elPagi = 'thumbnails'; } else { elPagi = elPagi; }
		if( elArrows == 'false' ) { elArrows = false; } else { elArrows = true; }
		if( elHover == 'false' ) { elHover = false; } else { elHover = true; }
		if( elTouch == 'false' ) { elTouch = false; } else { elTouch = true; }

		element.find('.flexslider').flexslider({
			selector: ".slider-wrap > .slide",
			animation: elAnimation,
			easing: elEasing,
			direction: elDirection,
			reverse: elReverse,
			slideshow: elSlideshow,
			slideshowSpeed: Number(elPause),
			animationSpeed: Number(elSpeed),
			pauseOnHover: elHover,
			video: elVideo,
			controlNav: elPagi,
			directionNav: elArrows,
			smoothHeight: elSheight,
			useCSS: elUseCSS,
			touch: elTouch,
			start: (slider) => { // Fires when the slider loads the first slide
				if(hideLeftBtn) {
					let prevBtn = slider.find('.flex-prev');
					if(prevBtn.length > 0) prevBtn.remove();
				}
				$('.flex-prev').html('<i class="'+ elArrowLeft +'"></i>');
				$('.flex-next').html('<i class="'+ elArrowRight +'"></i>');
				$(slider)
				.find('img.lazy:eq(0)')
				.each(function() {
					let src = $(this).attr('data-src');
					$(this).attr('src', src).removeAttr('data-src');
				});
				// smoothHeight of 1st slide
				smootHeight(slider, 0);
			},
			before: (slider) => { // Fires asynchronously with each slider animation
				let slides = slider.slides,
					index = slider.animatingTo,
					$slide = $(slides[index]),
					current = index,
					next_slide = current + 1,
					prev_slide = current - 1,
					parent = $slide.parent(),
					items = parent.find(SELECTOR_LAZY + ':eq(' + current + '), ' + SELECTOR_LAZY + ':eq(' + prev_slide + '), ' + SELECTOR_LAZY + ':eq(' + next_slide + ')')
				;
				if(hideLeftBtn) {
					items = parent.find(SELECTOR_LAZY + ':eq(' + current + '), ' + SELECTOR_LAZY + ':eq(' + next_slide + ')');
				}
				items.each((index, item) => {
					let src = item.getAttribute('data-src');
					if(src) {
						item.setAttribute('src', src);
						item.removeAttribute('data-src');
						if(item.tagName === "SOURCE") {
							item.parentNode.load();
							item.parentNode.play();
						}
					}
				});
			},
			after: (slider) => {
				smootHeight(slider, slider.animatingTo);
			}
		});
	});
}

function smootHeight(slider, index)
{
	let slides = slider.slides,
		$slide = $(slides[index]);
	const intervalId = setInterval(() => {
		if($slide.find('[src]').length > 0) { // check that the image has loaded
			let height;
			if($slide.find('video').length > 0) {
				height = $slide.find('video').height();
			} else if($slide.find('iframe').length > 0) {
				height = $slide.find('iframe').attr('height');
			} else {
				height = $slide.find('img').height();
			}
			if(height > 200) { // check if the image is loaded (it has an height)
				slider.find('.flex-viewport').height( height );
				clearInterval(intervalId);
			}
		}
	}, 50);
}