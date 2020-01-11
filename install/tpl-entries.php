<?php

/**
 * cookies.mod Database-Scheme
 * install/update the table for news
 * 
 */

$database = "cookies";
$table_name = "entries";

$cols = array(
	"id"  => 'INTEGER NOT NULL PRIMARY KEY',
	"entrydate"  => 'VARCHAR', /* timestring entry time */
	"lastedit"  => 'VARCHAR', /* timestring last edit time */
	"lastedit_from"  => 'VARCHAR', /* User */
	"title" => 'VARCHAR',
	"hash" => 'VARCHAR',
	"teaser" => 'VARCHAR',
	"text" => 'VARCHAR',
	"code" => 'VARCHAR',
	"code_head" => 'VARCHAR',
	"code_body" => 'VARCHAR',
	"tags" => 'VARCHAR',
	"link" => 'VARCHAR',
	"status" => 'VARCHAR', /* active|NULL */
	"mandatory" => 'VARCHAR', /* yes|no */
	"lang" => 'VARCHAR',
	"priority" => 'INTEGER'
	);
 
?>