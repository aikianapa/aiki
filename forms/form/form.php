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
		if (!is_dir($formPath)) { mkdir($formPath); }
		$modes=array("","edit","list","show");
		foreach($modes as $mode) {
			if ($mode>"") {$mode="_".$mode;}
			$file=file_get_contents("{$srcPath}/_sample{$mode}.php");
			$item=array("_form_"=>$formName);
			$file=str_replace(array('{{_form_}}','{{_descr_}}'),array($formName,$formDescr),$file);
			$file=formPrepForm($file,$formName);
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
	$out=file_get_contents($_SESSION["engine_path"]."/forms/form/snippets/{$_GET["snippet"]}.htm");
	return $out;
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