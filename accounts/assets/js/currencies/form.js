jQuery(document).ready(function ($) {
    $.ajax({
        cache: false,
        type: 'POST',
        data: {},
        url: getAppURL('currencies/get_local_currency'), 
        success: function (data) {
            var $id= $('#id').val();
            if($id == data){
                document.getElementById('currency_rate').setAttribute("readonly", true);
            }
        }
    });
});