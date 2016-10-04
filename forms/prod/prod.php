<?
function prod__list() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$flag=""; $where="";
	if (isset($_GET["division"]) && $_GET["division"]>"") {$where=aikiWhereFromTree("prod_division",$_GET["division"],"division"); $flag="division";}
	$Item=aikiListItems($_GET["form"],$where);
	$Item["result"]=array_sort($Item["result"],"id");
	$Item["form"]=$_GET["form"];
	$out->contentSetData($Item);
	$out->contentSetValues($Item);
	$modal=$out->find("div.modal");
	foreach($modal as $m) {
		if ($m->attr("id")=="") {
			$m->attr("id","{$_GET["form"]}Edit");
		}
		$m->attr("data-backdrop","static");
		if ($m->find("[data-formsave]")->length && $m->find("[data-formsave]")->attr("data-formsave")=="") {
			$m->find("[data-formsave]")->attr("data-formsave","#{$_GET["form"]}EditForm");
		}
		if ($m->find(".modal-title")->html()=="") $m->find(".modal-title")->html("Редактирование");
	}
	if ($flag=="division") {$out=$out->find("#prodList .list")->html(); return $out;}
	if ($flag=="") {return $out->outerHtml();}
}

function prod__edit() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiReadItem($_GET["form"],$_GET["id"]);
	$Item["form"]=$_GET["form"];
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();}
	$out->contentSetData($Item);
	return $out->outerHtml();
}
?>
