$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "glyphicon-eye-close" ); 
            $('#show_hide_password i').removeClass( "glyphicon-eye-open");                   
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "glyphicon-eye-close");
            $('#show_hide_password i').addClass( "glyphicon-eye-open" );
        }
    });

    $(".perm").on('change', function () {
        var div = $("#"+$(this).closest('div').attr('id'));
        var perm_input_id = div.find('.perm_text').attr('id');
        console.log(perm_input_id);
        if ($(this).is(':checked')) {
            $("#"+perm_input_id).val($(this).val());
        } else {
            $("#"+perm_input_id).val('');
        }       
    });
});
