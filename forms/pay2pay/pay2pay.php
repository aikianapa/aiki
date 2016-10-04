<?
function pay2pay__checkout() {
$order=aikiReadItem("orders",$_SESSION["order_id"]);
$form=aikiGetForm($_GET["form"],$_GET["mode"]);
$SETT=$_SESSION["settings"][$_GET["form"]];
$test_mode = $SETT['test']; if ($test_mode=="on") {$test_mode=1;}
$success_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/success/{$order['id']}.htm";
$fail_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/fail/{$order['id']}.htm";
$result_url = "http://{$_SERVER['HTTP_HOST']}/{$_GET["form"]}/result/{$order['id']}.htm";
$mch=array();
$mch["merchant_id"]=$SETT['id'];
$mch["merchant_url"]=$SETT['url'];
$mch["secret_key"]=$SETT['secret_key'];
$mch["currency"]="RUB";
$mch["description"]="Кафе Купон";
$mch["order_id"]=$order["id"];
$mch["amount"]=$order["total"];
$mch["secret_key"]=$SETT["key"];
$xml = '<?xml version="1.0" encoding="UTF-8"?>
	<request>
		<version>1.3</version>
		<merchant_id>'. $mch["merchant_id"] . '</merchant_id>
		<order_id>'. $mch["order_id"] . '</order_id>
		<amount>'. $mch["amount"]. '</amount>
		<currency>'. $mch["currency"] . '</currency>
		<description>'. $mch["description"] . '</description>
		<success_url><![CDATA['. $success_url. ']]></success_url>
		<fail_url><![CDATA['. $fail_url. ']]></fail_url>
		<result_url><![CDATA['. $result_url. ']]></result_url>
		<test_mode>'. $test_mode. '</test_mode>
	</request>
';
$sign = md5($mch["secret_key"]. $xml. $mch["secret_key"]);
$mch["sign_encoded"] = base64_encode($sign);
$mch["xml_encoded"] = base64_encode($xml);
$form->contentSetValues($mch);
return $form->outerHtml();
}

function pay2pay__success() {
	echo "success";
}

function pay2pay__result() { pay2pay_success(); }

function pay2pay__fail() {
	echo "fail";
}

function pay2pay__settings() {
	$form=aikiGetForm($_GET["form"],$_GET["mode"]);
	$form->contentSetValues($_SESSION["settings"]);  // проставляем значения
	return $form->outerHtml();
}
?>
