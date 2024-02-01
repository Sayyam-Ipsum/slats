jQuery(document).ready(function () {
    $('#bgback').on('click', function (e) {
        e.preventDefault();
        window.location.href = document.referrer;
    });
});