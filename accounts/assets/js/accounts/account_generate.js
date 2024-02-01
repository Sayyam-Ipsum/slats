$(document).ready(function () {
    $('#account_type').change(function (e) {
        var getselected = $("#account_type option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: "<?=site_url('Accounts/fetchaccountnumberfromDatabase');?>",
            success: function (data) {
                $('#account_number').val(data);
            }
        });
    });
});