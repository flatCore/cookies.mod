<?php

/**
 * cookies | flatCore Modul
 * Configuration File
 */

if(FC_SOURCE == 'backend') {
	$mod_root = '../modules/';
} else {
	$mod_root = 'modules/';
}

include $mod_root.'cookies.mod/lang/en/dict.php';

if(is_file($mod_root.'cookies.mod/lang/'.$languagePack.'/dict.php')) {
	include $mod_root.'cookies.mod/lang/'.$languagePack.'/dict.php';
}

$mod = array(
	"name" => "cookies",
	"version" => "0.1.0",
	"author" => "Patrick Konstandin",
	"description" => "Manage your Cookies",
	"database" => "content/SQLite/cookies.sqlite3"
);


/* acp navigation */
$modnav[] = array('link' => $cookies_lang['nav_preferences'], 'title' => $cookies_lang['nav_preferences_title'], 'file' => "prefs");

?>