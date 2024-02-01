jQuery(document).ready(function ($) {
    inputToDatepickerA($('#from_date'));
    inputToDatepickerA($('#to_date'));
});

function validation() {
    var count = 0;
    var date1 = change_date_format($('#from_date').val());
    if ($('#from_date').val() == "" || isNaN(date1.getDate())) {
        document.getElementById('error_from_date').innerHTML = "*Please enter a valide date*";
        document.getElementById('error_from_date').className = "alert alert-danger";
        count++;
    }
    var date2 = change_date_format($('#to_date').val());
    if ($('#to_date').val() == "" || isNaN(date2.getDate())) {
        document.getElementById('error_to_date').innerHTML = "*Please enter a valide date*";
        document.getElementById('error_to_date').className = "alert alert-danger";
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