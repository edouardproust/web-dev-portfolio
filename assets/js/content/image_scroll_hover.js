import $ from 'jquery';

const SELECTOR_CONTAINER = '.scroll-hover-container';
const SELECTOR_IMG = '.scroll-hover-img';
const CONTAINER_HEIGHT = '50vh';
const CONTAINER_MAX_HEIGHT = '600px';
const SCROLL_SPEED_BASE_MS = 5; // in ms

function exec(){
	imageScrollHover(); console.log('imageScrollHover executed');
}
export default { exec };

function imageScrollHover() {
    // $(SELECTOR_IMG).css(
    //     "transition", "transform " + (SCROLL_SPEED_BASE_MS * $(SELECTOR_IMG).height()) + "ms ease"
    // );

    const sliders = document.querySelectorAll(SELECTOR_CONTAINER);

    let sliderStyleOnLoad = function(slider) {
        slider.style.height = CONTAINER_HEIGHT;
        slider.style.maxHeight = CONTAINER_MAX_HEIGHT;
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