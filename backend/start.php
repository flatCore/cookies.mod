<?php

/**
 * @modul	cookies
 * backend
 */

if(!defined('FC_INC_DIR')) {
	die("No access");
}

include '../modules/cookies.mod/install/installer.php';
include __DIR__.'/include.php';

echo $btn_help_text;
echo '<h3>'.$mod_name.' '.$mod_version.' <small>| '.$mod['description'].'</small></h3>';


if($mode == '') {
	$mode = 'new';
}




if((isset($_GET['delete_id'])) && is_numeric($_GET['delete_id'])) {
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "DELETE FROM entries WHERE id = :id";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':id', $_GET['delete_id'], PDO::PARAM_INT);
	$cnt_changes = $sth->execute();
	$dbh = NULL;
	if($cnt_changes == TRUE) {
		echo '<div class="alert alert-success">'.$lang['db_changed'].'</div>';
	}

}


if($_GET['edit_id'] != '') {
	$entry_id = (int) $_GET['edit_id'];
	$mode = 'new';
}


if(isset($_POST['save_cookie'])) {
	
	$dbh = new PDO("sqlite:$mod_db");
	
	$time = time();
	
	if($_POST['edit_id'] != '') {
		$mode = 'edit';
	}
	
	if($mode == "new")	{
		
		$sql = "INSERT INTO entries (
			id, title, teaser, text, code_head, code_body, code_head_default, code_body_default, status, mandatory, hash, priority, snippet_name
				) VALUES (
			NULL, :title, :teaser, :text, :code_head, :code_body, :code_head_default, :code_body_default, :status, :mandatory, :hash, :priority, :snippet_name		) ";
		
		$sth = $dbh->prepare($sql);
		
	} else {
		
		$sql = "UPDATE entries
							SET	title = :title,
							teaser = :teaser,
							text = :text,
							code_head = :code_head,
							code_body = :code_body,
							code_head_default = :code_head_default,
							code_body_default = :code_body_default,
							status = :status,
							mandatory = :mandatory,
							hash = :hash,
							priority = :priority,
							snippet_name = :snippet_name
							WHERE id = :id ";
		
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':id', $_POST['edit_id'], PDO::PARAM_INT);	
	}	
	
	$cookie_hash = clean_filename($_POST['cookie_title']);
		
		$sth->bindParam(':title', $_POST['cookie_title'], PDO::PARAM_STR);
		$sth->bindParam(':teaser', $_POST['cookie_teaser'], PDO::PARAM_STR);
		$sth->bindParam(':text', $_POST['cookie_text'], PDO::PARAM_STR);
		$sth->bindParam(':code_head', $_POST['cookie_code_head'], PDO::PARAM_STR);
		$sth->bindParam(':code_body', $_POST['cookie_code_body'], PDO::PARAM_STR);
		$sth->bindParam(':code_head_default', $_POST['cookie_code_head_default'], PDO::PARAM_STR);
		$sth->bindParam(':code_body_default', $_POST['cookie_code_body_default'], PDO::PARAM_STR);
		$sth->bindParam(':status', $_POST['cookie_status'], PDO::PARAM_STR);
		$sth->bindParam(':mandatory', $_POST['cookie_mandatory'], PDO::PARAM_STR);
		$sth->bindParam(':priority', $_POST['cookie_priority'], PDO::PARAM_INT);
		$sth->bindParam(':hash', $cookie_hash, PDO::PARAM_STR);
		$sth->bindParam(':snippet_name', $_POST['cookie_snippet'], PDO::PARAM_STR);
		
		$cnt_changes = $sth->execute();
		
		if($mode == "new")	{
			$entry_id = $dbh->lastInsertId();
		} else {
			$entry_id = $_POST['edit_id'];
		}
		
		if($cnt_changes == TRUE){
			$sys_message = '{OKAY} ' . $lang['db_changed'];
			record_log($_SESSION['user_nick'],"cookie.mod ($mode) <i>".$_POST['cookie_title']."</i>",'0');
		} else {
			$sys_message = '{ERROR} ' . $lang['db_not_changed'];
			print_r($dbh->errorInfo());
		}
		
		$dbh = NULL;
		
		print_sysmsg("$sys_message");
		
	
	
}

if($entry_id != '') {
	$mode = 'edit';
	
	$get_cookie = cookies_get_entry_data($entry_id);

	$cookie_title = $get_cookie['title'];
	$cookie_teaser = $get_cookie['teaser'];
	$cookie_text = $get_cookie['text'];
	$cookie_code_head = $get_cookie['code_head'];
	$cookie_code_body = $get_cookie['code_body'];
	$cookie_code_head_default = $get_cookie['code_head_default'];
	$cookie_code_body_default = $get_cookie['code_body_default'];
	$cookie_id = $get_cookie['id'];
	$cookie_status = $get_cookie['status'];
	$cookie_mandatory = $get_cookie['mandatory'];
	$cookie_priority = $get_cookie['priority'];
	$cookie_snippet = $get_cookie['snippet_name'];
}







/* list cookies */

$all_cookies = cookies_get_entries();
$cnt_all_cookies = count($all_cookies);

echo '<fieldset class="mt-4">';
echo '<legend>Cookies ('.$cnt_all_cookies.')</legend>';
echo '<table class="table table-sm table-hover">';
echo '<tr>';
echo '<td>ID</td>';
echo '<td>'.$cookies_lang['cookie_priority'].'</td>';
echo '<td>'.$cookies_lang['cookie_title'].'</td>';
echo '<td>Snippet</td>';
echo '<td>Status</td>';
echo '<td></td>';
echo '</tr>';

for($i=0;$i<$cnt_all_cookies;$i++) {
	
	$show_star = '';
	if($all_cookies[$i]['mandatory'] == 'yes') {
		$show_star = $icon['star_outline'];
	}
	
	$show_check = '';
	if($all_cookies[$i]['status'] == 'active') {
		$show_check = '<span class="text-success">'.$icon['check'].'</span>';
	}
	
	$show_snippet = '';
	if($all_cookies[$i]['snippet_name'] != 'no_snippet') {
		$show_snippet = $all_cookies[$i]['snippet_name'];
	}
	
		
	echo '<tr>';
	echo '<td>'.$all_cookies[$i]['id'].'</td>';
	echo '<td>'.$all_cookies[$i]['priority'].'</td>';
	echo '<td><strong>'.$all_cookies[$i]['title'].'</strong><br>'.$all_cookies[$i]['teaser'].'</td>';
	echo '<td><code>'.$show_snippet.'</code></td>';
	echo '<td nowrap>'.$show_check.' '.$show_star.'</td>';
	echo '<td class="text-right" nowrap>';
	echo '<a href="acp.php?tn=moduls&sub=cookies.mod&a=start&edit_id='.$all_cookies[$i]['id'].'" class="btn btn-fc text-success">'.$icon['edit'].'</a> ';
	echo '<a href="acp.php?tn=moduls&sub=cookies.mod&a=start&delete_id='.$all_cookies[$i]['id'].'" class="btn btn-danger">'.$icon['trash_alt'].'</a>';
	echo '</td>';
	echo '</tr>';
}

echo '</table>';
echo '</fieldset>';


echo '<hr class="shadow">';

/* form - edit cookies */

echo '<div class="card">';
echo '<div class="card-header">';
if($mode == 'new') {
	echo $cookies_lang['label_new_cookie'];
} else {
	echo $icon['edit'].' '.$cookie_title;
}
echo '</div>';
echo '<div class="card-body">';
echo '<form action="acp.php?tn=moduls&sub=cookies.mod&a=start" method="POST">';

echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['cookie_title'].'</label>';
echo '<input class="form-control" name="cookie_title" type="text" value="'.$cookie_title.'">';
echo '</div>';

echo '</div>';
echo '<div class="col-md-3">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['cookie_priority'].'</label>';
echo '<input class="form-control" name="cookie_priority" type="text" value="'.$cookie_priority.'">';
echo '</div>';

echo '</div>';
echo '<div class="col-md-3">';

echo '<div class="well">';
echo '<h5>Status</h5>';
if($cookie_status == 'active') {
	$check_status = 'checked';
}
echo '<div class="form-check">';
echo '<input class="form-check-input" id="status" type="checkbox" value="active" name="cookie_status" '.$check_status.'>';
echo '<label class="form-check-label" for="status">'.$cookies_lang['label_active'].'</label>';
echo '</div>';


if($cookie_mandatory == 'yes') {
	$check_mandatory = 'checked';
}
echo '<div class="form-check">';
echo '<input class="form-check-input" id="mandatory" type="checkbox" value="yes" name="cookie_mandatory" '.$check_mandatory.'>';
echo '<label class="form-check-label" for="mandatory">'.$cookies_lang['label_mandatory'].'</label>';
echo '</div>';

echo '</div>';


echo '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['cookie_teaser'].'</label>';
echo '<textarea class="form-control" name="cookie_teaser" rows="4">'.$cookie_teaser.'</textarea>';
echo '</div>';

echo '</div>';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['cookie_text'].'</label>';
echo '<textarea class="form-control" name="cookie_text" rows="4">'.$cookie_text.'</textarea>';
echo '</div>';

echo '</div>';
echo '</div>';

echo '<fieldset>';
echo '<legend>'.$cookies_lang['label_alternative_snippet'].'</legend>';

echo '<select class="form-control custom-select" name="cookie_snippet">';
echo '<option value="no_snippet">'.$cookies_lang['no_snippet'].'</option>';

$snippets_list = $db_content->select("fc_textlib","*",[
	"AND" => [
		"textlib_name[~]" => "cookie%",
		"textlib_lang" => $languagePack	
	]
]);

foreach($snippets_list as $snippet) {
	$selected = "";
	if($snippet['textlib_name'] == $cookie_snippet) {
		$selected = 'selected';
	}
	echo '<option '.$selected.' value='.$snippet['textlib_name'].'>'.$snippet['textlib_name'].'</option>';
}
echo '</select>';
echo '<span class="form-text text-muted">'.$cookies_lang['snippet_help_text'].'</span>';

echo '</fieldset>';


echo '<div class="row">';
echo '<div class="col-md-6">';

/* code injects if cookies acceppted */
echo '<fieldset>';
echo '<legend class="text-success">'.$cookies_lang['label_cookie_accepted'].'</legend>';
echo '<div class="card">';
echo '<div class="card-header">';

echo '<ul class="nav nav-tabs card-header-tabs" role="tablist">';
echo '<li class="nav-item"><a class="nav-link active" data-bs-target="#code_head" data-bs-toggle="tab" href="#" role="tab">Code &lt;head&gt;</a></li>';
echo '<li class="nav-item"><a class="nav-link" data-bs-target="#code_body" data-bs-toggle="tab" href="#" role="tab">Code &lt;body&gt;</a></li>';

echo '</div>';
echo '<div class="card-body">';

echo '<div class="tab-content" id="TabContent">';
echo'<div class="tab-pane fade show active" id="code_head" role="tabpanel">';

echo '<div class="form-group">';
echo '<textarea class="code_editor" id="code_head" name="cookie_code_head">'.$cookie_code_head.'</textarea>';
echo '</div>';

echo '</div>';
echo'<div class="tab-pane fade" id="code_body" role="tabpanel">';

echo '<div class="form-group">';
echo '<textarea class="code_editor" id="code_body" name="cookie_code_body">'.$cookie_code_body.'</textarea>';
echo '</div>';


echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</fieldset>';

echo '</div>';
echo '<div class="col-md-6">';


/* code injects if cookies declined */
echo '<fieldset>';
echo '<legend class="text-danger">'.$cookies_lang['label_cookie_declined'].'</legend>';
echo '<div class="card">';
echo '<div class="card-header">';

echo '<ul class="nav nav-tabs card-header-tabs" role="tablist">';
echo '<li class="nav-item"><a class="nav-link active" data-bs-target="#code_head_default" data-bs-toggle="tab" href="#" role="tab">Code &lt;head&gt;</a></li>';
echo '<li class="nav-item"><a class="nav-link" data-bs-target="#code_body_default" data-bs-toggle="tab" href="#" role="tab">Code &lt;body&gt;</a></li>';

echo '</div>';
echo '<div class="card-body">';

echo '<div class="tab-content" id="TabContent">';
echo'<div class="tab-pane fade show active" id="code_head_default" role="tabpanel">';

echo '<div class="form-group">';
echo '<textarea class="code_editor" id="code_head_default" name="cookie_code_head_default">'.$cookie_code_head_default.'</textarea>';
echo '</div>';

echo '</div>';
echo'<div class="tab-pane fade" id="code_body_default" role="tabpanel">';

echo '<div class="form-group">';
echo '<textarea class="code_editor" id="code_body" name="cookie_code_body_default">'.$cookie_code_body_default.'</textarea>';
echo '<div class="ace_code_editor"></div>';
echo '</div>';


echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</fieldset>';

echo '</div>';
echo '</div>';


echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';

echo '<div class="well text-right">';
if($mode == 'edit') {
	echo '<input class="btn btn-success" type="submit" name="save_cookie" value="'.$lang['update'].'">';
	echo '<input type="hidden" name="edit_id" value="'.$cookie_id.'">';
} else {
	echo '<input class="btn btn-success" type="submit" name="save_cookie" value="'.$lang['save'].'">';
}
echo '</div>';


echo '</form>';
echo '</div>';
echo '</div>';

?>