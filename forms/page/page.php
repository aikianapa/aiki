<?php

function page__list() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiListItems("page");
	$Item["result"]=array_sort($Item["result"],"id");
	$out->contentSetData($Item);
	$out->find("div.modal")->attr("id","pageEdit");
	$out->find("div.modal")->attr("data-backdrop","static");
	$out->find("[data-formsave]")->attr("data-formsave","#pageEditForm");
	$out->find(".modal-title")->html("Редактирование страницы");
	return $out->outerHtml();
}

function page__show($out=null,$Item=null) {
	if ($Item==null) $Item=$_SESSION["Item"];
	if ($out==null) {
		if (isset($Item["template"]) && $Item["template"]>"") {$out=aikiGetTpl($Item["template"]);} else {$out=aikiGetForm();}
	}
	return common__show($Item);
}

function page__edit() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiReadItem("page",$_GET["id"]);
	if ($_GET["id"]=="_new") {
		$Item["id"]=newIdRnd();
		$Item["template"]=$_SESSION["settings"]["template"];
	}
	$Item["tpllist"]=aikiListTpl();
	$out->contentSetData($Item);
	$options=$out->find("select[name=template] option");
	foreach($options as $opt) {
		if (strpos($opt->attr("value"),".inc.")) $opt->remove();
	}
	return $out->outerHtml();
}

function page__getajax() {
	$Item=aikiReadItem($_GET["form"],$_GET["item"]);
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	if (!is_object($out)) {
		$out=aikiGetForm($_GET["form"],"show");
		$out->contentSetData($Item);
	} else {
		$out=aikiFromString(page__show($out,$Item));		
	}
	if (is_callable("pageChangeHtml")) {pageChangeHtml($out,$Item);}
	return $out->outerHtml();
}

function _pageBeforeShowItem($Item) {
	if (isset($Item["tags"])) $Item["tags"]=explode(",",$Item["tags"]);
	if ($_GET["mode"]=="show") {$Item=aikiAddItemGal($Item);}
	return $Item;
}

function _pageAfterReadItem($Item) {
	if ($_GET["mode"]=="list") {unset($Item["text"]);}
	return $Item;
}
?>
