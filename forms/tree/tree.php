<?php
function tree__getajax() {
	$tree=aikiReadTree($_GET["from"]);
	if (!isset($_GET["item"])) {$tree=$tree["tree"];} else {
		$tree=tagTree_find($tree["tree"],$_GET["item"]);
	}
	return json_encode($tree);
}

function _treeAfterReadItem($Item) {
	if ($_GET["mode"]=="edit" && $_GET["form"]=="tree") {
			//$Item["tree"]=json_decode(htmlentities(json_encode($Item["tree"])));
	}
	return $Item;
}
?>
