jQuery(document).ready(function ($) {
	autoCompleteAccount();
});
function autoCompleteAccount() {
	$('#customer').autocomplete({
		serviceUrl: getAppURL('sales/lookup_customers_accounts'), appendTo: $('#account').parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			// alert(result.row.account_name);
			$('#customer').val(result.row.account_name+" - "+result.row.account_number);
            $('#account_id').val(result.row.id);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}