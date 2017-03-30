<?php
ini_set('display_errors', 0	);
include_once(__DIR__ ."/functions.php");
comSession();
comPathCheck();
aikiSettingsRead();
aikiDatabaseConnect();
aikiLogin();
aikiFormFunctions();
aikiRouterAdd(array(
// Формы
	'' 								=> '/page/show/id:home/',
	'/contents(:any)' 				=> '/',
	'/engine/(:any).php' 			=> '/controller:engine/$1/',
	'/login.htm' 					=> '/controller:engine/login/',
	'/logout.htm' 					=> '/controller:engine/logout/',
	'/admin.htm' 					=> '/controller:engine/admin/',
	'/(:any)/(:any)/(id:any).htm'	=> '/$1/$2/$3',
	'/(:any)/(id:any).htm' 			=> '/$1/show/$2',
	'/(id:any).htm' 				=> '/page/show/$1/',

// Миниатюры

	'/thumb/(:num)x(:num)/src/(src:any)'	=> '/controller:thumbnails/zc:1/w:$1/h:$2/src:$3',
	'/thumbc/(:num)x(:num)/src/(src:any)'	=> '/controller:thumbnails/zc:0/w:$1/h:$2/src:$3',
	'/thumb/(:num)x(:num)/(src:any)'		=> '/controller:thumbnails/zc:1/w:$1/h:$2/src:uploads/$3/$4/$5',
	'/thumbc/(:num)x(:num)/(src:any)'		=> '/controller:thumbnails/zc:0/w:$1/h:$2/src:uploads/$3/$4/$5',
));

aikiRouterGet();
$_ENV["DOM"]=aikiFromString(""); $_ENV["ITEM"]=array();
if (is_callable("aikiBeforeEngine")) {$_ENV["ITEM"]=aikiBeforeEngine($_ENV["DOM"],$_ENV["Item"]);}
if (is_callable("aikiCustomEngine")) {$_ENV["DOM"]=aikiCustomEngine();} else {aikiLoadController();}
if (is_callable("aikiAfterEngine")) {$_ENV["ITEM"]=aikiAfterEngine($_ENV["DOM"],$_ENV["ITEM"]);}
$Item=$_ENV["ITEM"];
$__page=$_ENV["DOM"];

?>
