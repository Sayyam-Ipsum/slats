</div>
<script type="text/javascript">
	const _lang = <?php echo json_encode($this->lang->language) ?>;
	const _userLogedInType = <?php echo json_encode($this->violet_auth->get_user_type()) ?>;
</script>
<script src="assets/js/jquery213.min.js"></script>
<!-- <script src="//code.jquery.com/jquery-1.11.1.js"></script> -->
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/magnific/js/magnific.v1.1.0.min.js"></script>
<script src="assets/js/common.js"></script>
<script src="assets/js/html2canvas.js" type="text/javascript"></script> 
<script src="assets/js/jqueryhtml2canvas.js" type="text/javascript"></script> 
<script src="assets/js/jsbarcode.js" type="text/javascript"></script>
<script src="assets/js/navbar/change_password.js"></script>
<?php
if (isset($_moreJs)):
	foreach ($_moreJs as $jsFile) {
		echo PHP_EOL, '<script src="assets/js/', $jsFile, '.js"></script>';
	}
endif
?>
</body>
</html>