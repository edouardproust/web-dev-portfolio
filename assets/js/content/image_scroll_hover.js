import $ from 'jquery';


const SELECTOR_CONTAINER = '.scroll-hover-container';
const SELECTOR_IMG = '.scroll-hover-img';
const IMAGE_HEIGHT_BREAKPOINT = '90vh';
const SCROLL_SPEED_BASE_MS = 5; // in ms

// if is 
    // a video 
    // OR an image with a height > IMAGE_HEIGHT_BREAKPOINT
// Then apply the hover script

function exec(){
	imageScrollHover(); console.log('imageScrollHover executed');
}
export default { exec };

function imageScrollHover() {

    const sliders = document.querySelectorAll(SELECTOR_CONTAINER);

    let sliderStyleOnLoad = function(slider) {
        slider.style.maxHeight = IMAGE_HEIGHT_BREAKPOINT;
        slider.parentNode.style.maxHeight = IMAGE_HEIGHT_BREAKPOINT;
    };

    let imageStyleOnMouseEnter = function(image) {
        let imageHeight = image.offsetHeight;
        let containerHeight = image.parentNode.offsetHeight;
        image.style.transition = "transform " + (SCROLL_SPEED_BASE_MS * image.offsetHeight) + "ms ease";
        image.style.transform = "translateY(-" + (imageHeight - containerHeight) + "px)";
    };

    let imageStyleOnMouseOut = function(image) {
        image.style.transform = "translateY(0px)";
    }

    sliders.forEach((slider) => {
        window.addEventListener('load', (event) => {
            sliderStyleOnLoad(slider);
            slider.querySelectorAll(SELECTOR_IMG).forEach((image) => {
                image.addEventListener('mouseenter', () => imageStyleOnMouseEnter(image));
                image.addEventListener('mouseout', () => imageStyleOnMouseOut(image));
            });
        });
    });

}