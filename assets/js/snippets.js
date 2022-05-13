export {
    nl2br,
    escape,
    file_get_contents
}

// Footer links separator

let ep_snipets_menus = document.querySelectorAll('.copyrights-menu');
ep_snipets_menus.forEach((menu) => {
    let links = menu.querySelectorAll('a');
    for(let i = 0; i < links.length; i++) {
        if(i !== 0) {
            links[i].outerHTML = '<span class="footer-sep"> / </span>' + links[i].outerHTML;
        }
    }
})

/**
 * ```bash
 * nl2br('Kevin\nvan\nZonneveld'); // Kevin<br />\nvan<br />\nZonneveld
 * nl2br("\nOne\nTwo\n\nThree\n", false); // <br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n
 * nl2br("\nOne\nTwo\n\nThree\n", true); //<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n
 * ```
 * @param {String} str 
 * @param {Boolean} is_xhtml 
 * @returns 
 */
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function escape(string)
{
    let lookup = {
        '&': "&amp;",
        '"': "&quot;",
        '\'': "&apos;",
        '<': "&lt;",
        '>': "&gt;"
    };
    return string.replace( /[&"'<>]/g, c => lookup[c] );
}

function file_get_contents(filename) {
    fetch(filename).then((resp) => resp.text()).then(function(data) {
        return data;
    });
}