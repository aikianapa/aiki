<?php
function dict__list() {
	$form=$_GET["form"];
	$out=aikifromFile("http://{$_SERVER["HTTP_HOST"]}/engine/forms/{$form}/{$form}_list.php");
	$Item=aikiListItems("comments");
	$Item["result"]=array_sort($Item["result"],"date",SORT_DESC);
	$out->contentSetData($Item);
	$out->find("div.modal")->attr("id","{$form}Edit");
	$out->find("div.modal")->attr("data-backdrop","static");
	$out->find("[data-formsave]")->attr("data-formsave","#{$form}EditForm");
	$out->find(".modal-title")->html("Редактирование справочника");
	return $out->outerHtml();
}


function dict__edit() {
	$form=$_GET["form"];
	$out=ki::fromFile("http://{$_SERVER["HTTP_HOST"]}/engine/forms/{$form}/{$form}_edit.php");
	$Item=aikiReadItem("dict",$_GET["id"]);
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();} else {
		$out->find("#dictEditForm .nav-tabs li:eq(1)")->addClass("set_active");
	}
	$out->find("form button[data-formsave]")->parents(".form-group")->remove();
	$Item["form"]=$form;
	if (isset($Item["data"])) $Item["data"]=json_encode($Item["data"]);
	$out->contentSetData($Item);
	return $out->outerHtml();
}
?>
