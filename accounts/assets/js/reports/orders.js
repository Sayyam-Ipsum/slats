jQuery(document).ready(function ($) {
	inputToDatepickerA($('#trans_date'));
	inputToDatepickerA($('#to_date'));
	inputToDatepickerA($('#bulk_date'));
	inputToDatepickerA($('#date1'));
	autoCompleteSalesAccount();
});

$('#bulk').on('click', function (e) {
	let table = document.getElementById("table_bulk");
	table.deleteTHead();
	for (var i = 1; i < table.rows.length;) {
		table.deleteRow(i);
	}
	var rows = [];
	var count = 0;
	$("table > tbody > tr").each(function () {
		rows[count] =
		{
			'Artical nb': $(this).find('td').eq(1).text(),
			'Item Name': $(this).find('td').eq(2).text(),
			'Brand': $(this).find('td').eq(3).text(),
			'Qty': $(this).find('td').eq(4).text(),
			'Cost': $(this).find('td').eq(5).text(),
			'Total Cost': $(this).find('td').eq(6).text(),
			'Warehouse': $(this).find('td').eq(7).text(),
			'Customer Name': $(this).find('td').eq(8).text(),
			'Status': $(this).find('td').eq(9).text(),
			'Quotation#': $(this).find('td').eq(10).text(),
			'Order#': $(this).find('td').eq(11).text(),
			'Invoice#': $(this).find('td').eq(12).text(),
		};
		count++;
	});
	let data = Object.keys(rows[0]);
	generateTableHead(table, data);
	generateTable(table, rows);
	$('#group_action_modal').modal("show");
	// console.log(all_ids);
});

$('#update').on('click', function (e) {
	var ids = [];
	var all_ids = [];
	var count = 0;
	$("#report_table > tbody > tr").each(function () {	
		ids[count] = { 'id': $(this).find("td").eq(13).find("input").val() };
		all_ids[count] = ids[count].id;
		count++;
	});		
	$.ajax({
		cache: false,
		type: 'POST',
		async: true,
		data: {
			'ids': all_ids, 'status': $('#status').val(), 'date': change_date_format($('#bulk_date').val())
		},
		url: getAppURL('reports/orders_report_bulk_action'),
		success: function (data) {
			// console.log(data);
			setTimeout(() => $('#group_action_modal').modal('hide'), 1000);
			location.reload();
		}
	});
});

function generateTableHead(table, data) {
	let thead = table.createTHead();
	let row = thead.insertRow();
	for (let key of data) {
		let th = document.createElement("th");
		let text = document.createTextNode(key);
		th.appendChild(text);
		row.appendChild(th);
	}
}

function generateTable(table, data) {
	for (let element of data) {
		let row = table.insertRow();
		for (key in element) {
			let cell = row.insertCell();
			let text = document.createTextNode(element[key]);
			cell.appendChild(text);
		}
	}
}

function change_date_format(date) {
	var datearray = date.split("-");
	var new_date_format = datearray[2] + "/" + datearray[1] + "/" + datearray[0]
	// var date_new = new Date(new_date_format);
	return new_date_format;
}

function action_status(id){
	$('#id_single_action').val(id);
	$('#single_action_modal').modal("show");	
}

$('#update1').on('click', function (e) {
	var all_ids = [$('#id_single_action').val()];
	$.ajax({
		cache: false,
		type: 'POST',
		async: true,
		data: {
			'ids': all_ids, 'status': $('#status1').val(), 'date': change_date_format($('#date1').val())
		},
		url: getAppURL('reports/orders_report_bulk_action'),
		success: function (data) {
			setTimeout(() => $('#single_action_modal').modal('hide'), 1000);
			location.reload();
		}
	});
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
