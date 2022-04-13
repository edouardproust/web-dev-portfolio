function exec(){
	vichUploaderHideDeleteLink(); console.log('vichUploaderHideDeleteLink executed');
}
export default { exec };


function vichUploaderHideDeleteLink() {

    const SELECTOR = '.vich-no-delete';

	let elements = document.querySelectorAll(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	
	elements.forEach((element) => {
        let actionsContainer = element.querySelector('.ea-vich-image-actions');
        let deleteLink = actionsContainer.querySelector('div[class=""]');
		deleteLink.innerHTML = '';
    });
}