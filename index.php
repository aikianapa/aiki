<?php
include_once($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");
unset($_SESSION["data"]);
parse_str($_SERVER["REQUEST_URI"],$req);
$role=dict_filter_value("user_role","code",$_SESSION["user-role"]);
$tpl=$role["tpl"];
$__page=getTemplate($tpl);
if ($_SERVER["SCRIPT_NAME"]=="/engine/index.php" AND $_SESSION["user_role"]=="admin") {$engine=true;} else {$engine=false;}
contentAppends($__page);
$__page->contentTargeter();
if (is_callable("aikiBeforeShowHtml")) {aikiBeforeShowHtml($__page);}
$__page->contentTargeter();
echo $__page->outerHtml();
unset($__page);
?>
