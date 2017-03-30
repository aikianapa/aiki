<?php
function engine__controller() {
	$call=__FUNCTION__ ."__".$_ENV["route"]["mode"];
	if (is_callable($call)) {
		$_ENV["DOM"]=$call();
		return $_ENV["DOM"];
	} else {
		echo __FUNCTION__ .": отсутствует функция ".$call."()";
		die;
	}
}

function engine__controller__login() {
	$_ENV["DOM"]=aikiGetTpl("login.php");
	return $_ENV["DOM"];
}

function engine__controller__admin() {
	$role=dict_filter_value("user_role","code",$_SESSION["user-role"]);
	$_ENV["DOM"]=getTemplate($role["tpl"]);
	return $_ENV["DOM"];
}

function engine__controller__logout() {
		$_SESSION["User"]=$_SESSION["user"]=$_SESSION["user-role"]=$_SESSION["user_role"]=$_SESSION["user-id"]=$_SESSION["user_id"]="";
		setcookie("user_id","",time()-3600,"/"); unset($_COOKIE["user_id"]);
		header("Refresh: 0; URL=http://{$_SERVER["HTTP_HOST"]}");
		echo "Выход из системы, ждите...";
		die;
}

function engine__controller__setup() {
		include("../setup.php");
		die;
}

?>
