jQuery(document).ready(function () {	window.dnDT = null;	let dtActionsHTML = '<a href="' + getAppURL('delivery_notes/preview/') + '%d" class="btn bt-link btn-sm" title="Delivery Note"><i style="color:#282828;" class="glyphicon glyphicon-file"></i></a>'+	'<a href="' + getAppURL('sales/check/') + '%d" class="btn bt-link btn-sm" title="Edit"><i style="color:#282828;" class="glyphicon glyphicon-search"></i></a>';				/***************************************************/	var $dtTbl = $('#DNTbl');/*	BuildDataTableColumnSearch($dtTbl, 'dnDT');	EnhanceDataTableSearch(window.dnDT = $dtTbl.DataTable({		orderCellsTop: true, fixedHeader: {headerOffset: 0}, searchDelay: _GST, lengthMenu: _dtLengthMenu,		serverSide: true, processing: true, scrollX: true, scrollY: "350px", deferLoading: $dtTbl.attr('data-num-rows'),		order: [[0, 'asc']], ajax: {url: getAppURL('sales/index'), type: 'GET', searchDelay: _GST},		columns: [{data: 'auto_no'}, {data: 'trans_date'}, {data: 'value_date'}, {data: 'account1'}, {data: 'id'}],		columnDefs: [			{targets: 4, orderable: false, createdCell: (td, accId) => $(td).addClass('text-right').html(dtActionsHTML.replace(/%d/g, parseInt(accId)))}		]	}), 2048);*/});