jQuery(document).ready(function ($) {
	inputToDatepickerA($('#trans_date'));
	inputToDatepickerA($('#to_date'));
	inputToDatepickerA($('#bulk_date'));
	inputToDatepickerA($('#date1'));
	autoCompleteSalesAccount();
});
function autoCompleteSalesAccount() {
	$('#customer_name').autocomplete({
		serviceUrl: getAppURL('sales/lookup_customers_accounts'), appendTo: $('#customer_name').parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			// alert(result.row.account_name);
			$('#customer_name').val(result.row.account_name);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}