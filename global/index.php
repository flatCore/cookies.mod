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


include 'modules/cookies.mod/global/functions.php';
include 'modules/cookies.mod/lang/en/dict.php';

if(FC_SOURCE == 'frontend') {
	$languagePack = $page_contents['page_language'];
}

if(is_file('modules/cookies.mod/lang/'.$languagePack.'/dict.php')) {
	include 'modules/cookies.mod/lang/'.$languagePack.'/dict.php';
}

$get_cookies = cookies_get_entries();
$get_cookies_prefs = cookies_get_preferences();
$cookie_lifetime = $get_cookies_prefs['cookie_lifetime'];
$cookie_banner_intro = $get_cookies_prefs['cookie_banner_intro'];

if($get_cookies_prefs['cookie_banner_intro_snippet'] != 'no_snippet') {
	$snippet = $db_content->get("fc_textlib","*",[
		"AND" => [
			"textlib_name" => $get_cookies_prefs['cookie_banner_intro_snippet'],
			"textlib_lang" => $languagePack
		]
	]);
	
	$cookie_banner_intro = $snippet['textlib_content'];
}

$cookie_styles_dir = $get_cookies_prefs['cookie_styles']; /* tpl folder for cookies */

$cookie_styles = '';
if($get_cookies_prefs['ignore_inline_css'] != 'ignore') {
	$cookie_styles = '<style type="text/css">'.file_get_contents('modules/cookies.mod/styles/'.$cookie_styles_dir.'/styles.css').'</style>';
}

$cookie_script = file_get_contents('modules/cookies.mod/global/cookie.js');

if(is_file('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookie-alert.tpl')) {
	$cookie_alert = file_get_contents('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookie-alert.tpl');
} else {
	$cookie_alert = file_get_contents('modules/cookies.mod/styles/default/cookie-alert.tpl');
}



$cookies_test = print_r($get_cookies,true);

$time = time();

$cookie_table = cookies_print_table($get_cookies);


$cookie_form_action = '/'.$fct_slug.$mod_slug;
$cookie_form_action = str_replace('//', '/', $cookie_form_action);



$cookie_alert = str_replace('{cookie_banner_intro}', $cookie_banner_intro, $cookie_alert);
$cookie_alert = str_replace('{cookie_lifetime}', $cookie_lifetime, $cookie_alert);
$privacy_link = '';
if($get_cookies_prefs['url_privacy_policy'] != '') {
	$privacy_link .= '<p class="mb-0"><a href="'.$get_cookies_prefs['url_privacy_policy'].'">'.$cookies_lang['label_more_info'].' '.$get_cookies_prefs['url_privacy_policy'].'</a></p>';
}
$cookie_alert = str_replace('{url_privacy_policy}', $privacy_link, $cookie_alert);
$cookie_alert = str_replace('{cookie_list}', $cookie_table, $cookie_alert);
$cookie_alert = str_replace('{btn_accept_all}', $cookies_lang['btn_accept_all'], $cookie_alert);
$cookie_alert = str_replace('{btn_save}', $cookies_lang['btn_save'], $cookie_alert);
	
foreach($get_cookies as $k => $v) {
	$all_cookies .= '<input type="hidden" name="all_cookies[]" value="'.$get_cookies[$k]['hash'].'">';
}
$cookie_alert = str_replace('{cookies_list_all}', $all_cookies, $cookie_alert);
	

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
$append_body_code .= $cookie_alert.$cookies_body_code.$cookie_script;

?>