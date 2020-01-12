<?php

/**
 * module: cookies.mod
 * global injection file
 */

//error_reporting(E_ALL ^E_NOTICE);

/**
 * we are using {$prepend_head_code}, {$append_head_code}, {$prepend_body_code} or {$append_body_code}
 * to inject th cookie code
 */

include 'functions.php';

include 'modules/cookies.mod/lang/en/dict.php';

if(is_file('modules/cookies.mod/lang/'.$languagePack.'/dict.php')) {
	include 'modules/cookies.mod/lang/'.$languagePack.'/dict.php';
}

$get_cookies = cookies_get_entries();
$get_cookies_prefs = cookies_get_preferences();
$cookie_lifetime = $get_cookies_prefs['cookie_lifetime'];

$cookie_styles = '';
if($get_cookies_prefs['ignore_inline_css'] != 'ignore') {
	$cookie_styles = '<style type="text/css">'.file_get_contents('modules/cookies.mod/global/styles.css').'</style>';
}

$cookie_script = file_get_contents('modules/cookies.mod/global/cookie.js');
$cookies_test = print_r($get_cookies,true);

$time = time();
/* save individual cookies */
if(isset($_POST['cookies_save'])) {
	foreach($_POST['set_cookies'] as $set_cookie) {
		setcookie("$set_cookie","$time",$time+$cookie_lifetime);
	}
	setcookie("cookie_consent","$time",$time+$cookie_lifetime);
}

/* save all cookies - yeah */
if(isset($_POST['cookies_accept_all'])) {
	foreach($get_cookies as $k => $v) {
		setcookie($get_cookies[$k]['hash'],"$time",$time+$cookie_lifetime);
	}
	setcookie("cookie_consent","$time",$time+$cookie_lifetime);
}

$cookie_table = cookies_print_table($get_cookies);


$cookie_form_action = '/'.$fct_slug.$mod_slug;
$cookie_form_action = str_replace('//', '/', $cookie_form_action);

if(is_numeric($_COOKIE['cookie_consent'])) {
	$cookie_box = '';
} else {
	$cookie_box = '<div class="cookie-box">';
	$cookie_box .= '<p class="mb-0">'.$get_cookies_prefs['cookie_banner_intro'].'</p>';
	$cookie_box .= '<p class="mb-0"><a href="'.$get_cookies_prefs['url_privacy_policy'].'">'.$cookies_lang['label_more_info'].' '.$get_cookies_prefs['url_privacy_policy'].'</a></p>';
	$cookie_box .= '<form action="'.$cookie_form_action.'" method="POST">';
	$cookie_box .= $cookie_table;
	$cookie_box .= '<div class="cookie-box-actions">';
	$cookie_box .= '<button type="submit" class="btn btn-outline-success btn-sm" name="cookies_save">'.$cookies_lang['btn_save'].'</button> ';
	$cookie_box .= '<button type="submit" class="btn btn-success btn-sm" name="cookies_accept_all">'.$cookies_lang['btn_accept_all'].'</button>';
	$cookie_box .= '</div>';
	$cookie_box .= '</form>';
	$cookie_box .= '</div>';	
}


$cookie_codes = cookies_get_code_injections();

/* loop through cookies and check if we can inject code */
foreach($get_cookies as $k => $v) {
	
	$cookie_hash = $get_cookies[$k]['hash'];
	$user_cookie = $_COOKIE[$cookie_hash];

	if($get_cookies[$k]['mandatory'] == 'yes') {
		$cookies_head_code .=  $cookie_codes[$cookie_hash][0]['code_head'];
		$cookies_body_code .=  $cookie_codes[$cookie_hash][0]['code_body'];
		continue;		
	}
	
	if(is_numeric($user_cookie)) {
		/* hell yeah, inject the code from code_head and code_body */		
		$cookies_head_code .=  $cookie_codes[$cookie_hash][0]['code_head'];
		$cookies_body_code .=  $cookie_codes[$cookie_hash][0]['code_body'];	
	} else {
		/* use default codes as fallback */		
		$cookies_head_code .=  $cookie_codes[$cookie_hash][0]['code_head_default'];
		$cookies_body_code .=  $cookie_codes[$cookie_hash][0]['code_body_default'];		
	}
	
}

/* fire to template */
$append_head_code .= $cookie_styles.$cookies_head_code;
$append_body_code .= $cookie_box.$cookies_body_code.$cookie_script;

?>