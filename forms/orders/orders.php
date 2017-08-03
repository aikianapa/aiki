<?php

function _ordersAfterReadItem($Item) {
	if ($_GET["mode"]=="list" && isset($Item["person"])) {
		foreach($Item["person"] as $key => $val) {
			if (is_string($key)) {$Item["person_{$key}"]=$val;}
		}
	}
	if ($_GET["mode"]=="edit" && $_GET["form"]=="orders" && isset($Item["id"]) && $Item["id"]>"") {$_SESSION["order_id"]=$Item["id"];}
	return $Item;
}

function _ordersBeforeSaveItem($Item) {
	if ($_POST["order"]=="on" && $_GET["mode"]=="save" && $_GET["form"]=="orders") {
		$_SESSION["order_id"]=newIdRnd();
		$Item["date"]=date("Y-m-d H:i:s");
	}
	return $Item;
}

function _ordersAfterSaveItem($Item) {
	if ($_POST["order"]=="on") {
		_ordersMail($Item);
		$_SESSION["order_id"]=newIdRnd();
	}
	return $Item;
}


function _ordersMail($Item=null) {
	
	if ($Item==null) {$Item=aikiReadItem("orders",$_GET["item"]);}
	$out=aikiGetForm("orders","mail",true);
	$out->contentSetData($Item);
	$out->find(".data-grand-total")->remove();

	$subject=$out->find("title")->text();

	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=windows-1251\r\n";
//	$headers .= "From: {$_SERVER["HTTP_HOST"]} <{$_SESSION["settings"]["email"]}>\r\n";

	$body=$out->outerHtml();

	if (is_callable("iconv")) $body=iconv("UTF-8", "WINDOWS-1251", $body);
	if (is_callable("iconv")) $subject=iconv("UTF-8", "WINDOWS-1251", $subject);

	$to  = $Item["person"]["email"];
	mail($to, $subject, $body, $headers); 
	
	$to = $_SESSION["settings"]["email"] ; 
	mail($to, $subject, $body, $headers); 

	return $out;
}

?>
