jQuery(document).ready(function ($) {
    $transferForm = $('form#transferform');
    inputToDatepickerA($('#trans_date', $transferForm));
});
$(document).ready(function () {
    var getselected = $('#from').children("option:selected").val();
    var item_id = $('#item_id').val();
    $.ajax({
        cache: false,
        type: 'POST',
        data: { 'whatselected': getselected, 'item_id': item_id },
        url: getAppURL('warehouses/get_max_qty'),
        success: function (data) {
            var input = document.getElementById("qty");
            input.type = "number";
            input.setAttribute("min", 1);
            input.setAttribute("max", data);
            $('#qty').val(data);
            $('#max_qty').val(data);
        }
    });
    $('#from').change(function (e) {
        var getselected = $('#from').children("option:selected").val();
        var item_id = $('#item_id').val();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected, 'item_id': item_id },
            url: getAppURL('warehouses/get_max_qty'),
            success: function (data) {
                var input = document.getElementById("qty");
                input.type = "number";
                input.setAttribute("min", 1);
                input.setAttribute("max", data);
                $('#qty').val(data);
                $('#max_qty').val(data);
            }
        });
    });
});

function validate() {

    var count = 0;
    var element = document.getElementById("qty");
    var min = parseInt(element.getAttribute("min"));
    var max = parseInt(element.getAttribute("max"));
    var value = parseInt(element.value);
    if (value < min || value > max) {
        document.getElementById('error_qty').innerHTML = "*Please Enter value less than or equal ".concat(max).concat("*");
        document.getElementById('error_qty').className = "alert alert-danger";
        count++;
    }
    var qty = $('#qty').val();
    if (qty.trim() == "") {
        document.getElementById('error_qty').innerHTML = "*please enter number for qty*";
        document.getElementById('error_qty').className = "alert alert-danger";
        count++;
    }
    if (isNaN(qty) == true) {
        document.getElementById('error_qty').innerHTML = "*qty must be numeric*";
        document.getElementById('error_qty').className = "alert alert-danger";
        count++;
    } else {
        if (Math.sign(qty) == -1) {
            document.getElementById('error_qty').innerHTML = "*qty must be positive number*";
            document.getElementById('error_qty').className = "alert alert-danger";
            count++;
        }
    }
    var cost = $('#cost').val();
    if (cost.trim() == "") {
        document.getElementById('error_cost').innerHTML = "*please enter number for cost*";
        document.getElementById('error_cost').className = "alert alert-danger";
        count++;
    }
    if (isNaN(cost) == true) {
        document.getElementById('error_cost').innerHTML = "*cost must be numeric*";
        document.getElementById('error_cost').className = "alert alert-danger";
        count++;
    } else {
        if (Math.sign(cost) == -1) {
            document.getElementById('error_cost').innerHTML = "*cost must be positive number*";
            document.getElementById('error_cost').className = "alert alert-danger";
            count++;
        }
    }
    var date1 = change_date_format(document.transferform.trans_date.value);
    if (document.transferform.trans_date.value == "" || isNaN(date1.getDate())) {
        document.getElementById('error_trans_date').innerHTML = "*Please enter a valide date*";
        document.getElementById('error_trans_date').className = "alert alert-danger";
        count++;
    }

    if ($('#from').val() == $('#to').val()) {
        document.getElementById('error_to').innerHTML = "*please select warehouse-shelf different than from*";
        document.getElementById('error_to').className = "alert alert-danger";
        count++;
    }

    if (count > 0) {
        return false;
    } else {
        return true;
    }
}

function change_date_format(date) {
    var datearray = date.split("-");
    var new_date_format = datearray[1] + "/" + datearray[0] + "/" + datearray[2]
    var date_new = new Date(new_date_format);
    return date_new;
}
