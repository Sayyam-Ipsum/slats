jQuery(document).ready(function ($) {
	if (window.location.href == getAppURL('missing_products/show_checked') || window.location.href == getAppURL('missing_products/show_all')) {
		document.getElementById("missing_save").style.visibility="hidden";
	}
});