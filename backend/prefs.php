<?php
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
							ignore_inline_css = :ignore_inline_css
					WHERE status = 'active' ";
	$sth = $dbh->prepare($sql);
	$sth->bindParam(':url_privacy_policy', $_POST['url_privacy_policy'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_banner_intro', $_POST['cookie_banner_intro'], PDO::PARAM_STR);
	$sth->bindParam(':cookie_lifetime', $_POST['cookie_lifetime'], PDO::PARAM_STR);
	$sth->bindParam(':ignore_inline_css', $_POST['ignore_inline_css'], PDO::PARAM_STR);
	$cnt_changes = $sth->execute();
	$dbh = null;
}


$cookie_prefs = cookies_get_preferences();

$url_privacy_policy = $cookie_prefs['url_privacy_policy'];
$cookie_banner_intro = $cookie_prefs['cookie_banner_intro'];
$cookie_lifetime = $cookie_prefs['cookie_lifetime'];
$ignore_inline_css = $cookie_prefs['ignore_inline_css'];

if($cookie_lifetime == '') {
	$cookie_lifetime = 0;
}


echo '<h3>'.$mod_name.' '.$mod_version.' <small>| '.$cookies_lang['nav_preferences'].'</small></h3>';



echo '<form action="acp.php?tn=moduls&sub=cookies.mod&a=prefs" method="POST">';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['url_privacy_policy'].'</label>';
echo '<input class="form-control" name="url_privacy_policy" type="text" value="'.$url_privacy_policy.'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label>'.$cookies_lang['cookie_banner_intro'].'</label>';
echo '<textarea class="form-control" name="cookie_banner_intro" rows="6">'.$cookie_banner_intro.'</textarea>';
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

echo '<div class="form-check">';
echo '<input id="ignore_inline_css" class="form-check-input" name="ignore_inline_css" type="checkbox" value="ignore" '.$ckeck_ignore_inline_css.'>';
echo '<label for="ignore_inline_css" class="form-check-label">'.$cookies_lang['label_ignore_inline_css'].'</label>';
echo '</div>';


echo '<hr><div class="well">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '<input class="btn btn-success" type="submit" name="save_cookie_prefs" value="'.$lang['update'].'">';
echo '</div>';

echo '</form>';

function cookies_format_seconds($seconds) {
    $df = new \DateTime('@0');
    $dt = new \DateTime("@$seconds");
    return $df->diff($dt)->format('%a days, %h hours, %i minutes and %s seconds');
}

?>