export default { 
    findMatchingDivs, 
};

/**
 * Search for divs up to 3 nesting levels, based on tag and class criterias
 * @param {HTMLDivElement} element The HTML element to search in
 * @param {String} tag The tag to search for
 * @param {String|null} className The class to search for. Optionnal: default = null
 * @return {Array} Array of HTMLDivElement. Epty array if no divs matches
 */
function findMatchingDivs(element, tag, className = null) {

    return getMatchingDivs();

    function getMatchingDivs() {

        let matches = [];

        // search level1 nodes
        let divs = element.childNodes;
        divs.forEach((node) => {
            if(match(node)) matches.push(node);

            // search level2 nodes
            let divsLvl2 = node.childNodes;
            divsLvl2.forEach((node) => {
                if(match(node)) matches.push(node);

                    // search level3 nodes
                    let divsLvl3 = node.childNodes;
                    divsLvl3.forEach((node) => {
                        if(match(node)) matches.push(node);
                    });

            });

        });

        return matches;
    }

    function match(node) {
        if(node instanceof HTMLElement) {
            // check tag
            if((node.tagName === tag) || (node.tagName.toLowerCase() === tag)) {
                // check className
                if (className === null) {
                    return true;
                } else if(typeof className === 'string') {
                    if(node.classList.contains(className)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}