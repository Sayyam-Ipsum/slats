let $itemLookup, $transactionLines;
jQuery(document).ready(function ($) {
	$transactionLines = $('tbody#transactionLines');
	$itemLookup = $('#select_item');
	autoCompleteItem();
	$('#select_item').on('change', function () {
		if ($('#select_item').val() === '') {
			$('#item_id').val('');
		}
	});
	inputToDatepickerA($('#trans_date'));
	inputToDatepickerA($('#to_date'));
	$('#refresh_btn').on('click', function () {
		$('#trans_date').val('')
		$('#to_date').val('')
		$('#select_item').val('')
		$('#item_id').val('')
		$('#report_table').remove()
	})
	$('.i-pickup').on('click', function () {
		let res = confirm('Are You Sure?');
		if (res) {
			let pickuped_qty = parseInt($(this).closest("td").find('input.i-inp_pickup_qty').val());
			if(!pickuped_qty){
				pickuped_qty = 0;
			}
			pickuped_qty++;
			$.ajax({
				cache: false,
				type: 'POST',
				data: { 
					'trans_item_id': $(this).closest("td").find('input.i-inp_pickup').val(),
					'pickuped_qty': pickuped_qty,
				},
				url: getAppURL('reports/updated_trans_item_pickedup_qty'),
				success: function (data) {
					$("#form_report").submit();
				}
			});
		}
	})
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
			//alert(result.row.id);
			$('#item_id').val(result.row.id);
		}, transformResult: function (response) {
			if (response.length === 1) {
				if (response[0]['artical_number'] === $itemLookup.val() || response[0]['EAN'] === $itemLookup.val()) {
					$itemLookup.val('')
					$('#item_id').val(response[0]['id']);
					$("#form_report").submit();
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

function validation() {
	var count = 0;
	if ($('#item_id').val() == "") {
		document.getElementById('error_item').innerHTML = "*Please Select Item*";
		document.getElementById('error_item').className = "alert alert-danger";
		count++;
	}
	if (count > 0) {
		return false;
	} else {
		return true;
	}
}