// Footer links separator

let menus = document.querySelectorAll('.copyrights-menu');
menus.forEach((menu) => {
    links = menu.querySelectorAll('a');
    for(let i = 0; i < links.length; i++) {
        if(i !== 0) {
            links[i].outerHTML = '<span class="footer-sep"> / </span>' + links[i].outerHTML;
        }
    }
})