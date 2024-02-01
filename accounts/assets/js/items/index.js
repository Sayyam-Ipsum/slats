var filtered;
jQuery(document).ready(function () {
    window.itemsDT = null;
    let dtActionsHTML = '<a href="' + getAppURL('items/edit/') + '%d" class="btn bt-link btn-xs" title="Edit"><i style="color:#282828;" class="fas fa-edit"></i></a>' +
        '<a href="#" data-id="%d"\n' + '                                               data-action="items/delete"\n' + '                                               class="btn bt-link btn-sm deleteBtn" title="Delete">\n' + '                                                <i class="fas fa-trash"></i>\n' + '                                            </a>' +
        '<a href="' + getAppURL('barcodes/generate/') + '%d" class="btn bt-link btn-xs" title="Generate Barcode"><i style="color:#686868;" class="fas fa-print"></i></a>'
        + '<a href="' + getAppURL('items/activity/') + '%d" class="btn bt-link btn-xs" title="Activity"><i class="fas fa-info-circle text-danger"></i></a>'
        + '<a href="' + getAppURL('items/warehouses/') + '%d" class="btn bt-link btn-xs" title="Warehouses"><i style="color:#505050;" class="fas fa-warehouse"></i></a>';
    // +'<a href="' + getAppURL('items/add_opening_item/') + '%d" class="btn bt-link btn-sm" title="Opening Item" hidden><i class="glyphicon glyphicon-open text-danger"></i></a>';
    /***************************************************/
    var $dtTbl = $('#itemsTbl');
    BuildDataTableColumnSearch($dtTbl, 'itemsDT');

    EnhanceDataTableSearch(window.itemsDT = $dtTbl.DataTable({
        orderCellsTop: true,
        fixedHeader: {headerOffset: 0},
        searchDelay: _GST,
        lengthMenu: _dtLengthMenu,
        serverSide: true,
        processing: true,
        deferLoading: $dtTbl.attr('data-num-rows'),
        order: [[0, 'desc']],
        ajax: {
            url: getAppURL('items/index'), type: 'GET', searchDelay: _GST,

            complete: function () {

                check_active();

            }
        },
        columns: [{data: 'barcode'}, {data: 'EAN'}, {data: 'artical_number'}, {data: 'description'}, {data: 'brand'}, {data: 'purchase_cost'}, {data: 'cost'}, {data: 'qty'}, {data: 'id'}],
        columnDefs: [{
            targets: 8,
            orderable: false,
            createdCell: (td, accId) => $(td).addClass('text-right').html(dtActionsHTML.replace(/%d/g, parseInt(accId)))
        }],
        /*pagingType: "input", */
        stateSave: true,

        stateSaveCallback: function (settings, data) {
            localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
        },
        stateLoadCallback: function (settings, data) {
            return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
        },
        "stateSaveParams": function (settings, data) {
            var temp = {};
            $('#itemsTbl thead').find("tr:eq(1)").find('input').each(function (n) {
                $(this).attr('id', 'input_' + n);
                temp[$(this).attr('placeholder')] = document.getElementById($(this).attr('id')).value;
            });
            data.colsFilter = temp;
            data.page_nb = $('.paginate_input').val();

        },
        "stateLoadParams": function (settings, data) {
            $.each(data.colsFilter, function (key, val) {
                $('#itemsTbl thead input[placeholder="' + key + '"]').val(val);
            });
            // $('.paginate_input').val(data.page_nb);
        },
        "stateLoaded": function (settings, data) {

        },
        initComplete: function () {
            var table = $('#itemsTbl').DataTable();
            table.ajax.reload(null, false);
        }
    }), 2048);
    $('#group_action').on('click', function (e) {
        $('#group_action_modal').modal("show");
    });

    $('#brand').on('change', function (e) {
        // alert($('#brand').val());
        if ($('#brand').val() != '') {
            $.ajax({
                cache: false,
                type: 'POST',
                data: {'brand': $('#brand').val()},
                url: getAppURL('items/get_products_names_by_brand'),
                success: function (data) {
                    // alert(data);
                    $('#name_list').empty();
                    for (let i = 0; i < data.length; i++) {
                        $('#name_list').append(`<option value="${data[i].description}">
					${data[i].description} </option>`);
                    }
                }
            });
        } else {
            $('#name_list').empty();
        }
    });

    // $('#itemsTbl').on('click', function (e) {
    // 	console.log($('#filter_Barcode').val());
    // 	// console.log($('#itemsTbl thead tr:eq(1) input[placeholder="Search By Barcode"]').val());
    // 	// $('#itemsTbl thead').find("tr:eq(1)").find('input').each(function (n) {

    // 	// });
    // })

    check_active();
});

function check_active() {
    $("#itemsTbl tbody tr").each(function () {

        var data = $('#itemsTbl').DataTable().row($(this)).data();
        if (data) {
            if (parseInt(data['status']) === 1) {
                $(this).css('background-color', '#d3e3d4');
            }
        }
    });
}

function groupActionValidation() {
    var count = 0;
    if ($('#brand').val() == '' || $('#brand').val() == "0") {
        document.getElementById('error_brand').innerHTML = "*Please select a brand*";
        document.getElementById('error_brand').className = "alert alert-danger";
        document.getElementById('error_brand').style.display = "block";
        count++;
    } else {
        document.getElementById('error_brand').style.display = "none";
    }
    if ($('#name_list').val() == '' || $('#name_list').val() == "0") {
        document.getElementById('error_name_list').innerHTML = "*Please select a name*";
        document.getElementById('error_name_list').className = "alert alert-danger";
        document.getElementById('error_name_list').style.display = "block";
        count++;
    } else {
        document.getElementById('error_name_list').style.display = "none";
    }
    if ($('#profit').val() == '') {
        document.getElementById('error_profit').innerHTML = "*Please enter a value for profit*";
        document.getElementById('error_profit').className = "alert alert-danger";
        document.getElementById('error_profit').style.display = "block";
        count++;
    } else {
        if (isNaN($('#profit').val()) == true) {
            document.getElementById('error_profit').innerHTML = "*Please enter a numeric value*";
            document.getElementById('error_profit').className = "alert alert-danger";
            document.getElementById('error_profit').style.display = "block";
            count++;
        } else {
            document.getElementById('error_profit').style.display = "none";
        }
    }
    if (count > 0) {
        return false;
    } else {
        return true;
    }
}