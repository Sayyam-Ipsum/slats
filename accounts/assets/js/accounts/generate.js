$(document).ready(function () {
    if (window.location.href == getAppURL('accounts/add')) {
        var getselected = $("#account_type option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: getAppURL('Accounts/fetchaccountnumberfromDatabase'),
            success: function (data) {
                $('#account_number').val(data);
            }
        });
    }

    $('#account_type').change(function (e) {
        var getselected = $("#account_type option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: getAppURL('Accounts/fetchaccountnumberfromDatabase'),
            success: function (data) {
                $('#account_number').val(data);
            }
        });
    });

});

function validation() {
    var count = 0;
    if (document.accountForm.currency_id.value == "") {
        document.getElementById('error_currency_id').innerHTML = "*Please select a cuurency*";
        document.getElementById('error_currency_id').className = "alert alert-danger";
        count++;
    }
    var date1 = change_date_format(document.accountForm.opening_date.value);
	if (document.accountForm.opening_date.value == "" || isNaN(date1.getDate())) {
		document.getElementById('error_date').innerHTML = "*Please enter a valide date*";
		document.getElementById('error_date').className = "alert alert-danger";
		count++;
	}
    if (count > 0) {
		return false;
	}else{
        return true;
    }
}

function change_date_format(date){
	var datearray = date.split("-");
	var new_date_format = datearray[1] + "/" + datearray[0] + "/" + datearray[2]
	var date_new = new Date(new_date_format);
	return date_new;
}
