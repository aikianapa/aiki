<?php
function news__show() {
	$out=aikiGetForm();
	$Item=aikiReadItem($_GET["form"],$_GET["id"]);
	$Item=aikiBeforeShowItem($Item);
	$out->contentSetData($Item);
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
		case "show" :
			$Item=aikiAddItemGal($Item);
			break;
		case "list"	:
			$Item["datesort"]=date("Y-m-d",strtotime($Item["date"]));
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
		if (isset($_GET["item"]) && !isset($_GET["id"])) $_GET["id"]=$_GET["item"];
		$out="<div class='row'><div class='col-sm-12'>".news__show()."</div></div>";
		return $out;
	}
}


?>
