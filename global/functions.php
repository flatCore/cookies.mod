<?php

/**
 * global cookies.mod functions for backend and frontend
 * please prefix functions 'cookies_'
 */


function cookies_get_entries() {
	
	global $mod_db;
	$status = '';
	global $languagePack;
	global $db_content;
	
	if(FC_SOURCE == 'frontend') {
		$mod_db = './content/SQLite/cookies.sqlite3';
		$dbh = new PDO("sqlite:$mod_db");
		$status = 'active';
		$sql = 'SELECT * FROM entries WHERE status = :status ORDER BY priority DESC';
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':status', $status, PDO::PARAM_STR);		
	} else {
		$dbh = new PDO("sqlite:$mod_db");
		$sql = 'SELECT * FROM entries ORDER BY priority DESC';
		$sth = $dbh->prepare($sql);
	}


	$sth->execute();
	$entries = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$cnt_entries = count($entries);
	
	for($i=0;$i<$cnt_entries;$i++) {
		if($entries[$i]['snippet_name'] != 'no_snippet') {
						
			$snippet = $db_content->get("fc_textlib","*",[
				"AND" => [
					"textlib_name" => $entries[$i]['snippet_name'],
					"textlib_lang" => $languagePack
				]
			]);
			
			$separator = '<hr>';
			$pos = stripos($snippet['textlib_content'], $separator);
			if($pos !== false) {
				$entries[$i]['teaser'] = substr($snippet['textlib_content'], 0,$pos);
				$entries[$i]['text'] = substr($snippet['textlib_content'], $pos);
			
			} else {
				$entries[$i]['teaser'] = $snippet['textlib_content'];
				$entries[$i]['text'] = '';
			}
			
			$entries[$i]['title'] = $snippet['textlib_title'];
			$entries[$i]['teaser'] = strip_tags($entries[$i]['teaser'], '<br><a>');
				
		}
	}
	
	
	$dbh = null;

	return $entries;
}


function cookies_get_entry_data($id) {
	
	global $mod_db;
	global $mod;
	
	if(FC_SOURCE == 'frontend') {
		$mod_db = $mod['database'];
	}
	
	$dbh = new PDO("sqlite:$mod_db");
	
	$sql = "SELECT * FROM entries WHERE id = :id";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':id', $id, PDO::PARAM_STR);
	$sth->execute();
	
	$cookie = $sth->fetch(PDO::FETCH_ASSOC);
	$dbh = null;
	
	return $cookie;
}

/* frontend - list active cookies */


function cookies_print_table($get_cookies) {
	
	global $get_cookies_prefs;
	
	$cnt_cookies = count($get_cookies);
	$cookie_styles_dir = $get_cookies_prefs['cookie_styles'];
	
	
	if(is_file('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookies-list-item.tpl')) {
		$tpl_cookies_list_item = file_get_contents('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookies-list-item.tpl');
	} else {
		$tpl_cookies_list_item = file_get_contents('modules/cookies.mod/styles/default/cookies-list-item.tpl');
	}

	if(is_file('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookies-list-item-collapse.tpl')) {
		$tpl_cookies_list_item_collapse = file_get_contents('modules/cookies.mod/styles/'.$cookie_styles_dir.'/cookies-list-item-collapse.tpl');
	} else {
		$tpl_cookies_list_item_collapse = file_get_contents('modules/cookies.mod/styles/default/cookies-list-item-collapse.tpl');
	}
	
	$cookie_table = '<table class="table table-sm">';
	for($i=0;$i<$cnt_cookies;$i++) {
		
		$checked = '';
		$disabled = '';
					
		if($get_cookies[$i]['mandatory'] == 'yes') {
			$checked = 'checked';
			$disabled = 'disabled';
		}
		
		$this_item = $tpl_cookies_list_item;
				
		
		$collapse = '';
		if($get_cookies[$i]['text'] != '') {
			$collapse = $tpl_cookies_list_item_collapse;
			$collapse = str_replace('{cookie_text}', $get_cookies[$i]['text'], $collapse);
			$collapse = str_replace('{cookie_id}', $i, $collapse);
		}
		
		if(isset($_COOKIE[$get_cookies[$i]['hash']])) {
			$checked = 'checked';
		}
		
		
		$this_item = str_replace('{cookie_title}', $get_cookies[$i]['title'], $this_item);
		$this_item = str_replace('{cookie_teaser}', $get_cookies[$i]['teaser'], $this_item);
		$this_item = str_replace('{cookie_hash}', $get_cookies[$i]['hash'], $this_item);
		$this_item = str_replace('{cookie_collapse}', $collapse, $this_item);
		$this_item = str_replace('{cookie_id}', $i, $this_item);
		$this_item = str_replace('{checked}', $checked, $this_item);
		$this_item = str_replace('{disabled}', $disabled, $this_item);
		$cookie_table .= $this_item;
		
	}
	$cookie_table .= '</table>';
	
	return $cookie_table;
}


/* frontend - get active cookies, use hash as index */

function cookies_get_code_injections() {
	
	$mod_db = './content/SQLite/cookies.sqlite3';
	$dbh = new PDO("sqlite:$mod_db");
	$status = 'active';
	$sql = 'SELECT hash, title, code_head, code_body, code_head_default, code_body_default FROM entries WHERE status = :status ';
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':status', $status, PDO::PARAM_STR);	
	$sth->execute();
	$entries = $sth->fetchAll(PDO::FETCH_GROUP);
	
	$dbh = null;	
	
	return $entries;
}




/**
 * get preferences
 *
 */

function cookies_get_preferences() {
	
	global $mod_db;
	
	if(FC_SOURCE == 'frontend') {
		$mod_db = './content/SQLite/cookies.sqlite3';
	}
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM preferences WHERE status LIKE '%active%' ";
	$prefs = $dbh->query($sql);
	$prefs = $prefs->fetch(PDO::FETCH_ASSOC);
	$dbh = null;
	
	return $prefs;
}


?>