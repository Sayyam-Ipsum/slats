jQuery(document).ready(function () {
    $('#changeUserPassBtn').on('click', function (e) {
        $.ajax({
            cache: false,
            type: 'POST',
            data: {},
            url: getAppURL('users/get_logedin_user_pass'),
            success: function (data) {
                $('#modalUserPass').val(data);
                $('#changeUserPassModal').modal('show');
            }
        });
    });

});

function changeUserPassFormValidation() {
    var count = 0;
    if ($('#modal_old_pass').val() == '') {
        document.getElementById('error_modal_old_pass').innerHTML = "*This Feild is Required*";
        document.getElementById('error_modal_old_pass').className = "alert alert-danger";
        $('#error_modal_old_pass').show();
        count++;
    } else {
        $('#error_modal_old_pass').hide();
        if ($('#modal_old_pass').val() == $('#modalUserPass').val()) {
            $('#error_modal_old_pass').hide()
        } else {
            document.getElementById('error_modal_old_pass').innerHTML = "*Wrong Password*";
            document.getElementById('error_modal_old_pass').className = "alert alert-danger";
            $('#error_modal_old_pass').show();
            // count++;
        }
    }
    if ($('#modal_new_pass').val() == '') {
        document.getElementById('error_modal_new_pass').innerHTML = "*This Feild is Required*";
        document.getElementById('error_modal_new_pass').className = "alert alert-danger";
        $('#error_modal_new_pass').show();
        count++;
    } else {
        $('#error_modal_new_pass').hide();
    }
    
    if (count > 0) {
        return false;
    } else {
        return true;
    }
}