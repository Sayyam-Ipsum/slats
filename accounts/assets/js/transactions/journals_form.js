let $transactionForm;
jQuery(document).ready(function ($) {
	$transactionForm = $('form#transactionForm');
	inputToDatepickerA($('#journal_date', $transactionForm));
	let $acctLkupFrom = $('#acctLkupFrom', $transactionForm), $acctLkupTo = $('#acctLkupTo', $transactionForm),
		$accountId = $('#account_id', $transactionForm), $account2Id = $('#account2_id', $transactionForm);
	autoCompleteAccount($acctLkupTo, $accountId, accountSelected);
	getcurrencyrate();
	$('#currency_id_list').prop('disabled', true).trigger("chosen:updated");
	if (window.location.href == getAppURL('payments/add') || window.location.href == getAppURL('receipts/add')) {
		document.getElementById("print").style.visibility = "hidden";
	} else {
		$.ajax({
			cache: false,
			type: 'POST',
			data: { 'whatselected': $('#currency_id').val() },
			url: getAppURL('currencies/get_currency_rate'),
			success: function (data) {
				var result = data.split('-');
				var cur_read = result[1];
				if (cur_read == "readonly") {
					document.getElementById('currency_rate').setAttribute("readonly", true);
				} else {
					document.getElementById('currency_rate').removeAttribute("readonly");
				}
			}
		});
	}
});

function accountSelected(account, $accountId) {
	$accountId.val(account.id);
}

function autoCompleteAccount($acctLkup, $accountId, onResultSelection) {
	$acctLkup.autocomplete({
		serviceUrl: getAppURL('purchases/lookup_accounts'), appendTo: $acctLkup.parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			onResultSelection(result.row, $accountId);
			let $acctLkupFrom = $('#acctLkupFrom', $transactionForm);
			let $account2Id = $('#account2_id', $transactionForm);
			var id = $("#account_id").val();
			$.ajax({
				cache: false,
				type: 'POST',
				data: { 'whatselected': id },
				url: getAppURL('purchases/get_account_currency'),
				success: function (data) {
					$('#currency_id_list').val(data);
					$('#currency_id').val(data);
					autoCompleteAccountCurrency($acctLkupFrom, $account2Id, accountSelected, data);
					$.ajax({
						cache: false,
						type: 'POST',
						data: { 'whatselected': data },
						url: getAppURL('currencies/get_currency_rate'),
						success: function (data) {
							var result = data.split('-');
							var cur_rate = result[0];
							var cur_read = result[1];
							$('#currency_id_list').prop('disabled', true).trigger("chosen:updated");
							$('#currency_rate').val(cur_rate);
							if (cur_read == "readonly") {
								document.getElementById('currency_rate').setAttribute("readonly", true);
							} else {
								document.getElementById('currency_rate').removeAttribute("readonly");
							}
						}
					});
				}
			});
			$.ajax({
				cache: false,
				type: 'POST',
				data: { 'acc_id': id },
				url: getAppURL('accounts/get_account_balance'),
				success: function (data) {
					$('#balance').val(data);
				}
			});
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}

function getcurrencyrate() {
	$('#currency_id').change(function (e) {
		var id = $("#currency_id").val();
		$.ajax({
			cache: false,
			type: 'POST',
			data: { 'whatselected': id },
			url: getAppURL('currencies/get_currency_rate'),
			success: function (data) {
				var result = data.split('-');
				$('#currency_rate').val(result[0]);
			}
		});
	});
}

function validation() {
	// // alert(ali);
	// return false;
	var count = 0;
	if (document.transactionForm.acctLkupTo.value == "" || document.transactionForm.account_id.value == "") {
		document.getElementById('error_acctLkupTo').innerHTML = "*Please select Account*";
		document.getElementById('error_acctLkupTo').className = "alert alert-danger";
		count++;
	}
	if (document.transactionForm.acctLkupFrom.value == "" || document.transactionForm.account2_id.value == "") {
		document.getElementById('error_acctLkupFrom').innerHTML = "*Please select Account*";
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
	var date1 = change_date_format(document.transactionForm.journal_date.value);
	if (document.transactionForm.journal_date.value == "" || isNaN(date1.getDate())) {
		document.getElementById('error_trans_date').innerHTML = "*Please enter a valide date*";
		document.getElementById('error_trans_date').className = "alert alert-danger";
		count++;
	}
	var amount = $('#amount').val();
	if (amount.trim() == "") {
		document.getElementById('error_amount').innerHTML = "*please enter number for amount*";
		document.getElementById('error_amount').className = "alert alert-danger";
		count++;
	}
	if (isNaN(amount) == true) {
		document.getElementById('error_amount').innerHTML = "*amount must be numeric*";
		document.getElementById('error_amount').className = "alert alert-danger";
		count++;
	} else {
		if (Math.sign(amount) == -1) {
			document.getElementById('error_amount').innerHTML = "*amount must be positive number*";
			document.getElementById('error_amount').className = "alert alert-danger";
			count++;
		}
	}
	if (count > 0) {
		return false;
	}
}

function change_date_format(date) {
	var datearray = date.split("-");
	var new_date_format = datearray[1] + "/" + datearray[0] + "/" + datearray[2]
	var date_new = new Date(new_date_format);
	return date_new;
}

function autoCompleteAccountCurrency($acctLkup, $accountId, onResultSelection, $currency_id) {
	$acctLkup.autocomplete({
		serviceUrl: getAppURL('payments/lookup_accounts_with_same_cuurency/'.concat($currency_id)), appendTo: $acctLkup.parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			onResultSelection(result.row, $accountId);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}
