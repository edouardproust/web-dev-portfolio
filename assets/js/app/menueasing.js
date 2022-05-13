import ShapeOverlays from '../_lib/menuEasing';

const SELECTOR = '.shape-overlays';

let hamburgerSelector = '.hamburger';
let menuItemsSelector = '.global-menu__item';
let duration = 500;
let delayPerPath = 80;
let numPoints = 4;
let overlay;

let $elements = document.querySelector(SELECTOR);

function exec(){

  if($elements.length < 1) {
    return;
  }
  console.log('menuEasing executed');

  generateMenu(); 
  onClickEvent();

}
export default { exec }

function generateMenu() {

  overlay = new ShapeOverlays(
    $elements,
    duration, 
    delayPerPath,
    numPoints
  );
}

function onClickEvent() {

  let elmHamburger = document.querySelector(hamburgerSelector);
  let gNavItems = document.querySelectorAll(menuItemsSelector);

  elmHamburger.addEventListener('click', () => {
    if (overlay.isAnimating) {
      return false;
    }
    overlay.toggle(); 
    if (overlay.isOpened === true) {
      elmHamburger.classList.add('is-opened-navi');
      gNavItems.forEach( (item) => item.classList.add('is-opened') );
    } else {
      elmHamburger.classList.remove('is-opened-navi');
      gNavItems.forEach( (item) => item.classList.remove('is-opened') );
    }
  });

}