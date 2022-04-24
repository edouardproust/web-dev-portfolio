

// TypeAround plugin bug on <post-type>/show pages
const contentContainer = document.querySelector('.entry-content');
if(contentContainer) {
let typeAroundContainers = contentContainer.querySelectorAll('.ck-widget__type-around');

    typeAroundContainers.forEach((container) => {
        container.parentNode.removeChild(container);
    });
}

// move Symfony Profiler icon (to prevent it hiding the open button of the CKEditor Inspector)
setTimeout(() => {
    let symfonyProfilerIcon = document.querySelector('.sf-minitoolbar');
    symfonyProfilerIcon.style.left = '0px';
    symfonyProfilerIcon.style.width = '38px';
    symfonyProfilerIcon.style.borderRadius = '0px 5px 0px 0px';
},1000);
