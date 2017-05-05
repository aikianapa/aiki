<?php
function todo__ajax() {
	$res=false;
	if (isset($_GET["action"])) {
		$call = __FUNCTION__ ."_".$_GET["action"];
		if (is_callable($call)) $res=$call();
	}
	return json_encode($res);
}

function todo__ajax_add() {
	$Item=array(
		"id"		=> newIdRnd(),
		"form"		=> "todo",
		"user"		=> $_SESSION["user_id"],
		"task"		=> $_POST["task"],
		"category"	=> $_POST["category"],
		"status"	=> $_POST["status"],
		"done"		=> $_POST["done"],
		"time"		=> date("Y-m-d H:i:s"),
		"created"	=> date("Y-m-d H:i:s")
	);
	$res=aikiSaveItem("todo",$Item);
	if ($res) {$res=array("id"=>$Item["id"]);}
	return $res;
}

function todo__ajax_addcategory() {
	$Item=aikiReadItem("users",$_SESSION["user_id"]);
	if (!isset($Item["todo_categories"])) {$Item["todo_categories"]=array();}
	$id=newIdRnd();
	$add=array("id"=>$id,"category"=>$_POST["category"]);
//$Item["todo_categories"]=array(); // to delete //////////////////////////
	array_unshift($Item["todo_categories"],$add);
	$res=aikiSaveItem("users",$Item);
	if ($res) {$res=array("id"=>$id);}
	return $res;
}

function todo__ajax_upd() {
	$res=false;
	$Item=aikiReadItem("todo",$_POST["id"]);
	if ($Item["user"]==$_SESSION["user_id"]) {
		foreach($_POST as $key => $val) {	$Item[$key]=$_POST[$key];	}
		$res=aikiSaveItem("todo",$Item);
		if ($res) {$res=true;}
	}
	return $res;
}

function todo__ajax_counter() {
	$where='user = "'.$_SESSION["user_id"].'"';
	$list=aikiListItems("todo",$where);
	$user=aikiReadItem("users",$_SESSION["user_id"]);
	$res=array();
	foreach($user["todo_categories"] as $key => $val) {
		$res[$val["id"]]=0;
	}
	$res["unsorted"]=$res["arc"]=0;
	$list=new arrayObject($list["result"]);
	foreach($list as $item) {
		if (isset($res[$item["category"]])) {$res[$item["category"]]+=1;} else {$res["unsorted"]+=1;}
	}
	unset($list,$item);
	return $res;
}


function todo__ajax_getitem() {
	$res=false;
	$Item=aikiReadItem("todo",$_POST["id"]);
	if ($Item["user"]==$_SESSION["user_id"]) {$res=$Item;}
	return $res;
}

function todo__ajax_getlist() {
	$where='user = "'.$_SESSION["user_id"].'" AND category="'.$_GET["category"].'"';
	$tpl=aikiGetForm("todo","list");
	$tpl=$tpl->find(".task-list",0)->clone();
	$tpl->removeClass("loaded");
	$tpl->attr("where",$where);

	$out=aikiFromString("<div></div>");
	$out->find("div")->append($tpl);
	$out->contentSetData($list);
	echo $out->find("div")->html();
	unset($tpl,$out);
	die;
}



function todo__ajax_generate() {
	$res=false;
	
	
	
	for ($i=1; $i<5000; $i++) {
		
	$Item=array(
		"id"		=> newIdRnd(),
		"form"		=> "todo",
		"user"		=> $_SESSION["user_id"],
		"task"		=> "Абра швабра кадабра ".$i,
		"category"	=> "unsorted",
		"status"	=> "default",
		"done"		=> "",
		"time"		=> date("Y-m-d H:i:s")
	);
	aikiSaveItem("todo",$Item);	
	}
	
	
	
	return $res;
}


function todo__ajax_del() {
	$res=aikiDeleteItem("todo",$_POST["id"]);
	return $res;
}

function todoBeforeShowItem($Item) {
	$Item["timeview"]=date("d.m.y H:i",strtotime($Item["time"]));
	return $Item;
}

function todoAfterReadItem($Item) {
	if (!isset($Item["category"]) OR $Item["category"]=="") $Item["category"]="unsorted";
	return $Item;
}

?>
