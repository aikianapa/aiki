<?php
function form__create() {
	$res=array("error"=>false,"status"=>"Форма {$formName} успешно создана.");
	$formList=aikiListForms();
	$formName=$_REQUEST["name"];
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
			$file=str_replace('{{_form_}}',$formName,$file);
			$file=formPrepForm($file,$formName);
			file_put_contents("{$formPath}/{$formName}{$mode}.php",$file);			
			
			chmod("{$formPath}/{$formName}{$mode}.php",0766);
		}
	}
	return json_encode($res);
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

?>
