jQuery(document).ready(function ($) {
	inputToDatepickerA($('#trans_date'));
	inputToDatepickerA($('#to_date'));
	autoCompleteCustomerAccount();
	autoCompleteItem();
	$('.c-acc_btn').on('click', function (e) {
		let acc_id = $(this).parent('td').find('.c-acc_id').val();
		let acc_name = $(this).parent('td').text();
		$('#customer_name').val(acc_name.trim())
		$('#customer_id').val(acc_id)
		$('#order_nb').val('')
		$('form#pickup_form').submit()
	})
	$('.c-order_btn').on('click', function (e) {
		let auto_no = $(this).parent('td').find('.c-order_nb').val();
		$('#order_nb').val(auto_no)
		$('form#pickup_form').submit()
	})
	$('#customer_name').on('change', function (e) {
		if ($('#customer_name').val() == '') {
			$('#customer_id').val('')
		}
	})
	$('.c-inp_scan').on('change', function (e) {
		if ($(this).val() == $(this).parent().parent().find('td.td-artical_nb').text().trim() || $(this).val() == $(this).parent().parent().find('td.td-ean').text().trim()) {
			let p_count = $(this).parent().parent().find('td.td-count p.c-order_count');
			let input_count = $(this).parent().parent().find('td.td-count input.c-order_qty');
			let res = parseInt(input_count.val()) + 1;
			input_count.val(res);
			p_count.text(res)
			$(this).val('')
			if (res == parseInt($(this).parent().parent().find('td.td-needed_qty').text().trim())) {
				$(this).attr('readonly', 'readonly');
			}
			// alert($(this).parent().parent().find('td.td-needed_qty').text().trim())
		} else {
			alert('No Matching Between Entered Code & Item Row Artical Number or EAN!')
			$(this).val('')
		}
	})
	$('.c-inp_scan').on('keydown', function (e) {
		if (e.keyCode == 13) {
			if ($(this).val() !== '') {
				if ($(this).val() == $(this).parent().parent().find('td.td-artical_nb').text().trim() || $(this).val() == $(this).parent().parent().find('td.td-ean').text().trim()) {
					let p_count = $(this).parent().parent().find('td.td-count p.c-order_count');
					let input_count = $(this).parent().parent().find('td.td-count input.c-order_qty');
					let res = parseInt(input_count.val()) + 1;
					input_count.val(res);
					p_count.text(res)
					$(this).val('')
					if (res == parseInt($(this).parent().parent().find('td.td-needed_qty').text().trim())) {
						$(this).attr('readonly', 'readonly');
					}
					// alert($(this).parent().parent().find('td.td-needed_qty').text().trim())
				} else {
					alert('No Matching Between Entered Code & Item Row Artical Number or EAN!')
					$(this).val('')
				}
			}
		}
	})
	// //setup before functions
	// var typingTimer;                //timer identifier
	// var doneTypingInterval = 3000;  //time in ms, 5 seconds for example
	// var $input = $('.c-inp_scan');

	// //on keyup, start the countdown
	// $input.on('keyup', function () {
	// 	clearTimeout(typingTimer);
	// 	typingTimer = setTimeout(doneTyping($(this)), doneTypingInterval);
	// });

	// //on keydown, clear the countdown 
	// $input.on('keydown', function () {
	// 	clearTimeout(typingTimer);
	// });

	// //user is "finished typing," do something
	// function doneTyping(inp) {
	// 	//do something
	// 	$('#item').focus()
	// 	inp.focus()
	// }
	$('#pickup_order_form').on('keydown', function (e) {
		if (e.keyCode == 13) {
			// do not submit form on press of return key
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		}
	})
	$('#to_invoice_btn').on('click', function (e) {
		var count = 0;
		jQuery('tr', $('#order_items_tbody')).each(function (i, tr) {
			var ordered_qty = parseInt(jQuery('td.td-ordered_qty', tr).text());
			var pickedup_qty = parseInt(jQuery('td.td-pickedup_qty input', tr).val());
			if (ordered_qty !== pickedup_qty) {
				count++
			}
		});
		if (count == 0) {
			window.open(getAppURL('orders/to_invoice/' + $('#os_id').val()), '_blank');
			// window.location.href = getAppURL('orders/to_invoice/' + $('#os_id').val());
		} else {
			alert('Sorry, All Order Items Must Be Pickedup To Proceed to Invoice!')
		}
	})
	$('#pickup_update_btn').on('click', function (e) {
		var count = 0;
		var count1 = 0;
		var trans_items = [];
		jQuery('tr', $('#order_items_tbody')).each(function (i, tr) {
			var trans_item_id = jQuery('input.c-trans_item_id', tr).val();
			var ordered_qty = parseInt(jQuery('td.td-ordered_qty', tr).text());
			var pickedup_qty = parseInt(jQuery('td.td-pickedup_qty input', tr).val());
			if (ordered_qty !== pickedup_qty) {
				count++
			}
			var qty = jQuery('input.c-order_qty', tr).val();
			if (parseInt(qty) !== 0) {
				count1++;
			}
			trans_items[i] = { qty: qty, pickedup_qty: pickedup_qty, trans_item_id: trans_item_id };
		});
		console.log(trans_items)
		if (count == 0) {
			alert('Sorry, All Order Items Was Pickedup!')
		} else {
			if (count1 !== 0) {
				if (confirm('Are You Sure?')) {
					$.ajax({
						cache: false,
						type: 'POST',
						data: {
							'trans_items': trans_items
						},
						url: getAppURL('reports/update_order_sale_items_pickedup_qty'),
						success: function (data) {
							alert(data)
							$('#search_btn').click()
						}
					});
				}
			} else {
				alert('Sorry, At Least One Item Must be Scanned/Pickedup To Submit!')
			}
		}
	})
	$('#reset').on('click', function () {
		window.location.href = getAppURL('reports/pickup_items');
		// $('#trans_date').val('')
		// $('#to_date').val('')
		// $('#customer_name').val('')
		// $('#customer_id').val('')
		// $('#order_nb').val('')
		// $('#item').val('')
		// $('#item_id').val('')
		// $('#search_btn').click()
	})
	$('#order_items_tbody tr').on('click', function () {
		let trans_item_id = $(this).find('td input.c-trans_item_id').val();
		let item_id = $(this).find('td input.c-item_id').val();
		var itemid = [item_id];
		$.ajax({
			cache: false,
			type: 'POST',
			data: { 'item_ids': itemid },
			url: getAppURL('items/get_items_availability_table_data'),
			success: function (data) {
				$("#activityitemsTbl td").remove();
				for (let j = 0; j < data.length; j++) {
					for (let i = 0; i < data[j].length; i++) {
						r =
							"<tr><td>" + data[j][i].description
							+ "</td><td>" + data[j][i].brand + "</td>"
							+ "<td>" + data[j][i].artical_number + "</td>"
							+ '<td class="i-warehouse">' + data[j][i].warehouse + "</td>"
							+ '<td class="i-shelf">' + data[j][i].shelf + "</td><td>"
							+ data[j][i].total_qty + "</td></tr>";
						$("#activityitemsTbl tbody").append(r);
					}
				}
				$('#activityitemsTbl tbody tr').on('click', function () {
					let w= $(this).find('td.i-warehouse').text();
					let s= $(this).find('td.i-shelf').text();
					$.ajax({
						cache: false,
						type: 'POST',
						data: { 'warehouse': w, 'shelf': s, 'trans_item_id': trans_item_id },
						url: getAppURL('orders/update_order_item_warehouse_and_shelf'),
						success: function (data) {
							if(data == "1"){
								$('#search_btn').click()
							}else{
								alert('Sorry, Something Went Wrong!')
							}
						}
					});
				})
			}
		});
	})
});
function autoCompleteCustomerAccount() {
	$('#customer_name').autocomplete({
		serviceUrl: getAppURL('sales/lookup_customers_accounts'), appendTo: $('#customer_name').parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			// alert(result.row.account_name);
			$('#customer_name').val(result.row.account_number + ' - ' + result.row.account_name);
			$('#customer_id').val(result.row.id);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}

function autoCompleteItem() {
	$('#item').on('keydown', function (e) {
		if (e.keyCode == 13) {
			// do not submit form on press of return key
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		}
	}).autocomplete({
		serviceUrl: getAppURL('items/lookup_items_by_EAN_and_artical_nb'), appendTo: $('#item').parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			// alert(result.row.account_name);
			// $('#item').val(result.row.account_number + ' - ' + result.row.account_name);
			$('#item_id').val(result.row.id);
		}, transformResult: function (response) {
			if (response.length === 1) {
				if (response[0]['artical_number'] === $('#item').val() || response[0]['EAN'] === $('#item').val()) {
					$('#item').autocomplete('clear');
					$('#item').val(response[0]['EAN'] + ' - ' + response[0]['artical_number'] + ' - ' + response[0]['brand']);
					$('#item_id').val(response[0]['id']);
					$('#search_btn').click()
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
	var count1 = 0;
	jQuery('tr', $('#order_items_tbody')).each(function (i, tr) {
		var qty = jQuery('input.c-order_qty', tr).val();
		if (parseInt(qty) !== 0) {
			count1++;
		}
	});
	if (count1 == 0) {
		document.getElementById('error_transactionLines').innerHTML = "*Sorry, At Least One Item Must be Scanned/Pickedup To Submit!*";
		document.getElementById('error_transactionLines').className = "alert alert-danger";
		count++;
	}
	if (count > 0) {
		return false;
	} else {
		return true;
	}
}