import '../_lib/hover3d';
import $ from 'jquery';

const SELECTOR = ".img-hover-wrap";

function exec() {
    card3d(); 
}
export default { exec };

function card3d() {

    let $elements = $(SELECTOR);
    if($elements.length < 1) {
        return;
    }
    console.log('card3d executed');

    $elements.hover3d({
        selector: ".img-hover-card",
        shine: false,
    });
}