<?
function robokassa__checkout() {
	$order=aikiReadItem("orders",$_SESSION["order_id"]);
	$form=formGetForm($_GET["form"],$_GET["mode"]);
	$SETT=$_SESSION["settings"][$_GET["form"]];
	$test_mode = $SETT['test']; if ($test_mode=="on") {$test_mode=1;}
	$success_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/success/{$order['id']}.htm";
	$fail_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/fail/{$order['id']}.htm";
	$result_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/result/{$order['id']}.htm";
	$test_mode = $SETT['test'];
	$mrh=array();
	$mrh["login"] = $SETT['id'];
	$mrh["url"]=$SETT["url"]; // url мерчанта
	$mrh["inv_id"] = 0; // номер заказа системный (не принимает шестнатиричные)
	$mrh["Shp_orderId"] = $order["id"];
	$mrh["inv_desc"] = "Кафе Купон - заказ № {$order['id']}"; // описание заказа
	$mrh["summ"] = $order["total"]; // сумма заказа
	$mrh["currency"] = ""; // предлагаемая валюта платежа
	$mrh["culture"] = "ru"; // язык
	if ($test_mode=="on") {
		$mrh["pass"] = $SETT['test1'];
		$mrh["test"] = 1;
	} else {$mrh["pass"] = $SETT['key1'];}
	$crc="{$mrh['login']}:{$mrh['summ']}:{$mrh['inv_id']}:{$mrh['pass']}:Shp_orderId={$mrh['Shp_orderId']}";
	$mrh["crc"]  = md5($crc); // формирование подписи
	$form->contentSetData($mrh);
return $form->outerHtml();
}

function robokassa__success() {
	$order=aikiReadItem("orders",$_REQUEST["Shp_orderId"]);
	$SETT=$_SESSION["settings"]["robokassa"];
	$test_mode = $SETT['test'];
	// HTTP parameters:
	$mrh=array();
	$mrh["login"] = $SETT['id'];
	$mrh["summ"] = $order["total"];
	$mrh["inv_id"] = $_REQUEST["InvId"];
	$mrh["Shp_orderId"]=$order["id"];
	if ($test_mode=="on") {$mrh["pass"] = $SETT['test1'];} else {$mrh["pass"] = $SETT['key1'];}

	// build own CRC
	$my_crc="{$mrh['summ']}:{$mrh['inv_id']}:{$mrh['pass']}:Shp_orderId={$mrh['Shp_orderId']}";
	$my_crc = strtoupper(md5($my_crc));

	$crc = $_REQUEST["SignatureValue"];
	$crc = strtoupper($crc);  // force uppercase

	if (strtoupper($my_crc) != strtoupper($crc)) {  robokassa_fail();  exit();	}

		if (!is_callable("users_cabinet")) {include_once($_SESSION["root_path"]."/forms/users/users.php");}
		$out=users_cabinet();
		$_SESSION["order_id"]=newIdRnd();
		setcookie("order_id","",time()-3600,"/"); unset($_COOKIE["order_id"]);

		//Lpay2pay_order_success($inv_id);
	return $out;

}

function robokassa__result() {
	$order=aikiReadItem("orders",$_REQUEST["Shp_orderId"]);
	$SETT=$_SESSION["settings"]["robokassa"];
	$test_mode = $SETT['test'];
	// HTTP parameters:
	$mrh=array();
	$mrh["login"] = $SETT['id'];
	$mrh["summ"] = $order["total"];
	$mrh["inv_id"] = $_REQUEST["InvId"];
	$mrh["Shp_orderId"]=$order["id"];
	if ($test_mode=="on") {$mrh["pass"] = $SETT['test2'];} else {$mrh["pass"] = $SETT['key2'];}

	// build own CRC
	$my_crc="{$mrh['summ']}:{$mrh['inv_id']}:{$mrh['pass']}:Shp_orderId={$mrh['Shp_orderId']}";
	$my_crc = strtoupper(md5($my_crc));

	$crc = $_REQUEST["SignatureValue"];
	$crc = strtoupper($crc);  // force uppercase


	if (strtoupper($my_crc) != strtoupper($crc)) {    echo "bad sign\n"; exit();	}

		if (!is_callable("unitsCheckoutSuccess")) {include_once($_SESSION["root_path"]."/forms/units/units.php");}
		unitsCheckoutSuccess($mrh["Shp_orderId"]);

	echo "OK{$mrh['inv_id']}\n";
}

function robokassa__fail() {
	$out=getTemplate("/tpl/kupon/cart.php",true);
	$out->find("#page")->prepend("<br /><div class='container bg-danger'>
	<h4><i class='ti-alert'></i> Не удалось выполнить оплату заказа!</h4>
	</div>");
	return $out->outerHtml();
	die;
}

function robokassa__settings() {
	$form=formGetForm($_GET["form"],$_GET["mode"]);
	$form->contentSetValues($_SESSION["settings"]);  // проставляем значения
	return $form->outerHtml();
}



?>
