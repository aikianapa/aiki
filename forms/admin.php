<?php
function admin__delete() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=array();
	$Item["item"]=$_GET["itemname"];
	$Item["form"]=$_GET["formname"];
	$out->contentSetData($Item);
	return $out->htmlOuter();
}

function admin__settings() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=fileReadItem($_GET["form"],$_GET["mode"]);
	$checkout_list=aikiCheckoutForms();
	if (count($checkout_list)>0) {
		$Item["checkout_list"]=$checkout_list;
	} else {
		$out->find("select[name=checkout]")->parents(".form-group")->remove();
	}
	$Item["tpllist"]=aikiListTpl();
	$out->contentSetData($Item);
	return $out->htmlOuter();
}

?>
