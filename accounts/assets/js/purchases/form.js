let $fromAccountId, $fromAccount, $toAccountId,$toAccount;
$(document).ready(function ($) {
    $fromAccountId = $('input#fromAccountId'), $fromAccount = $('input#fromAccount'); // get jQuery object of the input with id = fromAccount
    autoCompleteFromAccount();
    load_data();
    $('#search_text').keyup(function () {
        var search = $(this).val();
        if (search != '') {
            load_data(search);
        } else {
            load_data();
        }
    });
    
});

function autoCompleteFromAccount() {
    $fromAccount.autocomplete({ // , appendTo: $fromAccount.parent()[0]
        serviceUrl: getAppURL('Sales/lookup_accounts') , appendTo: $fromAccount.parent()[0],
        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
        noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
        onSearchStart: function (params) {
        }, onSelect: function (selectedAccount) {
            fromAccountSelected(selectedAccount);
        }, transformResult: function (response) {
            return {
                suggestions: jQuery.map(response, function (dataItem) {
                    return { value: dataItem.A_id + ' - ' + dataItem.A_name, data: dataItem.A_id, name: dataItem.A_name };
                })
            };
        }
    });
}

function fromAccountSelected(account) {
    $fromAccountId.val(account.data);
}

$(document).ready(function ($) {
    $toAccountId = $('input#toAccountId'), $toAccount = $('input#toAccount'); // get jQuery object of the input with id = fromAccount
    autoCompletetoAccount();
    load_data();
    $('#search_text').keyup(function () {
        var search = $(this).val();
        if (search != '') {
            load_data(search);
        } else {
            load_data();
        }
    });
});

function autoCompletetoAccount() {
    $toAccount.autocomplete({ // , appendTo: $fromAccount.parent()[0]
        serviceUrl: getAppURL('Sales/lookup_accounts') , appendTo: $toAccount.parent()[0],
        noCache: true, showNoSuggestionNotice: true, triggerSelectOnValidInput: false, minChars: 2, autoSelectFirst: true, preventBadQueries: false,
        noSuggestionNotice: 'Sorry, no matching results', type: 'GET', dataType: 'JSON', deferRequestBy: 480,
        onSearchStart: function (params) {
        }, onSelect: function (selectedAccount) {
            toAccountSelected(selectedAccount);
        }, transformResult: function (response) {
            return {
                suggestions: jQuery.map(response, function (dataItem) {
                    return { value: dataItem.A_id + ' - ' + dataItem.A_name, data: dataItem.A_id, name: dataItem.A_name };
                })
            };
        }
    });
}

function toAccountSelected(account) {
    $fromAccountId.val(account.data);
}


function load_data(query) {
    $.ajax({
        url: getAppURL('livesearch_select/fetch'),
        method: "POST",
        data: {
            query: query
        },
        success: function (data) {
            $('#result').html(data);
        }
    })
}


/**
var subtotal = 0;

    function finalTotal() {
        var discnt = document.getElementById('Adiscount').value;
        document.getElementById('amount').value = subtotal - discnt;
    }


    findTotal();


    function findTotal() {

        var table = document.getElementById("mytable");
        if (subtotal !== 0) {
            subtotal = 0;
        }
        for (var i = 1, row; row = table.rows[i]; i++) {

            var qty = document.getElementById('qty' + i).value;
            var price = document.getElementById('price' + i).value;
            var discount = document.getElementById('discount' + i).value;
            document.getElementById('total' + i).value = (qty * price) * (1 - (discount / 100));
            subtotal += (qty * price) * (1 - (discount / 100));
            document.getElementById('amount').value = subtotal;

        }

    }
  */