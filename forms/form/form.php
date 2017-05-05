<?php
function form__create() {
	$res=array("error"=>false,"status"=>"Форма {$formName} успешно создана.","append"=>"");
	$formList=aikiListForms();
	$formName=$_REQUEST["name"];
	$formDescr=$_REQUEST["descr"];
	$formPath=$_SESSION["app_path"]."/forms/".$formName;
	$srcPath=$_SESSION["engine_path"]."/forms/form";
	if (in_array($formName,$formList)) {	
		$res=array("error"=>true,"status"=>"Форма {$formName} уже существует!");
		return json_encode($res);
	} else {
		if (!is_dir($formPath)) { mkdir($formPath,0755); }
		$modes=array("","edit","list","show");
		foreach($modes as $mode) {
			if ($mode>"") {$mode="_".$mode;}
			$file=file_get_contents("{$srcPath}/_sample{$mode}.php");
			$item=array("_form_"=>$formName);
			$file=str_replace(array('{{_form_}}','{{_descr_}}'),array($formName,$formDescr),$file);
			//$file=formPrepForm($file,$formName);
			file_put_contents("{$formPath}/{$formName}{$mode}.php",$file);			
			chmod("{$formPath}/{$formName}{$mode}.php",0766);
		}
		if ($_REQUEST["tolist"]=="on") {
			$settings=$_SESSION["settings"];
			$settings["forms"][]=array("name"=>$formName,"descr"=>$formDescr,"allow"=>"","disallow"=>"");
			$settings["id"]="settings";
			aikiSaveItem("admin",$settings);
			aikiSettingsRead();
			unset($settings);
			$res["append"]=formAddToList($formName,$formDescr);
		}
	}
	return json_encode($res);
}

function form_snippet() {
	if ($_SESSION["user_role"]=="admin") {
		$out=file_get_contents($_SESSION["engine_path"]."/forms/form/snippets/{$_GET["snippet"]}.htm");
		if (isset($_GET["formname"])) {$out=str_replace("{{form}}",$_GET["formname"],$out);}
		$out=str_replace("data-role","data-strip-role",$out);
		return $out;
	}
}

function form_getform() {
	if ($_SESSION["user_role"]=="admin") {
		$out=file_get_contents($_SESSION["app_path"].$_GET["path"]);
		return $out;
	}
}

function form_putform() {
	if ($_SESSION["user_role"]=="admin" && isset($_POST["content"])) {
		$out=file_put_contents($_SESSION["app_path"].$_GET["path"],$_POST["content"]);
		return $out;
	}
}

function form_designer() {
	$formlist=aikiListFormsFull(); $formlist=$formlist["app"];
	$out=aikiGetForm();
	$forms=array();
	foreach($formlist as $form) {
		if (!isset($forms[$form["form"]]) && $form["mode"]=="") {$forms[$form["form"]]=array("form"=>$form["form"],"dir"=>$form["dir"]);}
	}
	$sDir="/engine/forms/form/snippets";
	$snippets=aikiTreeReadObj("snippets",true);
	$snip=aikiFromString("");
	$tpl=aikiFromString('<li class="formDesignerSnippets">
	<a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span>
	<i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
	<i class="{{icon}} sidebar-nav-icon"></i>
	<span class="sidebar-nav-mini-hide">{{name}}</span></a>
	<ul data-type="{{type}}"></ul>
	</li>');
	$divs=$snippets->find("tree")->children("branch");
	foreach($divs as $d) {
		$dtpl=$tpl->clone();
		$item=array();
		$item["name"]=$d->find("name")->text();
		$item["type"]=$d->attr("data-id");
		$item["icon"]=$d->find("data icon");
		$dtpl->contentSetValues($item);
		$livs=$d->find("branch");
		foreach($livs as $li) {
			$dtpl->find("ul")->append("<li><a href='#snippet' data='{$li->attr("data-id")}'>{$li->find("name")->text()}</a></li>");
		}
		
		$snip->append($dtpl);
	}
	unset($dtpl,$item,$tpl,$d);
	$out->find(".formDesignerSnippets")->html($snip);
	$Item=array("forms"=>$forms);
	$out->contentSetData($Item);
	return $out->outerHtml();
}

function form_snippets() {
	$out=aikiTreeReadObj("snippets",true);
	return $out->outerHtml();
}

function form_designer1() {
	$formlist=aikiListFormsFull(); $formlist=$formlist["app"];
	$out=aikiGetForm();
	$forms=array();
	foreach($formlist as $form) {
		if (!isset($forms[$form["form"]]) && $form["mode"]=="") {$forms[$form["form"]]=array("form"=>$form["form"],"dir"=>$form["dir"]);}
	}
	$sDir="/engine/forms/form/snippets";
	$snippets=filesList($sDir);
	$snip=aikiFromString("");
	$menu=aikiFromFile($_SERVER["DOCUMENT_ROOT"].$sDir."/snippets.menu.htm");
	$divs=$menu->find("meta");
	foreach($divs as $d) {
		$dtpl=$menu->find("li.formDesignerSnippets",0)->clone();
		$item=array();
		$item["name"]=$d->attr("name");
		$item["type"]=$d->attr("type");
		$item["icon"]=$d->attr("icon");
		$dtpl->contentSetValues($item);
		$snip->append($dtpl);
	}
	unset($dtpl,$item,$divs,$menu);
	
	foreach($snippets as $s) {
		$sName=str_replace(".".$s["ext"],"",$s["name"]);
		if ($sName!=="snippets.menu") {
			$tmp=aikiFromFile($_SESSION["app_path"].$sDir."/".$s["name"]);
			$tmp=$tmp->find("meta[name=formDesigner]");
			if (is_object($tmp)) {
				$name=$tmp->attr("data-name");
				if ($name=="") {$name=$sName;}
				if ($snip->find("ul[data-type=".$tmp->attr("data-type")."]")->length) {
					$snip->find("ul[data-type=".$tmp->attr("data-type")."]")->append("<li><a href='#snippet' data='{$sName}'>{$name}</a></li>");
				} else {
					$snip->find("ul[data-type=other]")->append("<li><a href='#snippet' data='{$sName}'>{$name}</a></li>");
				}
					$snip->find("li:last a")->attr("data-zoom",$tmp->attr("data-zoom"));
			}
		}
	}
	$out->find(".formDesignerSnippets")->html($snip);
	$Item=array("forms"=>$forms);
	$out->contentSetData($Item);
	return $out->outerHtml();
}


function form_listModes() {
		$Item=aikiListFormsFull();
		return json_encode($Item);
}

function formPrepForm($file,$form) {
	$out=aikiFromString($file);
	$exc=array("prop","text","images","source");
	foreach($exc as $tab) {
		if (!isset($_POST[$tab]) OR $_POST[$tab]!=="on") {
			$tab=ucfirst($tab);
			$out->find("#{$form}{$tab}")->remove();
			$out->find(".nav li a[href=#{$form}{$tab}]")->parent("li")->remove();
		}
	}
	//$out->find(".nav li:first")->addClass("active");
	//$out->find($out->find(".nav li:first")->attr("href"))->addClass("active");
	return $out->aikiHtmlFormat();
}

function formAddToList($form,$name) {
	$list=aikiFromString("<ul class='formlist'></ul>");
	comAdminMenuAdd($list,$form,$name);
	return $list->find("ul.formlist")->html();
}

?>
