<?php
error_reporting(0);

$time = time();
$cookie_lifetime = (int) $_POST['cookie_lifetime'];

/* remove all the cookies */
foreach($_POST['all_cookies'] as $k => $v) {
	$cookie_name = $_POST['all_cookies'][$k];
	if(isset($_COOKIE[$cookie_name])) {
		unset($_COOKIE[$cookie_name]);
		setcookie($cookie_name, null, -1, '/');
	}
}

/* set the cookies */
if($_POST['set_mode'] == 'cookies_accept_selected') {
	foreach($_POST['set_cookies'] as $set_cookie) {
		setcookie("$set_cookie","$time",$time+$cookie_lifetime,'/');
	}
	setcookie("cookie_consent","$time",$time+$cookie_lifetime,'/');
}

if($_POST['set_mode'] == 'cookies_accept_all') {
	foreach($_POST['all_cookies'] as $set_cookie) {
		setcookie($set_cookie,"$time",$time+$cookie_lifetime,'/');
	}
	setcookie("cookie_consent","$time",$time+$cookie_lifetime,'/');
}

?>