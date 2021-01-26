<?php

/**
 * cookies.mod Database-Scheme
 * install/update the table for preferences
 * 
 */

$database = "cookies";
$table_name = "preferences";

$cols = array(
	"id"  => 'INTEGER NOT NULL PRIMARY KEY',
	"status"  => 'VARCHAR',
	"url_privacy_policy" => 'VARCHAR',
	"cookie_banner_intro" => 'VARCHAR',
	"cookie_banner_intro_snippet" => 'VARCHAR',
	"cookie_lifetime" => 'VARCHAR',
	"ignore_inline_css" => 'VARCHAR',
	"cookie_styles" => 'VARCHAR',
	"version" => 'VARCHAR'
  );
  
  
 
?>
