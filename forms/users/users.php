<?php
function users__reg() {
	if ($_GET["action"]) {
		$call="{$_GET['form']}__{$_GET['mode']}_{$_GET['action']}";
		if (is_callable($call)) {$out=@$call();} else {
			$call="{$_GET['form']}_{$_GET['mode']}_{$_GET['action']}";
			if (is_callable($call)) {$out=@$call();}
		}
	} else {
		$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	}
	return $out->htmlOuter();
}

function users__pwdchange() {
	$res=false;
	if (!isset($_GET["id"])) {
		$list=aikiListItems("users",' email = "'.$_POST["reminder-email"].'" ');
		$list=$list["result"];
		foreach($list as $Item) {if ($res==false) {
			$res=true;
			$reminder=array();
			$reminder["password"]=$_POST["reminder-pass"];
			$reminder["session"]=$_SESSION["SESSID"];
			$Item["reminder"]=$reminder;
			$_POST["link"]="http://{$_SERVER["HTTP_HOST"]}/users/pwdchange/{$Item["id"]}_dwp_{$_SESSION["SESSID"]}.htm";
			$text=aikiGetForm($_GET["form"],"ajax_text");
			$text->contentSetData($_POST);
			$text=$text->find("#mail_pwdchange")->outerHtml();
			aikiSaveItem("users",$Item);
			mail($_POST["reminder-email"],"Изменение пароля",$text,"From: {$_SERVER["HTTP_HOST"]} <{$_SESSION['settings']['email']}>\nContent-Type: text/html; charset=UTF-8");
		}}
		return $res;
	} else {
		$tmp=explode("_dwp_",$_GET["id"]);
		$Item=aikiReadItem("users",$tmp[0]);
		if (isset($tmp[1]) && $tmp[1]>"" && $tmp[1]==$Item["reminder"]["session"]) {
			$Item["password"]=$Item["reminder"]["password"];
		}
		unset($Item["reminder"]);
		if ($Item["id"]>"" && $Item["id"]!=="_new") {aikiSaveItem("users",$Item);}
		return "<meta http-equiv='refresh' content='0; url=http://{$_SERVER["HTTP_HOST"]}/login.htm'>";
	}
}

function users__reg_submit() {
	$res=users__reg_check();
	$text=aikiGetForm($_GET["form"],"ajax_text");
	switch($res) {
		case "ok" :
			users__reg_mail($text);
			$out=$text->find("#success")->html();
			break;
		case "email" :
			$out=$text->find("#error_email")->html();
			break;
		case "login" :
			$out=$text->find("#error_login")->html();
			break;
	}
	unset($text);
	return ki::fromString($out);
}

function users__reg_check() {
	$res="ok";
	$type=$_SESSION["settings"]["elogin"];
	$$type=$_POST[$type];
	$where=" {$type} = '{$$type}' ";
	$list=aikiListItems("users",$where); $list=$list["result"];
	foreach($list as $key => $user) {
		if ($user[$type]==$$type) {$res=$type;}
	}
	return $res;
}

function users__reg_mail($text) {
	$Item=$_POST;
	$Item["id"]=newIdRnd();
	$Item["form"]="users";
	$Item["password"]=md5($Item["password"]);
	$mt=explode(" ",microtime());
	$Item["verify"]=dechex(ceil(($mt[0]*1000000).time()));
	if (!isset($Item["login"])) {$Item["login"]=$Item["email"];}
	aikiSaveItem("users",$Item);
	$_POST["link"]="http://{$_SERVER['HTTP_HOST']}/engine/ajax.php?mode=activation&form=users&item={$Item['id']}&code={$Item['verify']}";
	$_POST["login"]=$Item["login"];
	$text->contentSetValues($_POST);
	$text=$text->find("#mail_text")->outerHtml();
	if (isset($_POST["email"]) && $_POST["email"]>"") {
		mail($_POST["email"].",".$_SESSION["settings"]["email"],"Регистрация",$text,"From: {$_SERVER["HTTP_HOST"]} <{$_SESSION['settings']['email']}>\nContent-Type: text/html; charset=UTF-8");
	}
}

function users__activation() {
	$res=true;
	$user=aikiReadItem("users",$_GET["item"]);
	unset($user["firstImg"]);
	if ((isset($_GET["code"]) AND isset($user["verify"])) AND $_GET["code"]==$user["verify"]) {
		unset($user["verify"]);
		$user["active"]="on";
		$user["role"]="user";
		aikiSaveItem("users",$user);
		header("Location: http://{$_SERVER['HTTP_HOST']}/");
	} else {
		echo "Error!!!";
		$res=false;
	}
}

function _usersAfterReadItem($Item) {
	if ($_GET["mode"]=="list") {
		if (!isset($Item["active"])) {$Item["active"]="";}
		if ($Item["active"]!="on") {$Item["active"]="off";}
	}
	return $Item;
}

?>
