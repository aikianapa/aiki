<?php
function form__controller() {
$Item=$_ENV["ITEM"]; $form=""; $mode=""; $id=""; $error=null; $tpl="";

if ($_ENV["route"]["form"]=="default_form" && $_ENV["route"]["mode"] == "default_mode" ) {
	if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$_ENV["DOM"]=aikiGetTpl($_SESSION["app_path"]."/tpl/404.php");} else {
	if (is_file($_SESSION["engine_path"]."/tpl/404.php")) {$_ENV["DOM"]=aikiGetTpl($_SESSION["engine_path"]."/tpl/404.php");} else {
		$_ENV["DOM"]=ki::fromString("[Ошибка 404] Страница отсутствует");
	}}
}

if ($_SERVER['SCRIPT_NAME']=="/index.php") {
		$form=$_ENV["route"]["form"]; $mode=$_ENV["route"]["mode"]; $item=$_ENV["route"]["params"]["id"];
		$_GET["id"]=$_GET["item"]=$item;
		if (isset($form) && isset($item)) {
			$tmpItem=aikiReadItem($form,$item);
			if ($_ENV["error"]["aikiReadItem"]=="noitem") {
				$_SESSION["Item"]=$Item=$_ENV["ITEM"];
				$error="noitem"; $empty=1;
			} else {
				$_ENV["ITEM"]=$_SESSION["Item"]=$Item=$tmpItem;
				if (isset($Item["template"])) {$tpl=$Item["template"];} 
				$empty=0;
			}
		}

		if ($form=="page" && $mode=="show" && $item=="home" && $tpl=="") {
			if (is_file($_SESSION["app_path"]."/tpl/home.php")) {$_ENV["DOM"]=aikiGetTpl("home.php");} else {
			if (is_file($_SESSION["app_path"]."/tpl/default.php")) $_ENV["DOM"]=aikiGetTpl("default.php");}
		} else {
				if ($tpl=="" AND ($error==null OR $empty==1)) {
					$__form=aikiGetForm();
					if (($_ENV["error"]["aikiGetForm"]=="noform" && !is_callable($form."_".$mode)) ) {
						if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$_ENV["DOM"]=aikiGetTpl("404.php");} else {
							$__form=ki::fromString("[Ошибка 404] Страница отсутствует");
						}	
					} else {
						
							$__form=ki::fromString("[Ошибка 404] Страница отсутствует");
					}
				}
		}
		
		if ($_SESSION["cache"]!==1) {
			if (is_object($_ENV["DOM"]) AND $_ENV["DOM"]->outerHtml()=="" AND $tpl=="") { // в темплейтах не нашли, ищем в обработчиках
				$call="{$form}_{$mode}"; if ($res==false && is_callable($call)) {  $_ENV["DOM"]=$call(); $res=true;} // в проектах
				$call="{$form}__{$mode}"; if ($res==false && is_callable($call)) {$_ENV["DOM"]=$call(); $res=true;} // в engine
				$call="common__{$mode}"; if ($res==false && is_callable($call)) {$_ENV["DOM"]=$call(); $res=true;} // в общем случае
				if ($res==false && is_callable($mode)) {$_ENV["DOM"]=$mode(); $res=true;}
				if ($res==true && !is_object($_ENV["DOM"]) && $_ENV["DOM"]>"") {$_ENV["DOM"]=ki::fromString($_ENV["DOM"]);}
			} else {
				if (isset($Item["template"]) && $Item["template"]>"") {$tpl=$Item["template"];} else {$tpl="";}
				if ($form=="page" && $mode=="show" && $item=="home" && $tpl=="") {
					if (is_file($_SESSION["app_path"]."/tpl/home.php")) {$_ENV["DOM"]=aikiGetTpl("home.php");} else {
					if (is_file($_SESSION["app_path"]."/tpl/default.php")) $_ENV["DOM"]=aikiGetTpl("default.php");}
				} else {
					$_ENV["DOM"]=aikiGetTpl($tpl);
				}
			}
			if (($_ENV["DOM"]->text()=="") && $empty==1) {
				$file=$_SESSION["app_path"]."/tpl/{$_GET["id"]}.php";
				if ($form=="page" && $mode=="show" && is_file($file)) {$_ENV["DOM"]=aikiGetTpl($file);}
				//header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
				if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$_ENV["DOM"]=aikiGetTpl("404.php");} else {
					$_ENV["DOM"]=ki::fromString("[Ошибка 404] Страница отсутствует");
				}
			} else {
					if (!is_object($_ENV["DOM"])) {$_ENV["DOM"]=ki::fromString($_ENV["DOM"]); }
					$call="_{$Item["form"]}BeforeShowItem"; if (is_callable($call)) {$Item=$call($Item);}
					$call="{$Item["form"]}BeforeShowItem"; if (is_callable($call)) {$Item=$call($Item);}
					$_ENV["DOM"]->contentSetData($Item);
					$call=$form."ChangeHtml"; if (is_callable($call)) {$call($_ENV["DOM"],$Item);}
					$_ENV["DOM"]->contentTargeter($Item);
					if ($Item==array("id"=>$_GET["id"],"form"=>$_GET["form"]) && strip_tags($_ENV["DOM"]->outerHtml())==""
						&& $_GET["form"]=="page" && $_GET["mode"]=="show") {
						if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$_ENV["DOM"]=aikiGetTpl("404.php");} else {
							$_ENV["DOM"]=aikifromString("[Ошибка 404] Страница отсутствует");
						}
					}
			}
			if (is_object($_ENV["DOM"]) && isset($__form) && $_ENV["DOM"]->outerHtml()=="") {
				$_ENV["DOM"]=$__form;
				$_ENV["DOM"]->contentSetData($Item);
			}
			
			if (isset($Item["meta_description"])) {
				$tag='<meta name="description" content="'.$Item["meta_description"].'">';
				$meta=$_ENV["DOM"]->find("meta[name=description]",0);
				if (is_object($meta)) {$meta->before($tag); $meta->remove();} else {$_ENV["DOM"]->find("head")->append($tag);}
			}

			if (isset($Item["meta_keywords"])) {
				$tag='<meta name="keywords" content="'.$Item["meta_keywords"].'">';
				$meta=$_ENV["DOM"]->find("meta[name=keywords]",0);
				if (is_object($meta)) {$meta->before($tag); $meta->remove();} else {$_ENV["DOM"]->find("head")->append($tag);}
			}
			aikiBaseHref($_ENV["DOM"]);
		} else {echo $_ENV["DOM"]->outerHtml(); die;}
		
	
} else {
	include_once($_SESSION["engine_path"]."/index.php");
}
$_ENV["Item"]=$Item; 
return $_ENV["DOM"];
}
?>
