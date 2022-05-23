const SELECTOR_CONTAINER = '.scroll-hover-container';
const SELECTOR_ITEM = '.scroll-hover-item';
const SCROLL_SPEED_BASE_MS = 5; // in ms

// if is 
    // a video 
    // OR an item with a height > IMAGE_HEIGHT_BREAKPOINT
// Then apply the hover script

function exec(){
	imageScrollHover(); console.log('imageScrollHover executed');
}
export default { exec };

function imageScrollHover() {

    const sliders = document.querySelectorAll(SELECTOR_CONTAINER);

    sliders.forEach((slider) => {
        window.addEventListener('load', () => {
            sliderStyleOnLoad(slider);
            slider.querySelectorAll(SELECTOR_ITEM).forEach((item) => {
                item.addEventListener('mouseenter', () => imageStyleOnMouseEnter(item));
                item.addEventListener('mouseout', () => imageStyleOnMouseOut(item));
            });
        });
    });

    let sliderStyleOnLoad = function(slider) {
        const sliderMaxHeight = slider.parentNode.parentNode.parentNode.getAttribute('data-max-height');
        slider.style.maxHeight = slider.parentNode.style.maxHeight = sliderMaxHeight;
    };

    let imageStyleOnMouseEnter = function(item) {
        let itemHeight = item.offsetHeight;
        console.log(item.parentNode);
        let containerHeight = item.parentNode.offsetHeight;
        item.style.transition = "transform " + (SCROLL_SPEED_BASE_MS * item.offsetHeight) + "ms ease";
        item.style.transform = "translateY(-" + (itemHeight - containerHeight) + "px)";
    };

    let imageStyleOnMouseOut = function(item) {
        item.style.transform = "translateY(0px)";
    }

}