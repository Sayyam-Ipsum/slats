function validation() {
	var count = 0;
	if (window.location.href == getAppURL('items/add')) {
		$.ajax({
			cache: false,
			type: 'POST',
			async: false,
			data: { 'barcode': document.itemsform.barcode.value },
			url: getAppURL('Items/check_if_barcode_exists'),
			success: function (data) {
				if (data != "0") {
					document.getElementById('error_barcode').innerHTML = "*Please Enter a new barcode: it must be unique*";
					document.getElementById('error_barcode').className = "alert alert-danger";
					count++;
				}
			}
		});
		$.ajax({
			cache: false,
			type: 'POST',
			async: false,
			data: { 'EAN': $('#EAN').val() },
			url: getAppURL('Items/check_if_EAN_exists'),
			success: function (data) {
				if (data != "0") {
					document.getElementById('error_EAN').innerHTML = "*Please Enter a new EAN: it must be unique*";
					document.getElementById('error_EAN').className = "alert alert-danger";
					count++;
				}
			}
		});
		// $.ajax({
		// 	cache: false,
		// 	type: 'POST',
		// 	async: false,
		// 	data: { 'artical_number': $('#artical_number').val() },
		// 	url: getAppURL('Items/check_if_artical_number_exists'),
		// 	success: function (data) {
		// 		if (data != "0") {
		// 			document.getElementById('error_artical_number').innerHTML = "*Please Enter a new Artical Number: it must be unique*";
		// 			document.getElementById('error_artical_number').className = "alert alert-danger";
		// 			count++;
		// 		}
		// 	}
		// });
	}
	var id = $('#id').val();
	if (id) {
		if (window.location.href == getAppURL('items/edit/') + id) {
			$.ajax({
				cache: false,
				type: 'POST',
				async: false,
				data: { 'barcode': document.itemsform.barcode.value, 'id': id },
				url: getAppURL('Items/check_if_barcode_exists_on_item_update'),
				success: function (data) {
					if (data.barcode != document.itemsform.barcode.value) {
						if (data.count != "0") {
							document.getElementById('error_barcode').innerHTML = "*Please Enter a new barcode: it must be unique*";
							document.getElementById('error_barcode').className = "alert alert-danger";
							count++;
						}
					}
				}
			});
			$.ajax({
				cache: false,
				type: 'POST',
				async: false,
				data: { 'EAN': $('#EAN').val(), 'id': id },
				url: getAppURL('Items/check_if_EAN_exists_on_item_update'),
				success: function (data) {
					if (data.item_EAN != $('#EAN').val()) {
						if (data.count != "0") {
							document.getElementById('error_EAN').innerHTML = "*Please Enter a new EAN: it must be unique*";
							document.getElementById('error_EAN').className = "alert alert-danger";
							count++;
						}
					}
				}
			});
			// $.ajax({
			// 	cache: false,
			// 	type: 'POST',
			// 	async: false,
			// 	data: { 'artical_number': $('#artical_number').val(), 'id': id },
			// 	url: getAppURL('Items/check_if_artical_number_exists_on_item_update'),
			// 	success: function (data) {
			// 		if (data.item_artical_number != $('#artical_number').val()) {
			// 			if (data.count != "0") {
			// 				document.getElementById('error_artical_number').innerHTML = "*Please Enter a new Artical Number: it must be unique*";
			// 				document.getElementById('error_artical_number').className = "alert alert-danger";
			// 				count++;
			// 			}
			// 		}
			// 	}
			// });
		}
	}
	if (document.itemsform.barcode.value == "") {
		document.getElementById('error_barcode').innerHTML = "*Please Enter or generate a barcode*";
		document.getElementById('error_barcode').className = "alert alert-danger";
		count++;
	}
	if (document.itemsform.description.value == "") {
		document.getElementById('error_description').innerHTML = "*Please enter a Name*";
		document.getElementById('error_description').className = "alert alert-danger";
		count++;
	}
	if (document.itemsform.EAN.value == "") {
		document.getElementById('error_EAN').innerHTML = "*Please enter a EAN*";
		document.getElementById('error_EAN').className = "alert alert-danger";
		count++;
	}
	if (document.itemsform.artical_number.value == "") {
		document.getElementById('error_artical_number').innerHTML = "*Please enter a Artical Number*";
		document.getElementById('error_artical_number').className = "alert alert-danger";
		count++;
	}
	// if (isNaN(document.itemsform.profit.value) == true) {
	// 	document.getElementById('error_profit').innerHTML = "*Please enter a numeric value*";
	// 	document.getElementById('error_profit').className = "alert alert-danger";
	// 	count++;
	// }
	// var prof = $("#profit").val();
	// if (parseFloat(prof) < 0) {
	// 	document.getElementById('error_profit').innerHTML = "*Please enter a numeric value between 0 and 100*";
	// 	document.getElementById('error_profit').className = "alert alert-danger";
	// 	count++;
	// }
	// if (isNaN(document.itemsform.price.value) == true) {
	// 	document.getElementById('error_price').innerHTML = "*Please enter a numeric value*";
	// 	document.getElementById('error_price').className = "alert alert-danger";
	// 	count++;
	// }
	// if (isNaN(document.itemsform.price_ttc.value) == true) {
	// 	document.getElementById('error_price_ttc').innerHTML = "*Please enter a numeric value*";
	// 	document.getElementById('error_price_ttc').className = "alert alert-danger";
	// 	count++;
	// }
	if (isNaN(document.itemsform.cost.value) == true) {
		document.getElementById('error_cost').innerHTML = "*Please enter a numeric value*";
		document.getElementById('error_cost').className = "alert alert-danger";
		count++;
	}
	if (isNaN(document.itemsform.purchase_cost.value) == true) {
		document.getElementById('error_purchase_cost').innerHTML = "*Please enter a numeric value*";
		document.getElementById('error_purchase_cost').className = "alert alert-danger";
		count++;
	}
	if (isNaN(document.itemsform.open_cost.value) == true) {
		document.getElementById('error_open_cost').innerHTML = "*Please enter a numeric value*";
		document.getElementById('error_open_cost').className = "alert alert-danger";
		count++;
	}
	if (isNaN(document.itemsform.weight.value) == true) {
		document.getElementById('error_weight').innerHTML = "*Please enter a numeric value*";
		document.getElementById('error_weight').className = "alert alert-danger";
		count++;
	}
	if (count > 0) {
		return false;
	} else {
		return true;
	}
}