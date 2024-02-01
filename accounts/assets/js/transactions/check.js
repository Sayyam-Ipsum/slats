let $transactionForm, $itemLookup, $transactionLines;
jQuery(document).ready(function ($) {
	$transactionForm = $('form#transactionForm');
	$transactionLines = $('tbody#transactionLines', $transactionForm);
	$itemLookup = $('#itemLookup', $transactionForm);
	autoCompleteItem();
	bindLinesEvents(); 
	$('#bgback').click(function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
});

function autoCompleteItem() {
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
            addLineToTransaction(result.row, true);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (item) {
					return { value: item.suggestion, row: item };
				})
			};
		}
	});
}

function addLineToTransaction(item, newLine) {
	$itemLookup.val('');
	let $lastTr = jQuery('tr:last', $transactionLines);
	let idx = 1 + parseInt($lastTr.length > 0 ? $lastTr.attr('data-index') : -1);
	$transactionLines.append(ItemLineTpl.replace(/%d/g, String(idx)));
	if (newLine) {
		var $tr = jQuery('tr#item-' + idx, $transactionLines);
		jQuery('input.i-item_id', $tr).val(item.id);
		jQuery('td.i-description', $tr).text(item.EAN);
		jQuery('td.i-barcode', $tr).text(item.artical_number);
		jQuery('input.i-qty', $tr).val('1').focus();
		bindLineEvents($tr);
	}
}

function bindLinesEvents() {
	jQuery('tr', $transactionLines).each(function (i, tr) {
		bindLineEvents(jQuery(tr));
	});
}

function bindLineEvents($tr) {
	jQuery('button.i-remove', $tr).on('click', function () {
		jQuery(this.parentNode.parentNode).remove();
	});
}

function validation() {
	var count = 0;
	jQuery('tr', $transactionLines).each(function (i, tr) {
		var qty = jQuery('input.i-qty', tr).val();
		if (qty.trim() == "") {
			document.getElementById('error_transactionLines').innerHTML = "*please enter number for qty*";
			document.getElementById('error_transactionLines').className = "alert alert-danger";
			count++;
		}
		if (isNaN(qty) == true) {
			document.getElementById('error_transactionLines').innerHTML = "*qty must be numeric*";
			document.getElementById('error_transactionLines').className = "alert alert-danger";
			count++;
		}else{
			if (Math.sign(qty) == -1) {
				document.getElementById('error_transactionLines').innerHTML = "*qty must be positive number*";
				document.getElementById('error_transactionLines').className = "alert alert-danger";
				count++;
			}
		}		
	});
	if (document.getElementById('transactionLines').rows.length == "0") {
		document.getElementById('error_transactionLines').innerHTML = "*Please enter an item*";
		document.getElementById('error_transactionLines').className = "alert alert-danger";
		count++;
	}
	if (count > 0) {
		return false;
	}else{
		return true;
	}
}