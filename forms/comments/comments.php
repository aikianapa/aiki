<?


function comments__widget() {
	$out=aikiGetForm();
	$out->contentSetData();
	return $out->outerHtml();
}


function comments__edit() {
	$out=aikiGetForm($_GET["form"],$_GET["mode"]);
	$Item=aikiReadItem("comments",$_GET["id"]); 
	if ($_GET["id"]=="_new") {$Item["id"]=newIdRnd();}
	$out->find("form button[data-formsave]")->parents(".form-group")->remove();
	$Item=_commentsBeforeShowItem($Item);
	$out->contentSetData($Item);
	return clearValueTags($out->outerHtml());
}

function comments__getajax() {
	switch($_GET["view"]) {
		case "modal" :
			$out=aikiGetForm($_GET["form"],"show");
			$Item=_commentsBeforeShowItem(aikiReadItem("comments",$_GET["item"]));
			$out->contentSetData($Item);
			return $out->outerHtml();
			break;
		case "new" :
			$out=aikiGetForm($_GET["form"],"edit");
			$Item["id"]=newIdRnd();
			if ($_SESSION["User"]!="Admin") {
				$out->find("textarea[name=reply]")->parents(".form-group")->remove();
				$out->find("input[type=checkbox]")->parents(".form-group")->remove();
				} else {
				$out->find(".modal-body button[formsave]")->parents(".form-group")->remove();
			}
			$out->contentSetData($Item);
			return clearValueTags($out->outerHtml());
			break;
	}
}

function _commentsBeforeShowItem($Item,$mode=NULL) {
	if ($mode==NULL) {$mode=$_GET["mode"];}
	switch($mode) {
		case "edit" :
			if (!isset($Item["date"]) OR $Item["date"]=="") {$Item["date"]=date("Y-m-d"); } else {	$Item["date"]=date("Y-m-d H:i:s",strtotime($Item["date"])); }
			break;
		case "list"	:
			$Item["date"]=date("d.m.Y H:i",strtotime($Item["date"]));
			$Item["text"]=getWords(strip_tags($Item["text"]),50);
			$Item["smalltext"]=getWords(strip_tags($Item["text"]),10);
			if ($_SESSION["User"]!="Admin" && (!$Item["show"]==1 OR !$Item["show"]=="on") ) {$Item=NULL;}
			break;
		default		:
			$Item["date"]=date("d.m.Y H:i",strtotime($Item["date"]));
			$Item["header"]="";
			break;
	}
	return $Item;
}

function _commentsBeforeSaveItem($Item) {
	if (!isset($Item["id"]) OR $Item["id"]=="_new") {$Item["id"]=newIdRnd();}
	if (!isset($Item["date"]) OR $Item["date"]=="") {$Item["date"]=date("Y-m-d H:i:s");}
	return $Item;
}

function _commentsAfterReadItem($Item) {
	if (isset($Item["show"]) && !isset($Item["visible"])) $Item["visible"]=$Item["show"];
	if (isset($Item["visible"]) && !isset($Item["show"])) $Item["show"]=$Item["visible"];
	return $Item;
}

?>
