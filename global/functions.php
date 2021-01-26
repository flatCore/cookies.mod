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
	$cnt_cookies = count($get_cookies);
	
	$cookie_table = '<table class="table table-sm">';
	for($i=0;$i<$cnt_cookies;$i++) {

		$checked = '';
		$disabled = '';
					
		if($get_cookies[$i]['mandatory'] == 'yes') {
			$checked = 'checked';
			$disabled = 'disabled';
		}
				
		
		$collapse = '';
		if($get_cookies[$i]['text'] != '') {
			$collapse  = '<a class="" data-toggle="collapse" href="#cookiCollapse'.$i.'" role="button" aria-expanded="false" aria-controls="collapseExample">[?]</a>';
			$collapse .= '<div class="collapse pt-2" id="cookiCollapse'.$i.'">';
			$collapse .= $get_cookies[$i]['text'];
			$collapse .= '</div>';
		}
		
		if(isset($_COOKIE[$get_cookies[$i]['hash']])) {
			$checked = 'checked';
		}
		
		$cookie_table .= '<tr>';
		$cookie_table .= '<td><strong>'.$get_cookies[$i]['title'].'</strong><br>'.$get_cookies[$i]['teaser'].' '.$collapse.'</td>';
		$cookie_table .= '<td><input type="checkbox" name="set_cookies[]" value="'.$get_cookies[$i]['hash'].'" class="cookie_switch" id="switch'.$i.'" '.$checked.' '.$disabled.'><label for="switch'.$i.'">Toggle</label></td>';
		$cookie_table .= '</tr>';
		
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