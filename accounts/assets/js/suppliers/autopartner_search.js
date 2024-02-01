jQuery(document).ready(function ($) {
    autoCompleteItem();
})   
function autoCompleteItem() {
    let $itemLookup = $('#items');
	$itemLookup.on('keydown', function (e) {
		if (e.keyCode == 13) {
			// do not submit form on press of return key
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		}
	}).autocomplete({
		serviceUrl: getAppURL('items/lookup_items_by_EAN_and_artical_nb'), appendTo: $itemLookup.parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			//alert(result.row.id);
			$('#items').val(result.row.artical_number)
		}, transformResult: function (response) {
			// if (response.length === 1) {
			// 	if (response[0]['artical_number'] === $itemLookup.val() || response[0]['EAN'] === $itemLookup.val()) {
			// 		$itemLookup.autocomplete('clear');
			// 		// addLineToTransaction(response[0], true);
			// 	}
			// }
			return {
				suggestions: jQuery.map(response, function (item) {
					return { value: item.suggestion, row: item };
				})
			};
		}
	});
}