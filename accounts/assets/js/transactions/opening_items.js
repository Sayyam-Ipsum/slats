let $transactionForm, $itemLookup, $transactionLines;
jQuery(document).ready(function ($) {
	$transactionForm = $('form#transactionForm');
	$transactionLines = $('tbody#transactionLines', $transactionForm);
	$itemLookup = $('#itemLookup', $transactionForm);
	inputToDatepickerA($('#trans_date, #value_date, #journal_date', $transactionForm));
	autoCompleteItem();
	bindLinesEvents();
	$('#bgback').click(function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
});
function accountSelected(account, $accountId) {
	$accountId.val(account.id);
}

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
		serviceUrl: getAppURL('purchases/lookup_items'), appendTo: $itemLookup.parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			addLineToTransaction(result.row, true);
		}, transformResult: function (response) {
			if (response.length === 1) {
				if(response[0]['artical_number'] === $itemLookup.val() || response[0]['EAN'] === $itemLookup.val()){
					$itemLookup.autocomplete('clear');
					addLineToTransaction(response[0], true);
				}					
			} 
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
		jQuery('td.i-artical_number', $tr).text(item.artical_number);
		jQuery('td.i-EAN', $tr).text(item.EAN);
		jQuery('input.i-qty', $tr).val('1').focus();
		jQuery('input.i-cost', $tr).val('0');
		jQuery('select.i-warehouse', $tr).val("Main");
		var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();
		$.ajax({
			cache: false,
			type: 'POST',
			data: { 'whatselected': getselected },
			url: getAppURL('purchases/get_warehouse_shelfs'),
			success: function (data) {
				jQuery('select.i-shelf', $tr).empty();
				for (let i = 0; i < data.length; i++) {
					jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">
							${data[i]} </option>`).val(data[0]);
				}
			}
		});
		bindLineEvents($tr);
	}
}

function bindLinesEvents() {
	jQuery('tr', $transactionLines).each(function (i, tr) {
		bindLineEvents(jQuery(tr));
	});
}

function bindLineEvents($tr) {
	jQuery('select.i-warehouse', $tr).change(function (e) {
		var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();
		$.ajax({
			cache: false,
			type: 'POST',
			data: { 'whatselected': getselected },
			url: getAppURL('purchases/get_warehouse_shelfs'),
			success: function (data) {
				jQuery('select.i-shelf', $tr).empty();
				for (let i = 0; i < data.length; i++) {
					jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">
						${data[i]} </option>`);
				}
			}
		});
	});
	jQuery('button.i-remove', $tr).on('click', function () {
		jQuery(this.parentNode.parentNode).remove();
	});
}


function validation() {
	var count = 0;
	if (document.transactionForm.acctLkupTo.value == "") {
		document.getElementById('error_acctLkupTo').innerHTML = "*Please select a Supplier Account*";
		document.getElementById('error_acctLkupTo').className = "alert alert-danger";
		count++;
	}
	if (document.transactionForm.acctLkupFrom.value == "") {
		document.getElementById('error_acctLkupFrom').innerHTML = "*Please select a Purchases Account*";
		document.getElementById('error_acctLkupFrom').className = "alert alert-danger";
		count++;
	}
	if (document.transactionForm.currency_id.value == "") {
		document.getElementById('error_currency_id').innerHTML = "*Please select a currency*";
		document.getElementById('error_currency_id').className = "alert alert-danger";
		count++;
	}
	if (document.transactionForm.currency_rate.value == "") {
		document.getElementById('error_currency_rate').innerHTML = "*Please enter a currency rate*";
		document.getElementById('error_currency_rate').className = "alert alert-danger";
		count++;
	}
	if (document.transactionForm.trans_date.value == "") {
		document.getElementById('error_trans_date').innerHTML = "*Please enter a trans date*";
		document.getElementById('error_trans_date').className = "alert alert-danger";
		count++;
	}
	if (document.getElementById('transactionLines').rows.length == "0") {
		document.getElementById('error_transactionLines').innerHTML = "*Please enter an item*";
		document.getElementById('error_transactionLines').className = "alert alert-danger";
		count++;
	}
	if (count > 0) {
		return false;
	}
}

function AddItem() {
	window.open(getAppURL('items/add?TransAddItem=1'), "_blank");
}