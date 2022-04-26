import lightbox from "../gallery/lightbox";

const SELECTOR = '#ea-index-AdminOption';

function exec(){
	// Only apply on easyAdmin panel
	const eaBody = document.querySelector(SELECTOR);
	if(eaBody) {
		vichUploaderHideDeleteLink(); console.log('vichUploaderHideDeleteLink executed');
		adminOptionPlaceholdersTransform(eaBody); console.log('adminOptionPlaceholdersTransform executed');
	}
}
export default { exec };

function vichUploaderHideDeleteLink() 
{
    const SELECTOR = '.vich-no-delete';

	let elements = document.querySelectorAll(SELECTOR);

	if( elements.length < 1 ){
		return;
	}
	
	elements.forEach((element) => {
        let actionsContainer = element.querySelector('.ea-vich-image-actions');
        let deleteLink = actionsContainer.querySelector('div.form-check');
		deleteLink.parentNode.parentNode.parentNode.outerHTML = '';
    });
}

function adminOptionPlaceholdersTransform(eaBody) 
{
	const placeholderBoundaryLeft = '{%~';
	const placeholderBoundaryRight = '~%}';
	const _value_ = '{%~value~%}';
	const blocks = {
		image: // {"type":"image","value":"\/uploads\/admin\/favicon.ico"}
			'<a href="' + _value_ + '" data-lightbox="image">' + 
				'<img src="' + _value_ + '" style="max-width:200px;height:auto" />' +
			'</a>',
		boolean: // {"type":"boolean","value":false}
			'<div class="form-check form-switch">' +
				'<input type="checkbox" class="form-check-input" ' + _value_ + ' disabled autocomplete="off">' +
			'</div>',
		url: // {"type":"url","value":"https:\/\/github.com"}
			'<a href="' + _value_ + '" target="_blank">' + _value_ + '</a>', 
		null: // {"type":"null","value":"text"} || {"type":"null","value":"file"} || ...
			'<span class="small text-muted"><i>No ' + _value_ + '</i></span>', 
	}
			
	// restrict to page with tables
	let indexTable = eaBody.querySelector('.table.datagrid ');
	indexTable.setAttribute('data-lightbox', true)
	let adminOptionCells = indexTable.querySelectorAll('td[data-label="Value"]');
	if(adminOptionCells) {
		lightbox.exec();
		adminOptionCells.forEach((cell) => {
		let title = cell.querySelector('span[title]').title;
		if(title.includes(placeholderBoundaryLeft) && title.includes(placeholderBoundaryRight)) {
			replaceByBlock(title, cell);
		}
	});
	}
	
	function replaceByBlock($placeholder, tableCell) {
		let jsonEncoded = $placeholder.replace(placeholderBoundaryLeft, '').replace(placeholderBoundaryRight, '');
		let json = JSON.parse(jsonEncoded);
		Object.entries(blocks).forEach((block) => {
			if(block[0] === json.type) {
				let div = document.createElement('div');
				div.classList.add('option-placeholder');
				div.innerHTML = block[1].replaceAll(_value_, json.value);
				tableCell.querySelector('span[title]').replaceWith(div)
			}
		})
	}
}