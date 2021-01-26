<?php

/* list template folders */
function cookies_list_template_folders() {
	$tpl_folders = array();
	
	$directory = "../modules/cookies.mod/styles";
	
	if(is_dir($directory)) {
	
		$all_folders = glob("$directory/*");
		
		foreach($all_folders as $v) {
			if(is_dir("$v")) {
				$tpl_folders[] = basename($v);
			}
		}
	
	 }
	 
	 return $tpl_folders;
}


function cookies_format_seconds($seconds) {
    $df = new \DateTime('@0');
    $dt = new \DateTime("@$seconds");
    return $df->diff($dt)->format('%a days, %h hours, %i minutes and %s seconds');
}


?>