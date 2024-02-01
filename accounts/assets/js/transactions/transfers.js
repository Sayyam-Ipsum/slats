let $transactionForm, $itemLookup, $transactionLines;
jQuery(document).ready(function ($) {
    $transactionForm = $('form#transactionForm');
    $transactionLines = $('tbody#transactionLines', $transactionForm);
    $itemLookup = $('#itemLookup', $transactionForm);
    inputToDatepickerA($('#trans_date, #value_date, #journal_date', $transactionForm));
    autoCompleteItem();
    bindLinesEvents();
    $('#bgback').click(function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
});
function accountSelected(account, $accountId) {
    $accountId.val(account.id);
}

function autoCompleteItem() {
    $itemLookup.on('keydown', function (e) {
        if (e.keyCode == 13) {
            // do not submit form on press of return key
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            return false;
        }
    }).autocomplete({
        serviceUrl: getAppURL('items/lookup_items_by_EAN_and_artical_nb'), appendTo: $itemLookup.parent()[0],
        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
        noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
        onSearchStart: function (params) {
        }, onSelect: function (result) {
            $.ajax({
                cache: false,
                type: 'POST',
                data: { 'item_id': result.row.id },
                url: getAppURL('items/get_item_qty'),
                success: function (data) {
                    // alert(data);
                    if (parseFloat(data) > 0) {
                        addLineToTransaction(result.row, true);
                    } else {
                        alert("Item not found in any warehouse!");
                    }

                }
            });
        }, transformResult: function (response) {
            if (response.length === 1) {
                $itemLookup.autocomplete('clear');
                addLineToTransaction(response[0], true);
            }
            return {
                suggestions: jQuery.map(response, function (item) {
                    return { value: item.suggestion, row: item };
                })
            };
        }
    });
}

function addLineToTransaction(item, newLine) {
    $itemLookup.val('');
    let $lastTr = jQuery('tr:last', $transactionLines);
    let idx = 1 + parseInt($lastTr.length > 0 ? $lastTr.attr('data-index') : -1);
    $transactionLines.append(ItemLineTpl.replace(/%d/g, String(idx)));
    if (newLine) {
        var $tr = jQuery('tr#item-' + idx, $transactionLines);
        jQuery('input.i-item_id', $tr).val(item.id);
        jQuery('td.i-EAN', $tr).text(item.EAN);
        jQuery('td.i-artical_number', $tr).text(item.artical_number);
        jQuery('input.i-cost', $tr).val('0');
        jQuery('input.i-qty', $tr).val('1').focus();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': item.id },
            url: getAppURL('warehouses/find_item_warehouse_shelf'),
            success: function (data) {
                jQuery('select.i-from', $tr).empty();
                for (var i in data) {
                    jQuery('select.i-from', $tr).append(`<option value="${i}">
                            ${data[i]} </option>`);
                }
                var getselected = jQuery('select.i-from', $tr).children("option:selected").val();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    data: { 'whatselected': getselected, 'item_id': item.id },
                    url: getAppURL('warehouses/get_max_qty'),
                    success: function (data) {
                        jQuery('td.i-max_qty', $tr).text(data);
                    }
                });
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
    jQuery('button.i-remove', $tr).on('click', function () {
        jQuery(this.parentNode.parentNode).remove();
    });
    var getselected = jQuery('select.i-from', $tr).children("option:selected").val();
    var item_id = jQuery('input.i-item_id', $tr).val();
    $.ajax({
        cache: false,
        type: 'POST',
        data: { 'whatselected': getselected, 'item_id': item_id },
        url: getAppURL('warehouses/get_max_qty'),
        success: function (data) {
            jQuery('td.i-max_qty', $tr).text(data);
        }
    });
    jQuery('select.i-from', $tr).change(function (e) {
        var getselected = jQuery('select.i-from', $tr).children("option:selected").val();
        var item_id = jQuery('input.i-item_id', $tr).val();
        $.ajax({
            cache: false,
            type: 'POST',
            data: { 'whatselected': getselected, 'item_id': item_id },
            url: getAppURL('warehouses/get_max_qty'),
            success: function (data) {
                jQuery('td.i-max_qty', $tr).text(data);
            }
        });
    });
}

function validation() {
    var count = 0;
    jQuery('tr', $transactionLines).each(function (i, tr) {
        var qty = jQuery('input.i-qty', tr).val();
        var max_qty = jQuery('td.i-max_qty', tr).text();
        if (parseFloat(qty) > parseFloat(max_qty) || parseFloat(qty) < 1) {
            error = jQuery('div.i-error_qty', tr);
            error.show().html("*Qty not available*");
            error.addClass("alert alert-danger");
            count++;
        }
        if (qty.trim() == "") {
            document.getElementById('error_transactionLines').innerHTML = "*please enter number for qty*";
            document.getElementById('error_transactionLines').className = "alert alert-danger";
            count++;
        }
        if (isNaN(qty) == true) {
            document.getElementById('error_transactionLines').innerHTML = "*qty must be numeric*";
            document.getElementById('error_transactionLines').className = "alert alert-danger";
            count++;
        } else {
            if (Math.sign(qty) == -1) {
                document.getElementById('error_transactionLines').innerHTML = "*qty must be positive number*";
                document.getElementById('error_transactionLines').className = "alert alert-danger";
                count++;
            }
        }
        var cost = jQuery('input.i-cost', tr).val();
        if (cost.trim() == "") {
            document.getElementById('error_transactionLines').innerHTML = "*please enter number for cost*";
            document.getElementById('error_transactionLines').className = "alert alert-danger";
            count++;
        }
        if (isNaN(cost) == true) {
            document.getElementById('error_transactionLines').innerHTML = "*cost must be numeric*";
            document.getElementById('error_transactionLines').className = "alert alert-danger";
            count++;
        } else {
            if (Math.sign(cost) == -1) {
                document.getElementById('error_transactionLines').innerHTML = "*cost must be positive number*";
                document.getElementById('error_transactionLines').className = "alert alert-danger";
                count++;
            }
        }
        if (jQuery('select.i-from', tr).val() == jQuery('select.i-to', tr).val()) {
            document.getElementById('error_transactionLines').innerHTML = "*please select warehouse-shelf different than from*";
            document.getElementById('error_transactionLines').className = "alert alert-danger";
            count++;
        }
    });
    var date1 = change_date_format(document.transactionForm.trans_date.value);
    if (document.transactionForm.trans_date.value == "" || isNaN(date1.getDate())) {
        document.getElementById('error_trans_date').innerHTML = "*Please enter a valide date*";
        document.getElementById('error_trans_date').className = "alert alert-danger";
        count++;
    }
    if (document.getElementById('transactionLines').rows.length == "0") {
        document.getElementById('error_transactionLines').innerHTML = "*Please enter an item*";
        document.getElementById('error_transactionLines').className = "alert alert-danger";
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