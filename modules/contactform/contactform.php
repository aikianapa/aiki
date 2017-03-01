<?php
function contactform_init() {
	$out=aikiFromFile(__DIR__."/contactform_show.php");
	return $out->outerHtml();
}


?>
