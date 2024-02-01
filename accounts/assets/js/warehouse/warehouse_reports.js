jQuery(document).ready(function ($) {
	// inputToDatepickerA($('#trans_date'));
    var getselected = $('#warehouses_select_report').children("option:selected").text();
    $.ajax({
        cache: false,
        type: 'POST',
        data: { 'whatselected': getselected },
        url: getAppURL('purchases/get_warehouse_shelfs'),
        success: function (data) {
            $('#shelf_select_report').empty();
            $('#shelf_select_report').append(`<option value=""></option>`);
            for (let i = 0; i < data.length; i++) {
                $('#shelf_select_report').append(`<option value="${data[i]}">
                        ${data[i]} </option>`);
            }
            $('#warehouse_text').val(getselected);
            $('#shelf_text').val(0);
        }
    });

    $('#warehouses_select_report').change(function (e) {
        var getselected = $('#warehouses_select_report').children("option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: getAppURL('purchases/get_warehouse_shelfs'),
            success: function (data) {
                $('#shelf_select_report').empty();
                $('#shelf_select_report').append(`<option value=""></option>`);
                for (let i = 0; i < data.length; i++) {
                    $('#shelf_select_report').append(`<option value="${data[i]}">
                        ${data[i]} </option>`);
                }
                var shelf = $('#shelf_select_report').children("option:selected").text();
                $('#warehouse_text').val(getselected);

            }
        });
    });

    $('#shelf_select_report').change(function (e) {
        var getselected = $('#shelf_select_report').children("option:selected").val();
        $('#shelf_text').val(getselected);
    });

    if($('#group_by_visibility').text() == "1"){
        document.getElementById("bglist").style.visibility="hidden";
    }

});