jQuery(document).ready(function ($) {
    if($('#user_id').text() != "1"){
        $('#SystemCurrency').prop('disabled', true).trigger("chosen:updated");
    }
});