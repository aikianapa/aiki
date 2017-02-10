<?
function common__list() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiListItems($_GET["form"]);
	$Item["form"]=$_GET["form"];
	$out->contentSetData($Item);
	if ($out->find("div.modal")->attr("id")=="") {
		$out->find("div.modal")->attr("id","{$_GET["form"]}Edit");
	}
	$out->find("div.modal")->attr("data-backdrop","static");
	if ($out->find("[data-formsave]")->length && $out->find("[data-formsave]")->attr("data-formsave")=="") {
		$out->find("[data-formsave]")->attr("data-formsave","#{$_GET["form"]}EditForm");
	}
	$out->find(".modal-title")->html("Редактирование");
	return $out->outerHtml();
}

function common__edit() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	if (!isset($_GET["id"]) OR $_GET["id"]=="_new" OR $_GET["id"]=="") {$_GET["id"]=newIdRnd();}
	$Item=aikiReadItem($_GET["form"],$_GET["id"]);
	$Item["form"]=$_GET["form"];
	$out->contentSetData($Item); $i=0;
	return clearValueTags($out->outerHtml());
}

function common__show($Item=array()) {
	$out="";
	if (isset($_GET["form"]) && $_GET["form"]>"") {
		if (!isset($_GET["id"])) {$_GET["id"]="_new";}
		$Item=aikiReadItem($_GET["form"],$_GET["id"]);
		if ($_SESSION["error"]=="noitem") {
			header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			if (is_file($_SESSION["app_path"]."/tpl/404.php")) {$out=aikiGetTpl("404.php");} else {
				$out=ki::fromString("[Ошибка 404] Страница отсутствует");
			}
		} else {
			if (isset($Item["template"]) && $Item["template"]>"") {
					$out=aikiGetTpl($Item["template"]);
			} else {$out=aikiGetForm();}
			if ($out=="") {$out=ki::fromString("<html><div><h2>{{header}}</h2>{{text}}</div></html>");}
		}
		if (isset($Item["form"])) {
			formCurrentInclude($Item["form"]);
			$call="_{$Item["form"]}BeforeShowItem";
			if (is_callable($call)) {$Item=@$call($Item);}

			$call="{$Item["form"]}BeforeShowItem";
			if (is_callable($call)) {$Item=@$call($Item);}
		}
	}
	if (!is_object($out)) {$out=ki::fromString($out);}
	$out->contentSetData($Item);
	return $out->outerHtml();
}
?>
