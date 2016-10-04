<?php
function tree__getajax() {
	$tree=aikiReadItem("tree",$_GET["from"]);
	if (!is_array($tree["tree"])) {$tree["tree"]=json_decode($tree["tree"],true);}
	if (!isset($_GET["item"])) {$tree=$tree["tree"];} else {
		$tree=tagTree_find($tree["tree"],$_GET["item"]);
	}
	return json_encode($tree);
}
?>
