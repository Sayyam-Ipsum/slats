let $transactionForm, $itemLookup, $transactionLines;jQuery(document).ready(function ($) {    $submitted = 0;    window.addEventListener("beforeunload", function (event) {		if ($submitted === 0) {			event.preventDefault();			event.returnValue = "Leave page?";		}	});	window.addEventListener("unload", function (event) {		$.ajax({			cache: false,			type: 'POST',			data: { 'trans_id': $('#id').val() },			url: getAppURL('quotations/exit_page_js'),			success: function (data) {			}		});	});	window.addEventListener("load", function (event) {		if (window.location.href !== getAppURL('orders/add')) {			$.ajax({				cache: false,				type: 'POST',				data: { 'trans_id': $('#id').val() },				url: getAppURL('quotations/open_page_js'),				success: function (data) {				}			});		}	});    $transactionForm = $('form#transactionForm');    $transactionLines = $('tbody#transactionLines', $transactionForm);    $itemLookup = $('#itemLookup', $transactionForm);    inputToDatepickerA($('#trans_date, #value_date, #journal_date', $transactionForm));    let $acctLkupFrom = $('#acctLkupFrom', $transactionForm), $acctLkupTo = $('#acctLkupTo', $transactionForm),        $accountId = $('#account_id', $transactionForm), $account2Id = $('#account2_id', $transactionForm);    autoCompleteAccount($acctLkupTo, $accountId, accountSelected);    autoCompleteItem();    bindLinesEvents();    getcurrencyrate();    $('#sub_total').val('0');    if ($('#disc').val() == '') {        $('#disc').val('0');    }    calculateSubtotalAndTotal($('#disc'));    $('#currency_id_list').prop('disabled', true).trigger("chosen:updated");    // alert(getAppURL('quotations/add_to_order/'));    $result = window.location.href.startsWith(getAppURL('quotations/add_to_order/'));    if ($result == true) {        document.getElementById("print").style.visibility = "hidden";        document.getElementById("to_invoice").style.visibility = "hidden";        document.getElementById("customer_print").style.visibility = "hidden";    }    if (window.location.href == getAppURL('orders/add')) {        document.getElementById("print").style.visibility = "hidden";        document.getElementById("to_invoice").style.visibility = "hidden";        document.getElementById("customer_print").style.visibility = "hidden";        $('#delivery_charge').val(0);        $('#pfand').val(0);        $('#final_total').val('0');        $('#final_profit').val('0');    } else {        $.ajax({            cache: false,            type: 'POST',            data: { 'whatselected': $('#currency_id').val() },            url: getAppURL('currencies/get_currency_rate'),            success: function (data) {                var result = data.split('-');                var cur_read = result[1];                if (cur_read == "readonly") {                    document.getElementById('currency_rate').setAttribute("readonly", true);                } else {                    document.getElementById('currency_rate').removeAttribute("readonly");                }            }        });        $('#transItemsTbl tbody tr').each(function (e) {            $(this).find('td input').attr('readonly', true);        });    }    // $('#bgback').on('click', function (e) {    //     e.preventDefault();    //     window.location.href = document.referrer;    // });    $('#driver_list').on('change', function (e) {        $('#driver_id').val($('#driver_list').val())    });    $('#employee_list').on('change', function (e) {        $('#employee_id').val($('#employee_list').val())    });    delivery_type_action();    $('#delivery_type').on('change', function (e) {        delivery_type_action();    });    // $('#trans_items_table thead').on('click', function (e) {    // 	itemsAvailabilitytable();    // });});function accountSelected(account, $accountId) {    $accountId.val(account.id);}function autoCompleteAccount($acctLkup, $accountId, onResultSelection) {    $acctLkup.autocomplete({        serviceUrl: getAppURL('sales/lookup_customers_accounts'), appendTo: $acctLkup.parent()[0],        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,        noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,        onSearchStart: function (params) {        }, onSelect: function (result) {            onResultSelection(result.row, $accountId);            let $acctLkupFrom = $('#acctLkupFrom', $transactionForm);            let $account2Id = $('#account2_id', $transactionForm);            var id = $("#account_id").val();            $.ajax({                cache: false,                type: 'POST',                data: { 'whatselected': id },                url: getAppURL('purchases/get_account_currency'),                success: function (data) {                    $('#currency_id_list').val(data);                    $('#currency_id').val(data);                    $.ajax({                        cache: false,                        type: 'POST',                        data: { 'whatselected': data },                        url: getAppURL('sales/get_sales_account_with_the_same_currency'),                        success: function (data) {                            $('#acctLkupFrom').val(JSON.stringify(data["description"]).replace(/"/g, ""));                            $('#account2_id').val(JSON.stringify(data["id"]).replace(/"/g, ""));                        }                    });                    $.ajax({                        cache: false,                        type: 'POST',                        data: { 'whatselected': data },                        url: getAppURL('currencies/get_currency_rate'),                        success: function (data) {                            var result = data.split('-');                            var cur_rate = result[0];                            var cur_read = result[1];                            $('#currency_id_list').prop('disabled', true).trigger("chosen:updated");                            $('#currency_rate').val(cur_rate);                            if (cur_read == "readonly") {                                document.getElementById('currency_rate').setAttribute("readonly", true);                            } else {                                document.getElementById('currency_rate').removeAttribute("readonly");                            }                            var subtot1 = 0;                            jQuery('tr', $transactionLines).each(function (i, tr) {                                var currency_rate = $('#currency_rate').val();                                var profit = jQuery('input.i-item_profit', tr).val();                                var cost = parseFloat(jQuery('input.i-item_cost', tr).val());                                jQuery('input.i-cost', tr).val((cost / (parseFloat(currency_rate))).toFixed(2));                                var price = ((cost * (1 + (parseFloat(profit) / 100)))) / parseFloat(currency_rate);                                jQuery('input.i-price', tr).val(price.toFixed(2));                                let net_cost = (parseFloat(jQuery('input.i-price', tr).val()) * (1 - (jQuery('input.i-discount', tr).val() / 100)));                                let total = jQuery('input.i-qty', tr).val() * net_cost;                                let subtot = parseFloat(jQuery('input.i-price', tr).val()) * parseFloat(jQuery('input.i-qty', tr).val());                                jQuery('td.i-total', tr).text(total.toFixed(2));                                jQuery('td.i-subtotal', tr).text(subtot.toFixed(2));                                subtot1 = subtot1 + parseFloat(jQuery('td.i-total', tr).text());                            });                            $('#sub_total').val(subtot1.toFixed(2));                            var tva = $('#TVA1').val();                            var ftot = (parseFloat(subtot1) + parseFloat($('#delivery_charge').val()) - parseFloat($('#disc').val())) * ((1 + (parseFloat(tva) / 100))) + parseFloat($('#pfand').val());                            $('#final_total').val(ftot.toFixed(2));                        }                    });                }            });        }, transformResult: function (response) {            return {                suggestions: jQuery.map(response, function (account) {                    return { value: account.description, row: account };                })            };        }    });}function autoCompleteItem() {    $itemLookup.on('keydown', function (e) {        if (e.keyCode == 13) {            // do not submit form on press of return key            e.preventDefault();            e.stopPropagation();            e.stopImmediatePropagation();            return false;        }    }).autocomplete({        serviceUrl: getAppURL('items/lookup_items_by_EAN_and_artical_nb'), appendTo: $itemLookup.parent()[0],        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,        noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,        onSearchStart: function (params) {        }, onSelect: function (result) {            $.ajax({                cache: false,                type: 'POST',                data: { 'whatselected': result.row.id },                url: getAppURL('warehouses/get_warehouses_for_item'),                success: function (data) {                    var warehouse_check = data;                    $.ajax({                        cache: false,                        type: 'POST',                        data: { 'item_id': result.row.id },                        url: getAppURL('items/get_item_qty'),                        success: function (data) {                            if (parseFloat(data) > 0 && JSON.stringify(warehouse_check) != "[]") {                                var currency_rate = $('#currency_rate').val();                                if (currency_rate != '') {                                    addLineToTransaction(result.row, true);                                } else {                                    alert("Please, you must select customer account first.")                                }                            } else {                                $.ajax({                                    cache: false,                                    type: 'POST',                                    data: {},                                    url: getAppURL('warehouses/get_order_warehouses'),                                    success: function (data) {                                        if (data == []) {                                            alert("*No Warehouses For Orders*")                                        } else {                                            var currency_rate = $('#currency_rate').val();                                            if (currency_rate != '') {                                                addMissingItemLineToTransaction(result.row, true);                                            } else {                                                alert("Please, you must select customer account first.")                                            }                                        }                                    }                                });                            }                        }                    });                }            });        }, transformResult: function (response) {            if (response.length === 1) {                if (response[0]['artical_number'] === $itemLookup.val() || response[0]['EAN'] === $itemLookup.val()) {                    $itemLookup.autocomplete('clear');                    addLineToTransaction(response[0], true);                }            }            return {                suggestions: jQuery.map(response, function (item) {                    return { value: item.suggestion, row: item };                })            };        }    });}function addMissingItemLineToTransaction(item, newLine) {    $itemLookup.val('');    let $lastTr = jQuery('tr:last', $transactionLines);    let idx = 1 + parseInt($lastTr.length > 0 ? $lastTr.attr('data-index') : -1);    $transactionLines.append(ItemLineTpl.replace(/%d/g, String(idx)));    if (newLine) {        var currency_rate = $('#currency_rate').val();        var $tr = jQuery('tr#item-' + idx, $transactionLines);        jQuery('input.i-item_id', $tr).val(item.id);        jQuery('td.i-name', $tr).text(item.description);        jQuery('td.i-brand', $tr).text(item.brand);        jQuery('td.i-artical_number', $tr).text(item.artical_number);        if (item.qty_multiplier == '2') {			jQuery('input.i-qty', $tr).val('2').focus();		}else{			jQuery('input.i-qty', $tr).val('1').focus();		}	        jQuery('input.i-item_cost', $tr).val(item.cost);        jQuery('input.i-cost', $tr).val((parseFloat(item.cost) / parseFloat(currency_rate)).toFixed(2));        jQuery('input.i-item_profit', $tr).val(0);        var tva = $('#TVA1').val();        var price = (parseFloat(item.cost) * (1 + (parseFloat(tva) / 100))) / parseFloat(currency_rate);        jQuery('input.i-price', $tr).val(price.toFixed(2));        // jQuery('input.i-item_price', $tr).val(item.price);        var cost = parseFloat(item.cost) / parseFloat(currency_rate);        var profit = parseFloat(price) - parseFloat(cost);        jQuery('input.i-profit', $tr).val(profit.toFixed(2));        jQuery('input.i-discount', $tr).val('0');        jQuery('td.i-total', $tr).text(jQuery('input.i-price', $tr).val());        jQuery('td.i-subtotal', $tr).text(jQuery('input.i-price', $tr).val());        var id = jQuery('input.i-item_id', $tr).val();        var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();        $.ajax({            cache: false,            type: 'POST',            data: { 'whatselected': getselected },            url: getAppURL('purchases/get_warehouse_shelfs'),            success: function (data) {                jQuery('select.i-shelf', $tr).empty();                for (let i = 0; i < data.length; i++) {                    jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">							${data[i]} </option>`).val(data[0]);                }            }        });        bindLineEvents($tr);    }}function addLineToTransaction(item, newLine) {    $itemLookup.val('');    let $lastTr = jQuery('tr:last', $transactionLines);    let idx = 1 + parseInt($lastTr.length > 0 ? $lastTr.attr('data-index') : -1);    $transactionLines.append(ItemLineTpl.replace(/%d/g, String(idx)));    var nb = jQuery('td.i-nb', jQuery('tr#item-' + $lastTr.attr('data-index'), $transactionLines)).text();    if (newLine) {        var currency_rate = $('#currency_rate').val();        var $tr = jQuery('tr#item-' + idx, $transactionLines);        jQuery('input.i-item_id', $tr).val(item.id);        jQuery('td.i-nb', $tr).text(Number(nb) + 1);        jQuery('td.i-name', $tr).text(item.description);        jQuery('td.i-brand', $tr).text(item.brand);        jQuery('td.i-artical_number', $tr).text(item.artical_number);        if (item.qty_multiplier == '2') {			jQuery('input.i-qty', $tr).val('2').focus();		}else{			jQuery('input.i-qty', $tr).val('1').focus();		}	        jQuery('input.i-item_cost', $tr).val(item.cost);        jQuery('input.i-cost', $tr).val((parseFloat(item.cost) / parseFloat(currency_rate)).toFixed(2));        jQuery('input.i-item_profit', $tr).val(0);        var tva = $('#TVA1').val();        var price = (parseFloat(item.cost) * (1 + (parseFloat(tva) / 100))) / parseFloat(currency_rate);        jQuery('input.i-price', $tr).val(price.toFixed(2));        // jQuery('input.i-item_price', $tr).val(item.price);        var cost = parseFloat(item.cost) / parseFloat(currency_rate);        var profit = parseFloat(price) - parseFloat(cost);        jQuery('input.i-profit', $tr).val(profit.toFixed(2));        jQuery('input.i-discount', $tr).val('0');        jQuery('td.i-total', $tr).text(jQuery('input.i-price', $tr).val());        jQuery('td.i-subtotal', $tr).text(jQuery('input.i-price', $tr).val());        var id = jQuery('input.i-item_id', $tr).val();        var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();        $.ajax({            cache: false,            type: 'POST',            data: { 'whatselected': getselected },            url: getAppURL('purchases/get_warehouse_shelfs'),            success: function (data) {                jQuery('select.i-shelf', $tr).empty();                for (let i = 0; i < data.length; i++) {                    jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">							${data[i]} </option>`).val(data[0]);                }            }        });        bindLineEvents($tr);    }}function bindLinesEvents() {    jQuery('tr', $transactionLines).each(function (i, tr) {        bindLineEvents(jQuery(tr));    });}function bindLineEvents($tr) {    var id = jQuery('input.i-item_id', $tr).val();    var warehouse = jQuery('select.i-warehouse', $tr).val();    jQuery('select.i-warehouse', $tr).on('change', function (e) {        var getselected = jQuery('select.i-warehouse', $tr).children("option:selected").text();        $.ajax({            cache: false,            type: 'POST',            data: { 'whatselected': getselected },            url: getAppURL('purchases/get_warehouse_shelfs'),            success: function (data) {                jQuery('select.i-shelf', $tr).empty();                for (let i = 0; i < data.length; i++) {                    jQuery('select.i-shelf', $tr).append(`<option value="${data[i]}">							${data[i]} </option>`).val(data[0]);                }            }        });    });    $('#transItemsTbl tbody tr').click(function () {        $('#row_id').val($(this).attr('id'));        var itemid = $(this).find("input[class='d-none i-item_id']").val();        var row_nb = $(this).find("td:eq(1)").text();        var itemname = $(this).find("td:eq(2)").text();        var itembrand = $(this).find("td:eq(3)").text();        var itemartical_nb = $(this).find("td:eq(4)").text();        $.ajax({            cache: false,            type: 'POST',            data: { 'item_id': itemid },            url: getAppURL('warehouses/get_item_availabilty_table_with_alternatives'),            success: function (data) {                $("#activityitemTbl td").remove();                if (itemid == undefined) {                    document.getElementById("item_info").style.display = "none";                } else {                    document.getElementById("item_info").style.display = "block";                    document.getElementById("item_info").innerHTML = "Product: " + itemname + " - " + itembrand + " - " + itemartical_nb;                }                if (data == "no") {                    r = "<tr><td colspan='5' style='text-align: center;'> No Data Found </td></tr>";                    $("#activityitemTbl tbody").append(r);                } else {                    for (let i = 0; i < data.length; i++) {                        if (data[i].artical_number === itemartical_nb) {                            var tr = "<tr>";                        } else {                            var tr = "<tr style='background: #FEC5BF'>";                        }                        var input_id = '<input type="text" name="item_id" value="' + data[i].item_id + '" hidden>';                        r = tr + "<td>" + data[i].brand + input_id +                            "</td><td>" + data[i].artical_number + "</td><td>"                            + data[i].warehouse + "</td><td>"                            + data[i].shelf + "</td><td>"                            + data[i].total_qty + "</td></tr>";                        $("#activityitemTbl tbody").append(r);                    }                }                // if (window.location.href == getAppURL('orders/add')) {                $("#activityitemTbl tr").on('click', function (e) {                    var row_id = $('#row_id').val();                    var trans_item_id = $('#transItemsTbl tbody tr#' + row_id).find('td').find("input[class='d-none i-item_id']").val();                    var activity_item_id = $(this).find('td').find("input[name='item_id']").val();                    if (trans_item_id == activity_item_id) {                        var w = $(this).find('td').eq(2).text();                        var s = $(this).find('td').eq(3).text();                        $('#transItemsTbl tbody tr#' + row_id).find('td').eq(6).find('select').val(w);                        var shelf = $('#transItemsTbl tbody tr#' + row_id).find('td').eq(7).find('select');                        $.ajax({                            cache: false,                            type: 'POST',                            data: { 'whatselected': w },                            url: getAppURL('purchases/get_warehouse_shelfs'),                            success: function (data) {                                shelf.empty();                                for (let i = 0; i < data.length; i++) {                                    shelf.append(`<option value="${data[i]}">                                            ${data[i]} </option>`);                                }                                shelf.val(s);                            }                        });                    }                    //  else {                    //     $.ajax({                    //         cache: false,                    //         type: 'POST',                    //         data: { 'item_id': activity_item_id },                    //         url: getAppURL('items/get_item_data_by_item_id'),                    //         success: function (data) {                    //             // console.log(data);                    //             $('#transItemsTbl tbody tr#' + row_id).remove();                    //             addLineToTransaction(data, true);                    //             $("#activity_tbody").empty();                    //             let r = "<tr><td colspan='5' style='text-align: center;'> No Data Found </td></tr>";                    //             $("#activityitemTbl tbody").append(r);                    //             $('#row_id').val('');                    //             $('#item_info').text('');                    //         }                    //     });                    // }                })                // }            }        });    });    jQuery('button.i-remove', $tr).on('click', function () {        jQuery(this.parentNode.parentNode).remove();        calculateLineTotal();    });    $('#TVA1').on('change', upate_line_price);    $('#currency_rate').on('change', upate_line_price);    var $qty = jQuery('input.i-qty', $tr).on('change', calculateLineTotal);    var $price = jQuery('input.i-price', $tr).on('change', calculateLineTotal);    var $item_profit = jQuery('input.i-item_profit', $tr).on('change', upate_line_price);    var $cost = jQuery('input.i-cost', $tr).on('change', calculateLineTotal);    var $discount = jQuery('input.i-discount', $tr).on('change', calculateLineTotal);    var $total_discount = $('#disc').on('change', calculateLineTotal);    var $pfand = $('#pfand').on('change', calculateLineTotal);    var $delivery_charge = $('#delivery_charge').on('change', calculateLineTotal);    calculateLineTotal();    function calculateLineTotal() {        let net_cost = (parseFloat($price.val()) * (1 - ($discount.val() / 100)));        let total = $qty.val() * net_cost;        let subtot = parseFloat($price.val()) * parseFloat($qty.val());        jQuery('td.i-subtotal', $tr).text(subtot.toFixed(2));        jQuery('td.i-total', $tr).text(total.toFixed(2));        // let tot_profit = parseFloat($qty.val()) * (parseFloat($price.val()) - parseFloat(jQuery('input.i-cost', $tr).val())) * (1 - ($discount.val() / 100));        //tot_profit = (((parseFloat(jQuery('input.i-price', $tr).val()) - parseFloat(jQuery('input.i-cost', $tr).val())) * (1 - (parseFloat($discount.val()) / 100))) * 100) / (parseFloat(jQuery('input.i-cost', $tr).val()));        tot_profit = parseFloat(total.toFixed(2)) - (parseFloat(jQuery('input.i-cost', $tr).val()) * parseFloat($qty.val()));        jQuery('td.i-total_profit', $tr).text(tot_profit.toFixed(2));        var currency_rate = $('#currency_rate').val();        var price = parseFloat(jQuery('input.i-price', $tr).val()) / parseFloat(currency_rate);        $.ajax({            cache: false,            type: 'POST',            data: { 'item_id': id },            url: getAppURL('items/get_item_cost_LC'),            success: function (data) {                var cost = parseFloat(data) / parseFloat(currency_rate);                let net_price = (parseFloat(price) * (1 - ($discount.val() / 100)));                var profit = parseFloat(net_price) - parseFloat(cost);                jQuery('input.i-profit', $tr).val(profit.toFixed(2));                calculateTotalprofit();            }        });        calculateSubtotalAndTotal($total_discount);    }    function upate_line_price() {        jQuery('tr', $transactionLines).each(function (i, tr) {            var currency_rate = $('#currency_rate').val();            var profit = jQuery('input.i-item_profit', tr).val();            var cost = parseFloat(jQuery('input.i-item_cost', tr).val());            jQuery('input.i-cost', tr).val((cost / (parseFloat(currency_rate))).toFixed(2));            var price = ((cost * (1 + (parseFloat(profit) / 100)))) / parseFloat(currency_rate);            jQuery('input.i-price', tr).val(price.toFixed(2));            calculateLineTotal();        });    }    jQuery('input.i-price', $tr).on('change', function (e) {        var cost = jQuery('input.i-cost', $tr).val();        var price = jQuery('input.i-price', $tr).val();        if (parseFloat(cost) > 0) {            var item_prof = ((parseFloat(price) / parseFloat(cost)) - 1) * (100);        } else {            var item_prof = 0;        }        jQuery('input.i-item_profit', $tr).val(item_prof.toFixed(3));        calculateLineTotal();    });}function calculateTotalprofit() {    var profit_total = 0;    jQuery('tr', $transactionLines).each(function (i, tr) {        profit_total += parseFloat(jQuery('input.i-profit', tr).val());    });    $('#final_profit').val(profit_total.toFixed(2));}function calculateSubtotalAndTotal($total_discount) {    var subtot = 0;    jQuery('tr', $transactionLines).each(function (i, tr) {        subtot = subtot + parseFloat(jQuery('td.i-total', tr).text());    });    $('#sub_total').val(subtot.toFixed(2));    $('#final_total').val(((subtot + parseFloat($('#delivery_charge').val()) - parseFloat($total_discount.val())) * (1 + (parseFloat($('#TVA1').val()) / 100)) + parseFloat($('#pfand').val())).toFixed(2));}function getcurrencyrate() {    $('#currency_id').change(function (e) {        var id = $("#currency_id").val();        $.ajax({            cache: false,            type: 'POST',            data: { 'whatselected': id },            url: getAppURL('currencies/get_currency_rate'),            success: function (data) {                var result = data.split('-');                $('#currency_rate').val(result[0]);            }        });    });}function validation() {    var count = 0;    jQuery('tr', $transactionLines).each(function (i, tr) {        var qty = jQuery('input.i-qty', tr).val();        var price = jQuery('input.i-price', tr).val();        var item_profit = jQuery('input.i-item_profit', tr).val();        var discount = jQuery('input.i-discount', tr).val();        if (isNaN(qty) == true || isNaN(price) == true || isNaN(discount) == true || isNaN(item_profit) == true) {            document.getElementById('error_transactionLines').innerHTML = "*All input fields must be numeric*";            document.getElementById('error_transactionLines').className = "alert alert-danger";            count++;        } else {            if (Math.sign(qty) == -1 || Math.sign(price) == -1 || Math.sign(discount) == -1 || Math.sign(item_profit) == -1) {                document.getElementById('error_transactionLines').innerHTML = "*All input fields must be positive numbers*";                document.getElementById('error_transactionLines').className = "alert alert-danger";                count++;            }        }        if (qty.trim() == "" || price.trim() == "" || discount.trim() == "" || item_profit.trim() == "") {            document.getElementById('error_transactionLines').innerHTML = "*All input fields must be numeric*";            document.getElementById('error_transactionLines').className = "alert alert-danger";            count++;        }        if (jQuery('select.i-warehouse', tr).val() == null || jQuery('select.i-shelf', tr).val() == null) {            document.getElementById('error_transactionLines').innerHTML = "*In all rows you must select warehouse and shelfs*";            document.getElementById('error_transactionLines').className = "alert alert-danger";            count++;        }        // else {        //     var shelf = jQuery('select.i-shelf', tr).children("option:selected").val();        //     var warehouse = jQuery('select.i-warehouse', tr).children("option:selected").val();        //     var item_id = jQuery('input.i-item_id', tr).val();        //     var qty = jQuery('input.i-qty', tr).val();        //     var order_warehouse1 = warehouse.startsWith("order");        //     var order_warehouse2 = warehouse.startsWith("Order");        //     if (order_warehouse1 == false && order_warehouse2 == false) {        //         $.ajax({        //             cache: false,        //             type: 'POST',        //             async: false,        //             data: { 'qty': qty, 'shelf': shelf, 'warehouse': warehouse, 'item_id': item_id },        //             url: getAppURL('items/get_max_qty_for_each_warehouse_shelf'),        //             success: function (data) {        //                 // alert(data);        //                 if (parseFloat(qty) > parseFloat(data) || parseFloat(qty) < 1) {        //                     error = jQuery('div.i-error_qty', tr);        //                     error.show().html("*Qty not found: max qty=".concat(data).concat("*"));        //                     error.addClass("alert alert-danger");        //                     count++;        //                 }        //             }        //         });        //     }        // }    });    var disc = $('#disc').val()    if (disc.trim() == "") {        document.getElementById('error_disc').innerHTML = "*please enter number for discount*";        document.getElementById('error_disc').className = "alert alert-danger";        count++;    }    if (isNaN(disc) == true) {        document.getElementById('error_disc').innerHTML = "*discount must be numeric*";        document.getElementById('error_disc').className = "alert alert-danger";        count++;    } else {        if (Math.sign(disc) == -1) {            document.getElementById('error_disc').innerHTML = "*discount must be positive number*";            document.getElementById('error_disc').className = "alert alert-danger";            count++;        }    }    var delivery_charge = $('#delivery_charge').val()    if (delivery_charge.trim() == "") {        document.getElementById('error_delivery_charge').innerHTML = "*please enter number for delivery charge*";        document.getElementById('error_delivery_charge').className = "alert alert-danger";        count++;    }    if (isNaN(delivery_charge) == true) {        document.getElementById('error_delivery_charge').innerHTML = "*delivery charge must be numeric*";        document.getElementById('error_delivery_charge').className = "alert alert-danger";        count++;    } else {        if (Math.sign(delivery_charge) == -1) {            document.getElementById('error_delivery_charge').innerHTML = "*delivery charge must be positive number*";            document.getElementById('error_delivery_charge').className = "alert alert-danger";            count++;        }    }    if (document.transactionForm.acctLkupTo.value == "" || document.transactionForm.account_id.value == "") {        document.getElementById('error_acctLkupTo').innerHTML = "*Please select a Supplier Account*";        document.getElementById('error_acctLkupTo').className = "alert alert-danger";        count++;    }    if (document.transactionForm.acctLkupFrom.value == "" || document.transactionForm.account2_id.value == "") {        document.getElementById('error_acctLkupFrom').innerHTML = "*Please select a Purchases Account*";        document.getElementById('error_acctLkupFrom').className = "alert alert-danger";        count++;    }    if (document.transactionForm.currency_id.value == "") {        document.getElementById('error_currency_id').innerHTML = "*Please select a currency*";        document.getElementById('error_currency_id').className = "alert alert-danger";        count++;    }    var currency_rate = $('#currency_rate').val();    if (currency_rate.trim() == "") {        document.getElementById('error_currency_rate').innerHTML = "*please enter number for currency rate*";        document.getElementById('error_currency_rate').className = "alert alert-danger";        count++;    }    if (isNaN(currency_rate) == true) {        document.getElementById('error_currency_rate').innerHTML = "*currency rate must be numeric*";        document.getElementById('error_currency_rate').className = "alert alert-danger";        count++;    } else {        if (Math.sign(currency_rate) == -1) {            document.getElementById('error_currency_rate').innerHTML = "*currency rate must be positive number*";            document.getElementById('error_currency_rate').className = "alert alert-danger";            count++;        }    }    var date1 = change_date_format(document.transactionForm.trans_date.value);    if (document.transactionForm.trans_date.value == "" || isNaN(date1.getDate())) {        document.getElementById('error_trans_date').innerHTML = "*Please enter a valide date*";        document.getElementById('error_trans_date').className = "alert alert-danger";        count++;    }    if (document.transactionForm.value_date.value != '') {        var date2 = change_date_format(document.transactionForm.value_date.value);        if (isNaN(date2.getDate())) {            document.getElementById('error_value_date').innerHTML = "*Please enter a valide date*";            document.getElementById('error_value_date').className = "alert alert-danger";            count++;        }    }    if (document.getElementById('transactionLines').rows.length == "0") {        document.getElementById('error_transactionLines').innerHTML = "*Please enter an item*";        document.getElementById('error_transactionLines').className = "alert alert-danger";        count++;    }    if (count > 0) {        return false;    } else {        $submitted = 1;        return true;    }}function AddAccount() {    window.open(getAppURL('accounts/add?TransAddAccount=1'), "_blank");}function AddItem() {    window.open(getAppURL('items/add?TransAddItem=1'), "_blank");}function change_date_format(date) {    var datearray = date.split("-");    var new_date_format = datearray[1] + "/" + datearray[0] + "/" + datearray[2]    var date_new = new Date(new_date_format);    return date_new;}function delivery_type_action() {    var d = $('#delivery_type').val();    if (d == " ") {        $('#driver_list').prop('disabled', true);    } else {        if (d == "Post" || d == "DHL") {            $('#driver_div').hide();            $('#tracking_div').show();        } else {            $('#tracking_div').hide();            $('#driver_div').show();            if (d == "Delivery") {                $('#driver_list').prop('disabled', false);            } else {                if (d == "Self Pickup") {                    $('#driver_list').prop('disabled', true);                }            }        }    }}