let $transactionForm, $itemLookup, $transactionLines;
jQuery(document).ready(function ($) {
    $transactionForm = $('form#transactionForm');
    $transactionLines = $('tbody#transactionLines', $transactionForm);
    $itemLookup = $('#itemLookup', $transactionForm);
    inputToDatepickerA($('#trans_date', $transactionForm));
    autoCompleteItem();
    bindLinesEvents();
    $('#bgback').click(function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
    $transactionForm.on('keydown', function (e) {
        if (e.keyCode == 13) {
            // do not submit form on press of enter key
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            return false;
        }
    });
    $('form#itemsform').on('keydown', function (e) {
        if (e.keyCode == 13) {
            // do not submit form on press of enter key
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            return false;
        }
    });
    $('#modal_close1').on('click', function (e) {
        $itemLookup.val('');
    });
    $('#modal_close2').on('click', function (e) {
        $itemLookup.val('');
    });
    $('#generate').on('click', function (e) {
        var barcode = $('#barcode').val();
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        if (barcode == '') {
            $.ajax({
                cache: false,
                type: 'POST',
                data: {
                    '': ''
                },
                url: getAppURL('Items/fetchitemnumberfromDatabase'),
                success: function (data) {
                    $('#barcode').val(data);
                }
            });
        }
    });
    get_warehouse_shelfs();
    $('#default_warehouse').change(function (e) {
        get_warehouse_shelfs();
    });
});

function autoCompleteItem() {
    $itemLookup.on('keydown', function (e) {
    }).autocomplete({
        serviceUrl: getAppURL('items/lookup_items_by_EAN_and_artical_nb'), appendTo: $itemLookup.parent()[0],
        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
        noSuggestionNotice: 'No Suggestions'
        , type: 'GET', dataType: 'JSON', deferRequestBy: 480,
        onSearchStart: function (params) {
        },
        onSelect: function (result) {
            addLineToTransaction(result.row, true);
        },
        transformResult: function (response) {
            if (JSON.stringify(response) == "[]") {
                $('#EAN').val($itemLookup.val());
                document.getElementById("select_div").style.display = "none";
                $('div#ItemFormModal').modal('show');
                $('#save_item').on('click', function (e) {
                    var EAN = $('#EAN').val();
                    $.ajax({
                        cache: false,
                        type: 'POST',
                        data: {
                            'EAN': EAN
                        },
                        url: getAppURL('Items/check_if_EAN_exists'),
                        success: function (data) {
                            if (data == "0") {
                                $("#itemsform").each(function () {
                                    $input = $(this).serializeArray();
                                });
                                if (ItemModalvalidation() == true) {
                                    $.ajax({
                                        cache: false,
                                        type: 'POST',
                                        data: { 'form_data': $input },
                                        url: getAppURL('items/add_item_by_modal'),
                                        success: function (data) {
                                            $.ajax({
                                                cache: false,
                                                type: 'POST',
                                                data: {
                                                    'EAN': EAN
                                                },
                                                url: getAppURL('Items/get_item_data_by_EAN'),
                                                success: function (data) {
                                                    $itemLookup.val('');
                                                    setTimeout(() => $('div#ItemFormModal').modal('hide'), 500);
                                                    addLineToTransaction(data, true);
                                                }
                                            });
                                        }
                                    });
                                }
                            } else {
                                $itemLookup.val('');
                                alert("This EAN already exits");
                                setTimeout(() => $('div#ItemFormModal').modal('hide'), 500);
                            }
                        }
                    });
                });
                return {
                    suggestions: jQuery.map(response, function (item) {
                        return {};
                    })
                };
            } else {
                if (response.length === 1) {
                    if (response[0]['artical_number'] === $itemLookup.val() || response[0]['EAN'] === $itemLookup.val()) {
                        $itemLookup.autocomplete('clear');
                        addLineToTransaction(response[0], true);
                    }
                }
                return {
                    suggestions: jQuery.map(response, function (item) {
                        return { value: item.suggestion, row: item };
                    })
                };
            }
        },
    });
}

function addLineToTransaction(item, newLine) {
    $itemLookup.val('');
    let $lastTr = jQuery('tr:last', $transactionLines);
    let idx = 1 + parseInt($lastTr.length > 0 ? $lastTr.attr('data-index') : -1);
    $transactionLines.append(ItemLineTpl.replace(/%d/g, String(idx)));
    if (newLine) {
        // alert($('#default_warehouse').val());
        var $tr = jQuery('tr#item-' + idx, $transactionLines);
        jQuery('input.i-item_id', $tr).val(item.id);
        jQuery('td.i-artical_number', $tr).text(item.artical_number);
        jQuery('td.i-EAN', $tr).text(item.EAN);
        jQuery('input.i-qty', $tr).val('1');
        jQuery('select.i-warehouse', $tr).val($('#default_warehouse').val());
        var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: getAppURL('purchases/get_warehouse_shelfs'),
            success: function (data) {
                jQuery('select.i-shelf', $tr).empty();
                for (let i = 0; i < data.length; i++) {
                    jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">
							${data[i]} </option>`);
                }
                jQuery('select.i-shelf', $tr).val($('#default_shelf').val());
            }
        });
        bindLineEvents($tr);
    }
}

function bindLinesEvents() {
    jQuery('tr', $transactionLines).each(function (i, tr) {
        bindLineEvents(jQuery(tr));
    });
}

function bindLineEvents($tr) {
    jQuery('select.i-warehouse', $tr).change(function (e) {
        var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected },
            url: getAppURL('purchases/get_warehouse_shelfs'),
            success: function (data) {
                jQuery('select.i-shelf', $tr).empty();
                for (let i = 0; i < data.length; i++) {
                    jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">
						${data[i]} </option>`);
                }
            }
        });
    });
    jQuery('button.i-remove', $tr).on('click', function () {
        jQuery(this.parentNode.parentNode).remove();
    });
}

function get_warehouse_shelfs() {
    var getselected = $('#default_warehouse').children("option:selected").text();
    $.ajax({
        cache: false,
        type: 'POST',
        data: { 'whatselected': getselected },
        url: getAppURL('purchases/get_warehouse_shelfs'),
        success: function (data) {
            $('#default_shelf').empty();
            for (let i = 0; i < data.length; i++) {
                $('#default_shelf').append(`<option value="${data[i]}">
                        ${data[i]} </option>`);
            }
            $('#default_shelf').selectpicker('refresh');
            $('#default_shelf').val(data[0]);
            $('#default_shelf').selectpicker('render');
        }
    });
}

function ItemModalvalidation() {
    var count = 0;
    $.ajax({
        cache: false,
        type: 'POST',
        data: { 'barcode': document.itemsform.barcode.value },
        url: getAppURL('Items/check_if_barcode_exists'),
        success: function (data) {
            console.log(data);
            if (data != "0") {
                document.getElementById('error_barcode').innerHTML = "*Please Enter a new barcode: barcode must be unique*";
                document.getElementById('error_barcode').className = "alert alert-danger";
                count++;
            }
        }
    });
    $.ajax({
        cache: false,
        type: 'POST',
        async: false,
        data: { 'EAN': $('#EAN').val() },
        url: getAppURL('Items/check_if_EAN_exists'),
        success: function (data) {
            if (data != "0") {
                document.getElementById('error_EAN').innerHTML = "*Please Enter a new EAN: it must be unique*";
                document.getElementById('error_EAN').className = "alert alert-danger";
                count++;
            }
        }
    });
    var id = $('#id').val();
    if (document.itemsform.barcode.value == "") {
        document.getElementById('error_barcode').innerHTML = "*Please Enter or generate a Barcode*";
        document.getElementById('error_barcode').className = "alert alert-danger";
        count++;
    }
    if (document.itemsform.description.value == "") {
        document.getElementById('error_description').innerHTML = "*Please enter a Name*";
        document.getElementById('error_description').className = "alert alert-danger";
        count++;
    }
    if (document.itemsform.EAN.value == "") {
        document.getElementById('error_EAN').innerHTML = "*Please enter a EAN*";
        document.getElementById('error_EAN').className = "alert alert-danger";
        count++;
    }
    if (document.itemsform.artical_number.value == "") {
        document.getElementById('error_artical_number').innerHTML = "*Please enter a Artical Number*";
        document.getElementById('error_artical_number').className = "alert alert-danger";
        count++;
    }
    if (isNaN(document.itemsform.cost_modal.value) == true) {
        document.getElementById('error_cost').innerHTML = "*Please enter a numeric value*";
        document.getElementById('error_cost').className = "alert alert-danger";
        count++;
    }
    if (isNaN(document.itemsform.purchase_cost.value) == true) {
        document.getElementById('error_purchase_cost').innerHTML = "*Please enter a numeric value*";
        document.getElementById('error_purchase_cost').className = "alert alert-danger";
        count++;
    }
    if (isNaN(document.itemsform.open_cost.value) == true) {
        document.getElementById('error_open_cost').innerHTML = "*Please enter a numeric value*";
        document.getElementById('error_open_cost').className = "alert alert-danger";
        count++;
    }
    if (isNaN(document.itemsform.weight.value) == true) {
        document.getElementById('error_weight').innerHTML = "*Please enter a numeric value*";
        document.getElementById('error_weight').className = "alert alert-danger";
        count++;
    }
    if (count > 0) {
        return false;
    } else {
        return true;
    }
}