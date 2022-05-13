const SELECTOR_INDEX = '.ea-index';
const SELECTOR_EDIT = '.ea-edit-form';
const SELECTOR_NEW = '.ea-new-form';

function exec(){

	// Crud Actions
	const eaIndexBody = document.querySelector(SELECTOR_INDEX);
	const eaEditForm = document.querySelector(SELECTOR_EDIT);
	const eaForm = document.querySelector(SELECTOR_EDIT) ?? document.querySelector(SELECTOR_NEW);

	if(eaIndexBody) { // only on index
		adminOptionPlaceholdersTransform(eaIndexBody); console.log('ui_hacks::adminOptionPlaceholdersTransform executed');
	}		
	if(eaForm) { // only on forms
		// ...
	}
	if(eaEditForm) { // only on edit
		// ...
	}	
}
export default { exec };

function adminOptionPlaceholdersTransform(body) 
{
	const placeholderBoundaryLeft = '{%~';
	const placeholderBoundaryRight = '~%}';
	const _value_ = '{%~value~%}';
	const blocks = {
		image: 
			'<img data-lightbox src="' + _value_ + '" style="max-width:200px;height:auto" />',
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
	let indexTable = body.querySelector('.table.datagrid ');
	indexTable.setAttribute('data-lightbox', true)
	let adminOptionCells = indexTable.querySelectorAll('td[data-label="Value"]');
	if(adminOptionCells) {
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