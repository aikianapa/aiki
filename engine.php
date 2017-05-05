<?php
ini_set('display_errors', 0	);
include_once(__DIR__ ."/functions.php");
comSession();
comPathCheck();
aikiEnviroment();
aikiSettingsRead();
aikiDatabaseConnect();
aikiLogin();
aikiFormFunctions();

if (!isset($_ENV["route"]["form"]) OR $_ENV["route"]["form"]!=="default_form") {
$_ENV["DOM"]=aikiFromString(""); $_ENV["ITEM"]=array();
if (is_callable("aikiBeforeEngine")) {$_ENV["ITEM"] = aikiBeforeEngine($_ENV["DOM"],$_ENV["Item"]);}
if (is_callable("aikiCustomEngine")) {$_ENV["DOM"]  = aikiCustomEngine();} else {aikiLoadController();}
if (is_callable("aikiAfterEngine"))  {$_ENV["ITEM"] = aikiAfterEngine($_ENV["DOM"],$_ENV["ITEM"]);}
$Item=$_ENV["ITEM"];
$__page=$_ENV["DOM"];
}
?>
