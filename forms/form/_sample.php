<?php
/*
function {{_form_}}_list() {
	$out=aikiGetForm();
	$out->contentSetData($Item);
	return $out->outerHtml();
}

function {{_form_}}_show($out=null,$Item=null) {
	if ($Item==null) $Item=$_SESSION["Item"];
	if ($out==null) {
		if (isset($Item["template"]) && $Item["template"]>"") {$out=aikiGetTpl($Item["template"]);} else {$out=aikiGetForm();}
	}
	$out->contentSetData($Item);
	return $out->outerHtml();
}
*/

function test_edit() {
	$out=aikiGetForm();
	$Item=aikiReadItem("test",$_GET["id"]);
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();}
	$Item["tpllist"]=aikiListTpl();
	foreach($Item["tpllist"] as $key => $tpl) {
		if (strpos($Item["tpllist"][$key],".inc.")) {unset($Item["tpllist"][$key]);}
	}
	$out->contentSetData($Item);
	return $out->outerHtml();
}

function {{_form_}}BeforeShowItem($Item) {
	if (isset($Item["tags"])) $Item["tags"]=explode(",",$Item["tags"]);
//	if ($_GET["mode"]=="show") {$Item=aikiAddItemGal($Item);}
	return $Item;
}

function {{_form_}}AfterReadItem($Item) {
//
	return $Item;
}
?>
