$(document).ready(function() {

    $("#btn_convert").on('click', function() {
        html2canvas(document.getElementById("myHtml"), {
            allowTaint: true,
            useCORS: true
        }).then(function(canvas) {
            var anchorTag = document.createElement("a");
            // document.body.appendChild(anchorTag);
            // document.getElementById("previewImg").appendChild(canvas);
            anchorTag.download = "delivery_note.jpg";
            anchorTag.href = canvas.toDataURL();
            anchorTag.target = '_blank';
            anchorTag.click();
        });
    });

    $("#print").on('click', function() {
        event.preventDefault();
        var element = document.getElementById("myHtml");
        html2canvas(element).then(function(canvas) {
            var myImage = canvas.toDataURL("image/jpg");
            var tmp = window.open("");
            $(tmp.document.body)
                .html("<img src=" + myImage + " alt=''>")
                .ready(function() {
                    setTimeout(function() {
                        tmp.focus();
                        tmp.print();
                    }, 200);
                })
        })
    });

    $('#bgback').click(function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
    
    JsBarcode("#barcode", $('#dn_nb').val());
});