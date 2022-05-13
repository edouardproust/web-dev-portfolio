import jRespond from 'jrespond';

const SELECTOR = 'body';

function exec(){
    jRespondBuild();
}
export default { exec };


function jRespondBuild() {

    console.log('jrespond::jRespondBuild executed');
    
    let $body = $(SELECTOR);

    let jRes = jRespond([
        {
            label: 'smallest',
            enter: 0,
            exit: 575
        },{
            label: 'handheld',
            enter: 576,
            exit: 767
        },{
            label: 'tablet',
            enter: 768,
            exit: 991
        },{
            label: 'laptop',
            enter: 992,
            exit: 1199
        },{
            label: 'desktop',
            enter: 1200,
            exit: 10000
        }
    ]);

    jRes.addFunc([
        {
            breakpoint: 'desktop',
            enter: function() { $body.addClass('device-xl'); },
            exit: function() { $body.removeClass('device-xl'); }
        },{
            breakpoint: 'laptop',
            enter: function() { $body.addClass('device-lg'); },
            exit: function() { $body.removeClass('device-lg'); }
        },{
            breakpoint: 'tablet',
            enter: function() { $body.addClass('device-md'); },
            exit: function() { $body.removeClass('device-md'); }
        },{
            breakpoint: 'handheld',
            enter: function() { $body.addClass('device-sm'); },
            exit: function() { $body.removeClass('device-sm'); }
        },{
            breakpoint: 'smallest',
            enter: function() { $body.addClass('device-xs'); },
            exit: function() { $body.removeClass('device-xs'); }
        }
    ]);

    if( ! 'IntersectionObserver' in window ){
        intersectObserve;
    }
}