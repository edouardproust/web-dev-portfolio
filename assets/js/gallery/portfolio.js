import $ from 'jquery';

const SELECTOR = '.portfolio-reveal';

function exec(){
    revealDesc(); 
    // ajaxload();
}

function revealDesc(){
    
    let $elements = $(SELECTOR);

    if( $elements < 1 ) {
        return;
    }
    console.log('portfolio::revealDesc executed');

    $elements.each( function(){
        let element			= $(this),
            elementItems	= element.find('.portfolio-item');
        elementItems.each( function(){
            let element			= $(this).find('.portfolio-desc'),
                elementHeight	= element.outerHeight();
            element.css({ 'margin-top': '-'+elementHeight+'px' });
        });
    });
}

export default { exec };

// export default {
//     init,
//     revealDesc
// }

// function ajaxload(){
//     let settings = {
//         default: '.portfolio-ajax',
//         file: 'ajaxportfolio.js',
//         error: 'ajaxportfolio.js: Plugin could not be loaded',
//         execfn: 'SEMICOLON_portfolioAjaxloadInit',
//         pluginfn: 'typeof scwAjaxPortfolioPlugin !== "undefined"',
//         trigger: 'pluginAjaxPortfolioReady',
//         class: 'has-plugin-ajaxportfolio'
//     };

//     SEMICOLON.initialize.functions( settings );
// }