jQuery(document).ready(function () {
    $('#tag_shelfs').tagThis({
        noDuplicates: true
    });

    $('#save').on('click', function (e) {
        var shelf_tags = $('#tag_shelfs').data('tags');
        if (shelf_tags !== undefined) {
            var tags = [];
            for (let i = 0; i < shelf_tags.length; i++) {
                tags.push(shelf_tags[i].text);
            }
            $.ajax({
                cache: false,
                type: 'POST',
                data: { 'warehouse': $('#warehouse').val(), 'shelfs': tags },
                url: getAppURL('warehouses/add_multi_shelfs'),
                success: function (data) {
                    if (data == "1") {
                        window.location.href = getAppURL('warehouses/expand/'.concat($('#warehouse_name').val()))
                    } else {
                        document.getElementById('error_shelfs').innerHTML = data;
                        document.getElementById('error_shelfs').className = "alert alert-danger";
                    }
                }
            });
        } else {
            document.getElementById('error_shelfs').innerHTML = "Please, add shelfs.";
            document.getElementById('error_shelfs').className = "alert alert-danger";
        }
        // console.log($('#warehouse').val());
    });
});
