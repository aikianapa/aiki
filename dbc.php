<?php
if(!isset($_SESSION["SESSID"])) { session_start();}
if (!isset($_SESSION["SESSID"])) {$_SESSION["SESSID"]=session_id();} else {session_id($_SESSION["SESSID"]);}
$_SESSION["engine_path"]="{$_SERVER['DOCUMENT_ROOT']}/engine";
include_once("{$_SESSION["engine_path"]}/functions.php");
$list=fileListItems("users");
foreach($list["result"] as $id => $Item) {
	print_r($Item);
	jdbSaveItem("users",$Item);
}

?>
