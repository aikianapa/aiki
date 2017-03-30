<?php
include_once(__DIR__ ."/engine.php");
unset($_SESSION["data"]);
if ($_SERVER["SCRIPT_NAME"]=="/engine/index.php" AND $_SESSION["user_role"]=="admin") {$engine=true;} else {$engine=false;}
contentAppends($_ENV["DOM"]);
$_ENV["DOM"]->contentTargeter();
if (is_callable("aikiBeforeShowHtml")) {aikiBeforeShowHtml($_ENV["DOM"]);}
aikiBaseHref($_ENV["DOM"]);
echo $_ENV["DOM"]->outerHtml();
aikiClearMemory();
die;
?>
