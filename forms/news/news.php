<?php
function news__show() {
	$out=aikiGetForm();
	$Item=aikiReadItem($_GET["form"],$_GET["item"]);
	if (!isset($Item["images_position"])) {$out->append('<div data-role="gallery"></div>');}
	if ($Item["images_position"]=="top") {$out->prepend("<div data-role='gallery'></div>");}
	if ($Item["images_position"]=="bottom") {$out->append("<div data-role='gallery'></div>");}
	if (isset($Item["intext_position"]["pos"]) && !$Item["intext_position"]["pos"]=="") {
		$img="<img data-role='thumbnail' size=''>";
		if (isset($Item["intext_position"]["pos"]) && $Item["intext_position"]["pos"]>"") {
			$pos=$Item["intext_position"]["pos"];
			if (isset($Item["intext_position"]["width"]) && $Item["intext_position"]["width"]>"") {$width=$Item["intext_position"]["width"];} else {$width=200;}
			if (isset($Item["intext_position"]["height"]) && $Item["intext_position"]["height"]>"") {$width=$Item["intext_position"]["height"];} else {$height=160;}
			if (!strpos($width,"px") && !strpos($width,"%")) {$width.="px";}
			if (!strpos($height,"px") && !strpos($height,"%")) {$height.="px";}
			$size="{$width};{$height};src";
			$out->find("div.text")->prepend($img);
			$out->find("img:first")->attr("size",$size);
			$out->find("img:first")->attr("data-hide","data-role size img data-src");
			$out->find("img:first")->addClass("pull-{$pos}");
		}
	}
	$Item=aikiBeforeShowItem($Item);
	$out->contentSetData($Item);
	return $out->htmlOuter();
}

function news__list() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiListItems("news");
	foreach($Item["result"] as $key => $item) {
		$Item["result"][$key]=_newsBeforeShowItem($item);
	}
	$out->contentSetData($Item);
	$out->find("div.modal")->attr("id","newsEdit");
	$out->find("div.modal")->attr("data-backdrop","static");
	$out->find("[data-formsave]")->attr("data-formsave","#newsEditForm");
	$out->find(".modal-title")->html("Редактирование новости");
	return $out->htmlOuter();
}

function _newsBeforeShowItem($Item,$mode=NULL) {
	if ($mode==NULL) {$mode=$_GET["mode"];}
	$month=array("","январь","февраль","март","апрель","май","июнь","июль","август","сентябрь","октябрь","ноябрь","декабрь");
	$mon=array("","янв","фев","мар","апр","май","июн","июл","авг","сен","окт","ноя","дек");
	$Item["day"]=$Item["d"]=date("d",strtotime($Item["date"]));
	$Item["m"]=date("m",strtotime($Item["date"]));
	$Item["y"]=date("Y",strtotime($Item["date"]));
	$Item["month"]=$month[$Item["m"]*1];
	$Item["mon"]=$mon[$Item["m"]*1];
	switch($mode) {
		case "edit" :
			if (!isset($Item["date"])) {$Item["date"]=date("Y-m-d");}
			$Item["date"]=date("Y-m-d",strtotime($Item["date"]));
			break;
		case "list"	:
			$Item["datesort"]=$Item["date"];
			$Item["date"]=date("d.m.Y",strtotime($Item["date"]));
			$Item["text"]=getWords(strip_tags($Item["text"]),50);
			break;
		default		:
			$Item["date"]=date("d.m.Y",strtotime($Item["date"]));
			break;
	}
	return $Item;
}

function _newsBeforeSaveItem($Item) {
	if (!isset($Item["created"])) {$Item["created"]=date("Y-m-d H:i:s");}
	return $Item;
}

function news__edit() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiReadItem("news",$_GET["id"]);
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();}
	$Item=_newsBeforeShowItem($Item);
	$out->contentSetData($Item);
	return $out->htmlOuter();
}

function news__getajax() {
	if ($_GET["view"]=="modal") {
		$_GET["mode"]="show";
		$out="<div class='row'><div class='col-sm-12'>".news__show()."</div></div>";
		return $out;
	}
}


?>
