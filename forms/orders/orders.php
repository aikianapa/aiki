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
	require_once $_SERVER["DOCUMENT_ROOT"].'/engine/phpmailer/PHPMailerAutoload.php';
	if ($Item==null) {$Item=aikiReadItem("orders",$_GET["id"]);}
	$out=aikiGetForm("orders","mail",true);
	$out->contentSetData($Item);
	$out->find(".data-grand-total")->remove();
	$mail = new PHPMailer;
	$mail->CharSet = "WINDOWS-1251";
	$mail->setFrom($_SESSION["email"], $_SERVER["HTTP_HOST"]);
	$mail->Subject = $out->find("title")->text();
	$mail->MsgHTML($out);
	$mail->IsHTML(true); 
	$mail->addAddress($Item['email'], iconv("UTF-8", "WINDOWS-1251", $Item['name']));
	$mail->send();
	$mail->addAddress($_SESSION['email'], iconv("UTF-8", "WINDOWS-1251", $_SERVER["HTTP_HOST"]));
	$mail->send();
	return $out;
}

?>
