<?php
/**
 * @modul	cookies
 * include in all backend files
 */
 
error_reporting(E_ALL ^E_NOTICE);

if(!defined('FC_INC_DIR')) {
	die("No access");
}

include 'functions.php';
include '../modules/cookies.mod/global/functions.php';


?>