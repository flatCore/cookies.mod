<?php
error_reporting(E_ALL ^E_NOTICE);
/**
 * @modul	cookies
 * backend preferences
 */

if(!defined('FC_INC_DIR')) {
	die("No access");
}

include __DIR__.'/include.php';

if(isset($_POST['save_cookie_prefs'])) {
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "UPDATE preferences
					SET url_privacy_policy = :url_privacy_policy,
							cookie_banner_intro = :cookie_banner_intro,
							cookie_lifetime = :cookie_lifetime,
							ignore_inline_css = :ignore_inline_css,
							cookie_banner_intro_snippet = :cookie_banner_intro_snippet,
							cookie_styles = :cookie_styles
					WHERE status = 'active' ";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':url_privacy_policy', $_POST['url_privacy_policy'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_banner_intro', $_POST['cookie_banner_intro'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_lifetime', $_POST['cookie_lifetime'], PDO::PARAM_STR);
	$sth->bindParam(':ignore_inline_css', $_POST['ignore_inline_css'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_banner_intro_snippet', $_POST['cookie_snippet'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_styles', $_POST['cookie_styles'], PDO::PARAM_STR);
	$cnt_changes = $sth->execute();
	$dbh = null;
}


$cookie_prefs = cookies_get_preferences();

$url_privacy_policy = $cookie_prefs['url_privacy_policy'];
$cookie_banner_intro = $cookie_prefs['cookie_banner_intro'];
$cookie_lifetime = $cookie_prefs['cookie_lifetime'];
$ignore_inline_css = $cookie_prefs['ignore_inline_css'];
$intro_snippet = $cookie_prefs['cookie_banner_intro_snippet'];
$cookie_styles = $cookie_prefs['cookie_styles'];

if($cookie_lifetime == '') {
	$cookie_lifetime = 0;
}

echo $btn_help_text;
echo '<h3>'.$mod_name.' '.$mod_version.' <small>| '.$cookies_lang['nav_preferences'].'</small></h3>';



echo '<form action="acp.php?tn=moduls&sub=cookies.mod&a=prefs" method="POST">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['url_privacy_policy'].'</label>';
echo '<input class="form-control" name="url_privacy_policy" type="text" value="'.$url_privacy_policy.'">';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<fieldset>';
echo '<legend>'.$cookies_lang['cookie_banner_intro'].'</legend>';
echo '<textarea class="form-control" name="cookie_banner_intro" rows="3">'.$cookie_banner_intro.'</textarea>';
echo '</fieldset>';

echo '</div>';
echo '<div class="col-md-6">';

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
	if($snippet['textlib_name'] == $intro_snippet) {
		$selected = 'selected';
	}
	echo '<option '.$selected.' value='.$snippet['textlib_name'].'>'.$snippet['textlib_name'].'</option>';
}
echo '</select>';
echo '<span class="form-text text-muted">'.$cookies_lang['snippet_help_text'].'</span>';

echo '</fieldset>';

echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['label_cookie_lifetime'].'</label>';
echo '<input class="form-control" name="cookie_lifetime" type="text" value="'.$cookie_lifetime.'">';
echo '<small id="passwordHelpBlock" class="form-text text-muted">';
echo cookies_format_seconds($cookie_lifetime);
echo '</small>';
echo '</div>';

if($ignore_inline_css == 'ignore') {
	$ckeck_ignore_inline_css = 'checked';
} else {
	$ckeck_ignore_inline_css = '';
}

echo '<fieldset class="mt-4">';
echo '<legend>Theme/Styles</legend>';

echo '<div class="form-check">';
echo '<input id="ignore_inline_css" class="form-check-input" name="ignore_inline_css" type="checkbox" value="ignore" '.$ckeck_ignore_inline_css.'>';
echo '<label for="ignore_inline_css" class="form-check-label">'.$cookies_lang['label_ignore_inline_css'].'</label>';
echo '</div>';

$tpl_folders = cookies_list_template_folders();

echo '<div class="form-group">';
echo '<label>Template</label>';

echo '<select class="form-control custom-select" name="cookie_styles">';
				
foreach ($tpl_folders as $tpl) {
	unset($sel);
	if($cookie_styles == $tpl) {
		$sel = "selected";
	}					
	echo "<option $sel value='$tpl'>$tpl</option>";
}
echo '</select>';
echo '</div>';

echo '</fieldset>';


echo '<hr><div class="well">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '<input class="btn btn-success" type="submit" name="save_cookie_prefs" value="'.$lang['update'].'">';
echo '</div>';


echo '</form>';



?>