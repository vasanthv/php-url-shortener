<?php
require_once('urlshortener.php');

$v = array_reverse(explode("/", $_SERVER['REQUEST_URI']));
$shorturlid = $v[0];
$urlshortener = new hycus_urlshortener();

if($shorturlid){
	$urlshortener->goto_url($urlshortener->baseurl.$shorturlid);
}else{
	if($_POST['longurl']){
		$shorturl = $urlshortener->get_shorturl($_POST['longurl']);
		$hits = $urlshortener->get_hits($shorturl);
	}
	echo '<center>';
		echo '<h3>PHP URL shortener script</h3>';
		echo '<form action="" method="post">';
			echo '<input type="text" name="longurl" style="width:500px;"/><br/><br/>';
			echo '<input type="submit" value="Get short URL" />';
		echo '</form>';

		if($shorturl){
			echo '<br/><br/><span style="background:#efefef;padding:10px;border:thin solid #777777;font-size:20px;"><a href="'.$shorturl.'" target="_blank">'.$shorturl.'</a></span>';

			echo '<br/><br/><br/><span style="background:#efefef;padding:10px;border:thin solid #777777;font-size:20px;">Hits: '.$hits.'</span>';

		}

	echo '</center>';
}
?>
