<?php
ini_set('display_errors', 0	);
include_once(__DIR__."/functions.php");
comSession();
aikiSettingsRead();
aikiDatabaseConnect();
aikiFormFunctions();
aikiParseUri();
if (is_file("{$_SESSION["root_path"]}/functions.php")) {include_once("{$_SESSION["root_path"]}/functions.php");}
$req=(parse_url($_SERVER["REQUEST_URI"]));
parse_str($req["query"]);

if (isset($form)) {formCurrentInclude($form);} else {$form="";}
$out=""; $res=0;
if ($res==false && is_callable($_GET["mode"])) {$res=true; $out=$_GET["mode"]();}
	$call="{$form}_{$mode}";
if ($res==false && is_callable($call)) {$res=true; $out=$call();} // в проектах
	$call="{$form}__{$mode}";
if ($res==false && is_callable($call)) {$res=true; $out=$call();} // в engine // почему-то ajax_out() ломает редактор
if ($res==false) include_once("{$_SESSION["engine_path"]}/forms/common/common.php");
	$call="common__{$mode}";
if ($res==false && is_callable($call)) {$res=true; $out=$call();} // в общем случае
	$call="ajax_{$mode}";
if ($res==false && is_callable($call)) {$res=true; $out=$call();}
if ($mode=="save") {$res=true; $out=ajax_formsave($form);}

if ($res==false) {
	$out=aikiGetForm($form,$mode); 
	if (is_object($out)) {
		$out->contentSetData(aikiReadItem());
		$res=true;
	}
}
if ($res==false && is_object($out)) {
	$Item=aikiReadItem($form,$_GET["id"]);
	$out->contentSetData($Item);
	$out=clearValueTags($out);
}

if ($res==true) {
	if (is_object($out)) {$out=$out->outerHtml();}
	echo $out;
	unset($out,$Item,$call,$form,$mode);
} else {echo "No function: ".$mode;}

aikiClearMemory();


function ajax_out($out) {
	if (!is_object($out)) {$out=aikiFromString($out);}
	aikiDatePickerOconv($out);
	$out->contentTargeter();
	return $out->outerHtml();
}


function ajax_listfiles() {
	$result=array();
	if ($_GET["path"]=="") {$path=$_SESSION["app_path"]."/uploads";} else { $path=$_GET["path"]; $path=str_replace($_SESSION["prj_path"],"",$path);}
	$files=filesList($path);
	if (is_array($files)) {
	foreach($files as $key => $file) {
		if (is_file($_SESSION["app_path"].$path."/".$file["name"])) {
			$result[]=$file["name"];
		}
	}
	}
	return json_encode($result);
}

function ajax_get_template($tpl=NULL) {
	if ($tpl==NULL) {$tpl=$_GET['name'];}
	$af="/tpl/{$tpl}";
	$ef="/engine/tpl/{$tpl}";
	if (is_file($_SESSION["app_path"].$af)) {
		$out=ki::fromFile("http://{$_SERVER["HTTP_HOST"]}{$af}");
	} else {
		$out=ki::fromFile("http://{$_SERVER["HTTP_HOST"]}{$ef}");
	}
	return $out;
}

function ajax_formsave($form) {
	$call="{$form}_formsave";
	if (is_callable($call)) {$ret=$call();} else {
		if (!isset($_POST["id"])) {$_POST["id"]="";}
		if (!isset($_GET["item"])) {$_POST["id"]="";}
		if (!isset($_SESSION["form"][$form]["datatype"])) {$datatype="file";} else {$datatype=$_SESSION["form"][$form]["datatype"];}
		if ($_POST["id"]=="" && $_GET["item"]>"") {$_POST["id"]=$_GET["item"];}
		$res=aikiFormSave($form,$datatype); $ret=array();
		if (isset($_GET["copy"])) {
			$old=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"]."/uploads/{$form}/{$_GET["copy"]}/");
			$new=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"]."/uploads/{$form}/{$_GET["item"]}/");
			recurse_copy($old,$new);
		}
		if ($res!="1") {
			$ret["error"]=0;
		} else {$ret["error"]=1; $ret["text"]=$res;}
	}
	return json_encode($ret);
}

function ajax_set_data() {
	$form=proper_parse_str(urldecode($_POST["form"]));
	if (!isset($_GET["data-mode"])) {$_GET["mode"]="list";} else {$_GET["mode"]=$_GET["data-mode"];}
	$Item=aikiReadItem($_GET["form"],$_GET["item"]);
	$call="{$_GET['form']}BeforeShowItem"; if (is_callable($call)) {$Item=$call($Item);} else {
	$call="_{$_GET['form']}BeforeShowItem"; if (is_callable($call)) {$Item=$call($Item,"list");}}
	$tpl=aikiFromString(html_entity_decode(stripcslashes(urldecode($_POST["tpl"]))));
	$tpl=aikiFromString(urldecode($_POST["tpl"]));
	foreach($tpl->find("[data-role]") as $dr) {$dr->removeClass("loaded"); }
	$tpl->contentSetData($Item);
	return clearValueTags($tpl);
}

function ajax_sess_kick() {
	return true;
}

function ajax_getid() {
	return json_encode(newIdRnd());
}

function ajax_change_id() {
	$path=formPathGet($_GET["form"],$_GET["new"]);
	$oldu=$_SESSION["app_path"].$path["uplform"].$_GET["old"]."/";
	$newu=$_SESSION["app_path"].$path["uplitem"];
	$oldi=$_SESSION["app_path"].$path["form"].$_GET["old"];
	$newi=$_SESSION["app_path"].$path["item"];

	if (is_file($oldi)) {rename($oldi,$newi); }
	if (is_dir($oldu)) {rename($oldu,$newu);}
}

function ajax_deleteform() {
	$res=false;
	if ($_SESSION["user_role"]!=="" && $_SESSION["user_role"]!=="noname") {
		$res=aikiDeleteItem($_GET["form"],$_GET["item"]);
	}
	return json_encode($res);
}

function ajax_deleteitem() {
	$res=false;
	if ($_SESSION["user_role"]!=="" && $_SESSION["user_role"]!=="noname") {
		$res=aikiDeleteItem($_GET["form"],$_GET["item"]);
	}
	return json_encode($res);
}

function ajax_deletefile() {
	$res=false;
	if (isset($_SESSION["user_role"]) AND $_SESSION["user_role"]!=="" AND $_SESSION["user_role"]!=="noname") {
		$filename=$_SERVER["DOCUMENT_ROOT"].$_GET["path"]."/".$_GET["file"];
		$filename=str_replace("//","/",$filename);
		if (unlink($filename)) {$res=array(); $res["error"]=0;} else {$res=array(); $res["error"]=1;}
	} 
	return json_encode($res);
}


function ajax_deletePath() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$fullpath=$_SERVER["DOCUMENT_ROOT"].$_GET["path"];
		$path=$_GET["path"];
		$path=str_replace("//","/",$path);
		if (is_dir($fullpath) && $path>"") {
			DeleteDir($path); $res["error"]=true;
			if (!is_dir($path)) {$res["error"]=true;} 
		} 
		if (is_file($fullpath) && $path>"") {
			if (unlink($fullpath)) {$res["error"]=true;} // true - значит ОК
		}
		if (is_link($fullpath) && $path>"") {
			if (unlink($fullpath)) {$res["error"]=true;} // true - значит ОК
		}

	}
	return json_encode($res);
}

function ajax_createFolder() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$dirname=$_SERVER["DOCUMENT_ROOT"].$_GET["path"]."/".$_GET["name"];
		$dirname=str_replace("//","/",$dirname);
		if (!is_dir($dirname)) {
			if (mkdir($dirname,0777)) {$res["error"]=true;} // true - значит ОК
		}
	}
	return json_encode($res);
}

function ajax_createFile() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$filename=$_SERVER["DOCUMENT_ROOT"].$_GET["path"]."/".$_GET["name"];
		$filename=str_replace("//","/",$filename);
		if (!is_dir($filename) && !is_file($filename) && !is_link($filename)) {
			file_put_contents($filename,"",LOCK_EX);
			if (is_file($filename)) {$res["error"]=true;}
		}
	}
	return json_encode($res);
}

function ajax_RenameFile() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$old=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["path"]."/".$_GET["old"]);
		$new=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["path"]."/".$_GET["new"]);
		if ($old!==$new && !is_dir($new) && !is_file($new) && !is_link($new)) {
			if (rename($old,$new)) {$res["error"]=true;}
		}
	}
	return json_encode($res);	
}

function ajax_copyFile() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$old=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["old"]);
		$new=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["new"]);
		if ($old!==$new && !is_dir($new) && !is_file($new) && !is_link($new)) {
			if (copy($old,$new)) {$res["error"]=true;}
		}
	}
	return json_encode($res);	
}

function ajax_moveFile() {
	$res=array(); $res["error"]=false;
	if ($_SESSION["user_role"]=="admin") {
		$old=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["old"]);
		$new=str_replace("//","/",$_SERVER["DOCUMENT_ROOT"].$_GET["new"]);
		if ($old!==$new && !is_dir($new) && !is_file($new) && !is_link($new)) {
			if (rename($old,$new)) {$res["error"]=true;}
		}
	}
	return json_encode($res);	
}

function ajax_createSymlink() {
	$res=false;
	if ($_SESSION["user_role"]=="admin") {
		$target=$_SERVER["DOCUMENT_ROOT"].$_GET["target"];
		$link=$_GET["link"];
		$target=str_replace("//","/",$target);
		if (!is_dir($target) OR !is_file($target)) {
			symlink($target, $link);
		}
	}
	return json_encode($res);
}

function ajax_readitem() {
	$Item=aikiReadItem($_GET["form"],$_GET["id"]);
	return json_encode($Item);
}

function ajax_get_dict() {
	$Item=aikiReadDict($_GET["dict"]);
	return json_encode($Item);
}

function ajax_get_tree() {
	$Item=aikiReadTree($_GET["tree"]);
	if (isset($_GET["item"])) {
		$Item=$Item["tree"];
		$Item=aikiTreeFindData($Item,"id",$_GET["item"]);
	}
	return json_encode($Item);
}

function ajax_cart() {
	$order_id=$_SESSION["order_id"];
	if (isset($_GET["action"])) {$call="cartAction";  if (is_callable($call)) { return $call($_GET["action"]);} }
	return $order_id;
}

function ajax_sitemap_generation() {
	return sitemapGeneration();
}

function ajax_content_set_data() {
	if (!isset($_POST["html"])) {$_POST["html"]="";}
	if (!isset($_POST["data"])) {$_POST["data"]="";}
	$html=aikiFromString("<div>".$_POST["html"]."</div>");
	$html->contentSetData($_POST["data"]);
	return clearValueTags($html->innerHtml());
}

function ajax_pagination() {
	$res=array();
	foreach($_POST as $key =>$val) {$$key=$val;}
	if (!isset($page)) $page=1;
	if (!isset($find)) $find="";
	$fe=aikiFromString("<div>".$foreach."</div>");
	$fe->find("[data-template={$tplid}]")->attr("data-find",$find);
	$fe->find("[data-template={$tplid}]")->removeClass("loaded");
	$fe->find("[data-template={$tplid}]")->attr("data-cache",$cache);
	$fe->find("[data-template={$tplid}]")->attr("data-page",$page);
	$fe->find("[data-template={$tplid}]",0)->append(urldecode($tpl));
	$fe->find("[data-template={$tplid}]",0)->tagForeach();
	$res["data"]=$fe->find("[data-template={$tplid}]",0)->innerHtml();
	$fe->find("#ajax-{$tplid}")->attr("data-idx",$idx);
	$res["pagr"]=$fe->find("#ajax-{$tplid}")->outerHtml();
	$res["pages"]=$fe->find("[data-template={$tplid}]")->attr("data-pages");
	return json_encode($res);
}


function proper_parse_str($str) {
  $arr = array();
  $pairs = explode('&', $str);
  foreach ($pairs as $i) {
    list($name,$value) = explode('=', $i, 2);
    if( isset($arr[$name]) ) {
      if( is_array($arr[$name]) ) { $arr[$name][] = $value; }
      else { $arr[$name] = array($arr[$name], $value); }
    } else {
      $arr[$name] = $value;
    }
  }
  return $arr;
}

function ajax_FieldBuild() {
	$res=array();
	$res["html"]=aikiFieldBuild($_POST["type"],$_POST["name"],$_POST["label"],$_POST["descr"],$_POST["value"],$_POST["id"]);
	if ($res["html"]==false) {$res=false;}
	return json_encode($res);
}

?>
