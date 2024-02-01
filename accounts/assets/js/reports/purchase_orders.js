jQuery(document).ready(function ($) {
	inputToDatepickerA($('#trans_date'));
	inputToDatepickerA($('#to_date'));
	autoCompleteSalesAccount();
});
function autoCompleteSalesAccount() {
	$('#supplier_name').autocomplete({
		serviceUrl: getAppURL('purchases/lookup_suppliers_accounts'), appendTo: $('#supplier_name').parent()[0],
		noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
		noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
		onSearchStart: function (params) {
		}, onSelect: function (result) {
			// alert(result.row.account_name);
			$('#supplier_name').val(result.row.account_name);
            $('#supplier_id').val(result.row.id);
		}, transformResult: function (response) {
			return {
				suggestions: jQuery.map(response, function (account) {
					return { value: account.description, row: account };
				})
			};
		}
	});
}