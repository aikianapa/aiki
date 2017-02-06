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

function {{_form_}}_edit() {
	$out=aikiGetForm();
	$Item=aikiReadItem("{{_form_}}",$_GET["id"]);
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();}
	$Item["tpllist"]=aikiListTpl();
	$options=$out->find("select[name=template] option");
	foreach($options as $opt) {
		if (strpos($opt->attr("value"),".inc.")) $opt->remove();
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
