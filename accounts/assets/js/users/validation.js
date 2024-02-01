function validation() {
    var count = 0;
    var username= $('#username').val();
    if (username == "") {
        document.getElementById('error_username').innerHTML = "*Please enter a username*";
        document.getElementById('error_username').className = "alert alert-danger";
        count++;
    }
    var password= $('#password').val();
	if (password == "") {
		document.getElementById('error_password').innerHTML = "*Please enter a password*";
		document.getElementById('error_password').className = "alert alert-danger";
		count++;
	}
    if (count > 0) {
		return false;
	}else{
        return true;
    }
}