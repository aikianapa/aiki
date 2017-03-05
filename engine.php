<?php
ini_set('display_errors', 0	);
include_once(__DIR__."/functions.php");
comSession();
comPathCheck();
aikiSettingsRead();
aikiDatabaseConnect();
aikiLogin();
aikiFormFunctions();
aikiParseUri();
$__page=aikiFromString("");
$Item=array(); $form=""; $mode=""; $id=""; $error=null; $tpl="";
if ($_SERVER['SCRIPT_NAME']=="/index.php") {
	if (is_callable("aikiBeforeEngine")) {$Item=aikiBeforeEngine($__page,$Item);}
	if (is_callable("aikiCustomEngine")) {$__page=aikiCustomEngine();} else {
		$form="page"; $mode="show"; $item="home";
		if (isset($_GET["form"]) && $_GET["form"]>"") {$form=$_GET["form"];} else {$_GET["form"]=$form;}
		if (isset($_GET["mode"]) && $_GET["mode"]>"") {$mode=$_GET["mode"];} else {$_GET["mode"]=$mode;}
		if (isset($_GET["item"]) && $_GET["item"]>"") {$item=$_GET["item"];} else {$_GET["item"]=$item;}
		if (isset($_GET["id"]  ) && $_GET["id"]>""  ) {$item=$_GET["id"];  } else {$_GET["id"]=$item;}
		$_GET["id"]=$_GET["item"]=$item;
	
		//if ($_SERVER["REQUEST_URI"]=="/" && $mode=="show" && $form=="page") {$item="home";}
		if (isset($form) && isset($item)) {
			$Item=$_SESSION["Item"]=aikiReadItem($form,$item);
			if ($_SESSION["error"]=="noitem") {$error="noitem";} else {
				if (isset($Item["template"])) {$tpl=$Item["template"];} 
			}
		}
		if ($_SESSION["error"]=="noitem") {$empty=1;} else {$empty=0;}
		if ($form=="page" && $mode=="show" && $item=="home" && $tpl=="") {
			if (is_file($_SESSION["app_path"]."/tpl/home.php")) {$__page=aikiGetTpl("home.php");} else {
			if (is_file($_SESSION["app_path"]."/tpl/default.php")) $__page=aikiGetTpl("default.php");}
		} else {
			if ($tpl>"") {$__page=aikiGetTpl($tpl);} else {
				if ($error==null OR $empty==1) {
					$__form=aikiGetForm(); 
					if (($_SESSION["error"]=="noform" && !is_callable($form."_".$mode)) ) {
						if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$__page=aikiGetTpl("404.php");} else {
							$__form=ki::fromString("[Ошибка 404] Страница отсутствует");
						}	
					}
				}
			}
		}
		
		if ($_SESSION["cache"]!==1) {
			if (is_object($__page) AND $__page->outerHtml()=="") { // в темплейтах не нашли, ищем в обработчиках
				$res=false;	$_SESSION["getTpl"]=true;
				$call="{$form}_{$mode}"; if ($res==false && is_callable($call)) {  $__page=$call(); $res=true;} // в проектах
				$call="{$form}__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в engine
				$call="common__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в общем случае
				if ($res==false && is_callable($mode)) {$__page=$mode(); $res=true;}
				if ($res==true && !is_object($__page) && $__page>"") {$__page=ki::fromString($__page);}
			} else {
				if (isset($Item["template"]) && $Item["template"]>"") {$tpl=$Item["template"];} else {$tpl="";}
				if ($form=="page" && $mode=="show" && $item=="home" && $tpl=="") {
					if (is_file($_SESSION["app_path"]."/tpl/home.php")) {$__page=aikiGetTpl("home.php");} else {
					if (is_file($_SESSION["app_path"]."/tpl/default.php")) $__page=aikiGetTpl("default.php");}
				}	
			}
			if ((!is_object($__page) OR $__page->outerHtml()=="") && $empty==1) {
				$file=$_SESSION["app_path"]."/tpl/{$_GET["id"]}.php";
				if ($form=="page" && $mode=="show" && is_file($file)) {$__page=aikiGetTpl($file);}
				//header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
				if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$__page=aikiGetTpl("404.php");} else {
					$__page=ki::fromString("[Ошибка 404] Страница отсутствует");
				}
			} else {
					if (!is_object($__page)) {$__page=ki::fromString($__page); }
					$call="_{$Item["form"]}BeforeShowItem"; if (is_callable($call)) {$Item=@$call($Item);}
					$call="{$Item["form"]}BeforeShowItem"; if (is_callable($call)) {$Item=@$call($Item);}
					$__page->contentSetData($Item);
					$call=$form."ChangeHtml"; if (is_callable($call)) {$call($__page,$Item);}
					$__page->contentTargeter($Item);
					if ($Item==array("id"=>$_GET["id"],"form"=>$_GET["form"]) && strip_tags($__page->outerHtml())==""
						&& $_GET["form"]=="page" && $_GET["mode"]=="show") {
						if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$__page=aikiGetTpl("404.php");} else {
							$__page=ki::fromString("[Ошибка 404] Страница отсутствует");
						}
					}
			}
			if (is_object($__page) && isset($__form) && $__page->outerHtml()=="") {
				$__page=$__form;
				$__page->contentSetData($Item);
			}
			
			if (isset($Item["meta_description"])) {
				$tag='<meta name="description" content="'.$Item["meta_description"].'">';
				$meta=$__page->find("meta[name=description]",0);
				if (is_object($meta)) {$meta->before($tag); $meta->remove();} else {$__page->find("head")->append($tag);}
			}

			if (isset($Item["meta_keywords"])) {
				$tag='<meta name="keywords" content="'.$Item["meta_keywords"].'">';
				$meta=$__page->find("meta[name=keywords]",0);
				if (is_object($meta)) {$meta->before($tag); $meta->remove();} else {$__page->find("head")->append($tag);}
			}
			aikiBaseHref($__page);
		} else {echo $__page->outerHtml(); die;}
		
	}
}
if (is_callable("aikiAfterEngine")) {$Item=aikiAfterEngine($__page,$Item);}

//echo '<br><br>Памяти использовано: ',round(memory_get_usage()/1024/1024,2),' MB<br><br>';



?>
