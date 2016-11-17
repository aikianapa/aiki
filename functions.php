<?php
ini_set('display_errors', 0);
include_once("{$_SERVER["DOCUMENT_ROOT"]}/engine/kiDom.php");
// ============================================================
// куски от tags_parser
// ============================================================
function contentControls($set="") {
	$res="*";
	$controls="[data-role]";
	$allow="[data-allow],[data-disallow],[data-disabled],[data-enabled],[data-readonly],[data-writable]";
	$target="[prepend],[append],[before],[after],[html]";
	if ($set!="") {$res=$$set;} else {$res="{$controls},{$allow},{$target}";}
	unset($controls,$allow,$target);
	return $res;
}

function aikiBeforeShowItem($Item,$mode="show",$form=null) {
	return aikiCallFormFunc("BeforeShowItem",$Item,$form,$mode);
}

function aikiCallFormFunc($name,$Item,$form=null,$mode=null) {
	
	if ($mode==null) {$mode=$_GET["mode"];}
	if ($mode=="") {$mode="list";}
	if ($form==null) {
		if (isset($Item["form"]) && $Item["form"]>"") {$form=$Item["form"];} else {$form=$_GET["form"];}
	}
	$sf=$_GET["form"]; $_GET["form"]=$form;
	formCurrentInclude($form);
	$func=$form.$name; $_func="_".$func;
	if (is_callable($func)) {$Item=$func($Item,$mode);} else {
		if (is_callable($_func)) {$Item=$_func($Item,$mode);}
	}
	$_GET["form"]=$sf;
	return $Item;
}

function aikiFieldBuild($type,$name,$label,$param,$value,$id="") {
	$res=false;
	switch($type) {
		case "call":
			$call=trim(explode(";",$param));
			if (is_callable($call)) {$res=$call($type,$label,$param,$value);}		
			break;
		case "dict":
			// city;id;name
			// [0] = city - имя справочника
			// [1] = id - имя поля для option[value]
			// [2] = name - имя поля для отображения option[value]
			$param=explode(";",trim($param));
			$dict=trim($param[0]);
			if (isset($param[1])) {$key=trim($param[1]);} else {$key="id";}
			if (isset($param[2])) {$val=trim($param[2]);} else {$val="name";}
			$html=aikifromString("<select class='form-control' data-name='{$name}' placeholder='{$label}' value='{$value}' data-role='dict' from='{$dict}' data-hide='data-role,from'>
				<option value='{{".$key."}}'>{{".$val."}}</option>
			</select>");
			$html->find("option[value={$value}]")->attr("selected",true);
			
			$html->contentSetData();
			$res=$html->outerHtml();
			break;
		case "tree":
			// districts;{%parent}_distr;true;id;name
			// [0] = districts - имя каталога
			// [1] = {%parent}_distr - id ветви; {%parent} - ссылка на родительскую ветвь
			// [2] = true - показывать или не показывать вложенные записи true/false
			// [3] = id - имя поля для option[value]
			// [4] = name - имя поля для отображения option[value]
			$param=explode(";",trim($param));
			$tree=trim($param[0]);
			if (isset($param[1])) {$item=trim($param[1]);} else {$item="";}
			if (isset($param[2])) {$branch=trim($param[2]);} else {$branch="true";}
			if (isset($param[3])) {$key=trim($param[3]);} else {$key="id";}
			if (isset($param[4])) {$val=trim($param[4]);} else {$val="name";}
			preg_match_all("/\{(.*?)\}/",$item,$match);
			if (isset($match[1][0]) && $id>"") { // перемещение по дереву, если вызов из форм редактирования каталога
				$pattern=$match[0][0];
				$match=$match[1][0];
				$aval=array("this","parent","child");
				if ($item=="this") {$item=$value;}
				$count=substr_count($match,"%");
				$_item=$item; $item=str_replace("%","",$match);
				if (in_array($item,$aval)) {
					$treedata=aikiReadTree($tree); 
					$parent=$id;
					for ($i=0; $i<=$count; $i++) {
						$tmp=aikiFindTreeData($treedata["tree"],"id",$parent);
						$parent=$tmp["parent"];
					}
					$item=str_replace($pattern,$parent,$_item);
				} else {$item="";}
			}
			$html=aikifromString("<select class='form-control' data-name='{$name}' placeholder='{$label}' value='{$value}' data-role='tree' from='{$tree}' item='{$item}' branch='{$branch}' data-hide='data-role,from,item,branch'>
				<option value='{{".$key."}}'>{{".$val."}}</option>
			</select>");
			$html->find("option[value={$value}]")->attr("selected",true);
			
			$html->contentSetData();
			$res=$html->outerHtml();
			break;
		case "enum":
			// 1:1111;2:22222;a:aaaaa
			// перечисление значений через ;
			// через : разделяются значение и отображение для option
			$param=explode(";",trim($param));
			$html=aikifromString("<select class='form-control' data-name='{$name}' placeholder='{$label}' value='{$value}'></select>");
			foreach($param as $key => $item) {
				if ($item>"") {
					$html->find("select")->append("<option></option>");
					$item=explode(":",$item);
					$html->find("option:last")->attr("value",$item[0]);
					if ($item[0]==$value) {$html->find("option:last")->attr("selected",true);}
					if (isset($item[1])) {$html->find("option:last")->html($item[1]);} else {$html->find("option:last")->html($item[0]);}
				}
			}
			
			$res=$html->outerHtml();
			break;
	}
	return $res;
}

function contentHasControls($str) {
	$controls=array("}}","data-role","data-allow","data-disallow","data-disabled","data-enabled","data-readonly","data-writable");
	foreach($controls as $key => $ctl ) {
		if (strpos($str,$ctl)) {return true;}
	}
	return false;
}

function tagTree_find($branch=array(),$id="",$parent=null) {
	$res=false;
	if ($id>"") {
		foreach($branch as $key => $val) {
			if ($val["id"]==$id) {
				$val["parent"]=$parent;
				$val["idx"]=$key;
				if (isset($val["children"])) $val["children"]=$val["children"][0];
				$res=$val; return $res;
			} else {
				if (isset($val["children"]) && $res==false) {
					$parent=$val["id"];
					$res=tagTree_find($val["children"][0],$id,$parent);
				}
			}
		}
	}
	return $res;
}

function aikiLibsAdd($__page) {contentAppends($__page);}
function contentAppends($__page) {
	if ($_SERVER["SCRIPT_NAME"]=="/engine/index.php" AND $_SESSION["user_role"]=="admin") {$engine=true;} else {$engine=false;}
	if ($engine==true OR $_SESSION["settings"]["editload"]=="on") {
			$__page->append('<div data-role="include" src="/engine/js/editor.php"></div>');}
	if ($engine==true OR $_SESSION["settings"]["upldload"]=="on") {
			$__page->append('<div data-role="include" src="/engine/js/uploader.php"></div>');}
	if ($engine==true OR $_SESSION["settings"]["imgviewer"]=="on") {
			$__page->append('<div data-role="include" src="/engine/js/imgviewer.php"></div>');}
	$__page->ContentSetData();
	comAdminMenu($__page);
	if ($engine==true OR $_SESSION["settings"]["jquery"]=="on") {
			$__page->find("head")->prepend('<script src="/engine/js/jquery.min.js"></script>');
			$__page->find("body")->append('<script src="/engine/js/modernizr-2.8.3.min.js"></script>');
			}
	if ($engine==true OR $_SESSION["settings"]["jqueryui"]=="on") {
			$__page->find("head script:last")->after('<script src="/engine/js/jquery-ui.min.js"></script>');}
	if ($engine==true OR $_SESSION["settings"]["bootstrap"]=="on") {
			$__page->find("head")->prepend('<link rel="stylesheet" href="/engine/bootstrap/css/bootstrap.min.css">');
			$__page->find("head script:last")->after('<script src="/engine/bootstrap/js/bootstrap.min.js"></script>');	}
	if ($engine==true OR $_SESSION["settings"]["aikiload"]=="on") {
			$__page->find("body")->append('<link rel="stylesheet" href="/engine/tpl/css/admin.css">');
			$__page->find("head script:last")->after('<script src="/engine/js/functions.js"></script>');}
	if ($engine==true OR $_SESSION["settings"]["appui"]=="on") {
			$__page->find("head")->append('	<link rel="stylesheet" href="/engine/appUI/css/bootstrap.min.css">
											<link rel="stylesheet" href="/engine/appUI/css/plugins.css">
											<link rel="stylesheet" href="/engine/appUI/css/main.css">
											<link rel="stylesheet" href="/engine/appUI/css/themes.css">
											<link rel="stylesheet" href="/engine/tpl/css/appUI.css">');
			$__page->find("body")->append('	<script src="/engine/appUI/js/plugins.js"></script>
											<script src="/engine/appUI/js/app.js"></script>');}
	if ($engine==true OR $_SESSION["settings"]["appuiplugins"]=="on") {
			$__page->find("head")->append('<link rel="stylesheet" href="/engine/appUI/css/plugins.css">');
			$__page->find("body")->append('<script src="/engine/appUI/js/plugins.js" data="appUIplugins"></script>');}

	if (isset($Item["meta_keywords"])) {
		$__page->find("head meta[name=keywords]")->remove();
		$__page->find("head")->prepend("<meta name='keywords' content='{$Item["meta_keywords"]}'>");
	}
	if (isset($Item["meta_description"])) {
		$__page->find("head meta[name=description]")->remove();
		$__page->find("head")->prepend("<meta name='description' content='{$Item["meta_description"]}'>");
	}
	$__page->find("head")->prepend("<meta name='generator' content='AiKi :: Engine (http://www.digiport.ru)'>");
}


function contentSetValuesStr($tag="",$Item=array(), $limit=2)
{
	if (is_object($tag)) {$tag=$tag->outerHtml();}
	if (!is_array($Item)) {$Item=array($Item);}
	if (is_string($tag)) {
	//$tag=strtr($tag,array("%7B%7B"=>"{{","%7D%7D"=>"}}"));
	$tag=str_replace("%7B%7B","{{",$tag);
	$tag=str_replace("%7D%7D","}}",$tag);
	if (strpos($tag, '}}') !== false ) {
		// функция подставляющая значения
		$tag = changeQuot($tag);			// заменяем &quot на "
		$spec = array('_form', '_mode', '_item', '_id');
		$exit = false;
		$err = false;
		$nIter = 0;
		$mask = '`(\{\{){1,1}(%*[\w\d]+|_form|_mode|_item|((_SETT|_SETTINGS|_SESS|_SESSION|_SRV|_COOK|_COOKIE|_GET|_POST|%*[\w\d]+)?([\[]{1,1}(%*[\w\d]+|"%*[\w\d]+")[\]]{1,1})*))(\}\}){1,1}`u';
		while(!$exit) {
			$nUndef = 0;
			$nSub = preg_match_all($mask, $tag, $res, PREG_OFFSET_CAPTURE);				// найти все вставки, не содержащие в себе других вставок
			if ($nSub !== false) {
				if ($nSub == 0) {$exit = true;} else {
					$text = '';
					$startIn = 0;		// начальная позиция текста за предыдущей заменой
					for ($i = 0; $i < $nSub; $i++)		// замена в исходном тексте найденных подстановок
					{
						$In = $res[2][$i][0];						// текст вставки без скобок {{ и }}
						$beforSize = $res[2][$i][1] - 2 - $startIn;
						$text .= substr($tag, $startIn, $beforSize);		// исходный текст между предыдущей и текущей вставками
						$default = false;
						$special = 0;
						switch($res[4][$i][0])					// префикс вставки
						{
							case '_SETT':
								$sub = '$_SESSION["settings"]';
								break;
							case '_SETTINGS':
								$sub = '$_SESSION["settings"]';
								break;
							case '_SESS':
								$sub = '$_SESSION';
								break;
							case '_SESSION':
								$sub = '$_SESSION';
								break;
							case '_COOK':
								$sub = '$_COOKIE';
								break;
							case '_COOKIE':
								$sub = '$_COOKIE';
								break;
							case '_GET':
								$sub = '$_GET';
								break;
							case '_SRV':
								$sub = '$_SERVER';
								break;
							case '_POST':
								$sub = '$_POST';
								break;
							case '':
								if(in_array($In, $spec))
								{
									$sub = '$_GET';
									$In = substr($In, 1, strlen($In) - 1);		// убираем символ _ в начале
									if (!isset($_GET["item"]) and ($In == 'item')) $In = 'id';
									if (!isset($_GET["id"]) and ($In == 'id')) $In = 'item';
								} else
								{
									$sub = '$Item';
								}
								break;
							default:									// 1ый индекс без скобок [] - префикса нет
								$sub = '$Item';
								$default = true;
								$n = strlen($res[4][$i][0]);
								$In = '[' . substr($In, 0, $n) . ']' . substr($In, $n, strlen($In) - $n);
								break;
						}
						if ($default)
						{
							$pos = 0;
						} else
						{
							$pos = strlen($res[4][$i][0]);
						}
						$sub .= setQuotes(substr($In, $pos, strlen($In) - $pos));		// индексная часть текущей вставки с добавленными кавычками у текстовых индексов
						if (eval('return isset(' . $sub . ');'))
						{
							if (eval('return is_array(' . $sub . ');'))
							{
								$text .= eval('return json_encode(' . $sub . ');');
							} else
							{
								$temp="";
								eval('$temp .= ' . $sub . ';');
								$temp=strtr($temp,array("{{"=>"#~#~","}}"=>"~#~#"));
								$text.=$temp;
							}
						} else {
							/*
							$skip=array("_GET","_POST","_COOK","_COOKIE","_SESS","_SESSION","_SETT","_SETTINGS");
							$tmp=explode("[",$res[2][$i][0]); $tmp=$tmp[0];
							if (in_array($tmp,$skip)) {
								$text.="";
							} else {
								$text .= '{{' . $res[2][$i][0] . '}}';;
							}
							*/
							$text.="";
							$nUndef++;
						}
						$startIn += $beforSize + strlen($res[2][$i][0]) + 4;
						if ($i+1 == $nSub)		// это была последняя вставка
						{
							$text .= substr($tag,  $startIn, strlen($tag) - $startIn);
						}
					}
					$tag = $text;

				}
			}
			$nIter++;
			if ($limit > 0 and $nIter == $limit) $exit = true;
			if ($nUndef == $nSub) $exit = true;
		}
			if (isset($_GET["mode"]) && $_GET["mode"]=="edit") {
				$tag=ki::fromString($tag);
				foreach($tag->find("pre") as $pre) {
					$pre->html(htmlspecialchars($pre->html()));
				}; unset($pre);
				$tag=$tag->htmlOuter();
			}
	}
	$tag=strtr($tag,array("#~#~"=>"{{","~#~#"=>"}}"));
	return $tag;
	}
}

// добавление кавычек к нечисловым индексам
function setQuotes($In) {
	$err = false;
	$mask = '`\[(%*[\w\d]+)\]`u';
	$nBrackets = preg_match_all($mask, $In, $res, PREG_OFFSET_CAPTURE);				// найти индексы без кавычек
	if ($nBrackets !== false) {
		if ($nBrackets == 0) {
			if (substr($In, 0, 2) != '["') {
				if (!is_numeric($In)) $In = '"' . $In . '"';
				$In = '[' . $In . ']';
			}
		} else {
			for ($i = 0; $i < $nBrackets; $i++) {
				if (!is_numeric($res[1][$i][0])) $In = str_replace('['.$res[1][$i][0].']', '["'.$res[1][$i][0].'"]', $In);
			}
		}
	}
	return $In;
}

// заменяем &quot на "
function changeQuot($Tag) {
	$mask = '`&quot[^;]`u';
	
	if (is_string($Tag)) {
		$nQuot = preg_match_all($mask, $Tag, $res, PREG_OFFSET_CAPTURE);				// найти &quot без последеующего ;
		if ($nQuot !== false) {
			if ($nQuot == 0) {$In = $Tag;} else {
				$In = '';
				$startIn = 0;		// начальная позиция текста за предыдущей заменой
				for ($i = 0; $i < $nQuot; $i++) {
					$beforSize = $res[0][$i][1] - $startIn;
					$In .= substr($Tag, $startIn, $beforSize) . '"';		// исходный текст между предыдущей и текущей &quot
					$startIn += $beforSize + 5;
					if ($i+1 == $nQuot)		// это была последняя &quot
					{
						$In .= substr($Tag,  $startIn, strlen($Tag) - $startIn);
					}
				}
			}
		}
	}
	return $In;
}

function findThumbnailSrc($img,$form="*",$id="*") {
	if ($img!=="{{img}}") {
		exec("find {$_SESSION["app_path"]}/uploads/{$form}/{$id}/{$img}",$ret);
		if (isset($ret[0])) {$ret=$ret[0];
		if (is_file($ret)) {
			$ret=str_replace($_SESSION["app_path"],$_SESSION["prj_path"],$ret);
			return $ret;
		}
		}
	}
}

function get_order_id() {
	if (isset($_COOKIE["order_id"])) {$order_id=$_COOKIE["order_id"];} else {$order_id="";}
	if ($order_id=="") {$order_id=newIdRnd();}
	setcookie("order_id",$order_id,time()+3600*72,"/");
	return $order_id;
}

function cartAction($action,$attr=array()) {
	// $attr список полей по которым идентифицируется строка заказа
	if (!isset($_SESSION["order_id"]) OR $_SESSION["order_id"]=="") {$_SESSION["order_id"]=get_order_id();}
	$order=aikiReadItem("orders",$_SESSION["order_id"]);
	if ($_SESSION["user_id"]>"" && (!isset($order["user_id"]) OR $order["user_id"]=="")) {$order["user_id"]=$_SESSION["user_id"];}
	switch($action) {
		case "add-to-cart": cartAddItem($order,$attr); break;
		case "cart-item-recalc": cartItemRecalc($order); break;
		case "cart-item-remove": cartItemRemove($order); break;
		case "cart-clear": cartClear($order); break;
	}
}

function cartItemRecalc($order) {
	$index=$_GET["index"];
	unset($_GET["mode"]);
	unset($_GET["action"]);
	unset($_GET["index"]);
	$item=$order["items"][$index];
	foreach($_GET as $fld => $value) {
		$item[$fld]=$value;
	}
	$order["items"][$index]=$item;
	if (!isset($order["form"]) OR $order["form"]=="") {$order["form"]="orders";};
	$order["total"]=cartCalcTotal($order);
	aikiSaveItem($order["form"],$order);
}

function cartItemRemove($order) {
	$index=$_GET["index"];
	unset($_GET["mode"]);
	unset($_GET["action"]);
	unset($_GET["index"]);
	unset($order["items"][$index]);
	$order["items"]=array_values($order["items"]);
	if (!isset($order["form"]) OR $order["form"]=="") {$order["form"]="orders";};
	$order["total"]=cartCalcTotal($order);
	aikiSaveItem($order["form"],$order);
}

function cartClear($order) {
	$order["items"]=array();
	$order["total"]=0;
	aikiSaveItem("orders",$order);
}

function cartAddItem($order,$attr=array()) {
	unset($order["firstImg"]);
	unset($_GET["action"]);
	unset($_GET["mode"]);
	if ($_GET["id"]>"" && $_GET["quant"]>"") {
		if (!isset($order["items"])) {$order["items"]=array();}
		$pos=cartItemPos($order,$attr);
		if (is_numeric($pos)) {$order["items"][$pos]=$_GET;}
		$order["total"]=cartCalcTotal($order);
		aikiSaveItem("orders",$order);
	}
}

function cartCalcTotal($order) {
	$order["total"]=0;
	foreach($order["items"] as $item) {
		$order["total"]+=$item["quant"]*$item["price"];
	}; unset($item);
	return $order["total"];
}

function cartItemPos($order,$attr) {
	if (count($attr)==0) {$attr=array("id","idx","form");}
	if (!isset($order["items"])) {$order["items"]=array();}
	$flag=0;  $pos=0;
	foreach($order["items"] as $key => $Item) {
		if ($flag==0 && cartItemPosCheck($Item,$attr)==false) {$pos++;} else {$flag=1;}
	}
	return $pos;
}


function cartItemPosCheck($Item,$attr=array()) {
	$res=true;
	foreach($attr as $k => $fld) {
		if ( $Item[$fld]!=$_GET[$fld] ) {$res=false;}
	}
	return $res;
}

function aikiFromString($str="") {
	return ki::fromString($str);
}

function aikiFromFile($str="") {
	return ki::fromFile($str);
}

function aikiInString($string,$find) {
	$res = false;
	$pattern = '/'.$find.'/iu';
	$res=preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE, 3);
	return $res;
}

function aikiAddItemGal($Item=array(),$Field="text") {
	if (isset($Item["images_position"])) {
		$gallery='<div data-role="gallery"></div>';
		if ($Item["images_position"]=="top") {$Item[$Field]=$gallery."\r\n".$Item[$Field];}
		if ($Item["images_position"]=="bottom") {$Item[$Field].="\r\n".$gallery;}
	}
	if (isset($Item["intext_position"]) && $Item["intext_position"]["pos"]>"") {
		if ($Item["intext_position"]["width"]>"") {$w=$Item["intext_position"]["width"];} else {$w=240;}
		if ($Item["intext_position"]["height"]>"") {$h=$Item["intext_position"]["height"];} else {$h=120;}
		$pos=$Item["intext_position"]["pos"];
		if ($pos=="left") {$margin="0px 10px 10px 0px";} else {$margin="0px 0px 10px 10px";}
		$imgsrc="/thumb/{$w}x{$h}/src".aikiGetItemImg($Item);
		$img="<img src='{$imgsrc}' style='float:{$pos};margin:{$margin}' alt='img' class='img-responsive'>";
		$Item["text"]=$img.$Item["text"];
	}
	return $Item;
}

function aikiListTpl() {
$dir=$_SESSION["app_path"]."/tpl";
$list=array();
if (is_dir($dir)) {
	exec("find {$dir} -name '*.php'",$list);
	foreach($list as $l => $val) {
		$list[$l]=str_replace($dir,"",$val);
		if (substr_count($list[$l],"/")==1) {$list[$l]=substr($list[$l],1);}
	}
}
$list=array_sort($list);
return $list;
}

function aikiListForms() {
	$exclude=array("common","admin","source");
	$list=array();
	$eList=aikiListFilesRecursive($_SESSION["engine_path"] ."/forms");
	$aList=aikiListFilesRecursive($_SESSION["app_path"] ."/forms");
	$arr=$eList; foreach($aList as $a) {$arr[]=$a;}
	unset($eList,$aList);
	foreach($arr as $i => $name) {
			$inc=strpos($name,".inc");
			$ext=explode(".",$name); $ext=$ext[count($ext)-1];
			$name=substr($name,0,-(strlen($ext)+1));
			$name=explode("_",$name); $name=$name[0];
			if ($ext=="php" && !$inc && !in_array($name,$exclude)) {
				$exclude[]=$list[]=$name;
			}
	}
	unset($arr);
	return $list;
}

function aikiListFilesRecursive($dir,$path=false) {
   $list = array();
   $stack[] = $dir;
   while ($stack) {
       $thisdir = array_pop($stack);
       if ($dircont = scandir($thisdir)) {
           $i=0; $idx=0;
           while (isset($dircont[$i])) {
               if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
                   $current_file = "{$thisdir}/{$dircont[$i]}";
                   if (is_file($current_file)) {
					   if ($path==true) {
							$list[$idx]["file"] = "{$dircont[$i]}"; 
							$list[$idx]["path"] = "{$thisdir}"; 
						} else { $list[] = "{$dircont[$i]}"; }
                       $idx++;
                   } elseif (is_dir($current_file)) {
						$stack[] = $current_file;
                   }
               }
               $i++;
           }
       }
   }
   return $list;
}


function aikiTableProcessor($out) {
	$data=array(); $grps=array(); $total=array(); $grand=array();
	$tmp=$out->attr("data-group"); if ($tmp>"") {$groups=attrToArray($tmp);} else {$groups=array(null);}
	$tmp=$out->attr("data-total"); if ($tmp>"") {$totals=attrToArray($tmp); $total=array();}
	if ($out->is("[data-suppress]")) {$sup=true;} else {$sup=false;}
	$lines=$out->find("tr");
	$index=0;
	foreach ($lines as $tr) {
		unset($grp_id,$grpidx);
		$fields=$tr->find("td[data-fld]:not([data-eval])"); $Item=array();
		foreach($fields as $field) {$Item[$field->attr("data-fld")]=$field->text();}
		$fields=$tr->find("[data-eval]");
		foreach($fields as $field) { 
			$evalStr=contentSetValuesStr($field,$Item);
			eval ("\$tmp = ".$field->text().";"); 	$field->text($tmp);
		}
		foreach($groups as $group) {
			$grp_text=$tr->find("[data-fld={$group}]")->text();
			if (!isset($grp_id)) {$grp_id=$grp_text;} else {$grp_id.="|".$grp_text;}
			if (!isset($grpidx)) {$grpidx=$group;} else {$grpidx.="|".$group;}
			if (!isset($grps[$grp_id])) {$grps[$grp_id]=array("data"=>array(),"total"=>array());}
			$grps[$grp_id]["grpidx"]=$grpidx;
			$grps[$grp_id]["data"][]=$index;
			if (isset($totals)) {foreach($totals as $totfld) {
				$totval=$tr->find("[data-fld={$totfld}]")->text()*1;
				if (!isset($grps[$grp_id]["total"][$totfld])) {$grps[$grp_id]["total"][$totfld]=0;}
				$grps[$grp_id]["total"][$totfld]+=$totval;
				if (!isset($grand[$totfld])) {$grand[$totfld]=0;}
				if ($group==$groups[0]) $grand[$totfld]+=$totval;
			}}
		}
		$index++;
	}
	ksort($grps);
	$grps=array_reverse($grps,true);
	$tbody=aikiFromString("<tbody type='result'></tbody>");
	$ready=array();
	foreach($grps as $grpid => $grp) {
		$inner="";
		$count=count($grp["data"])-1;
		foreach($grp["data"] as $key => $idx) {
			if (!in_array($idx,$ready)) {
				$tpl=$out->find("tr:eq({$idx})")->outerHtml();
				if ($sup==false) $tbody->append($tpl);
			}
			if ($key==$count AND count($grp["total"])>0) {
				// выводим тоталы группы
				$trtot=aikiFromString("<tr>".$out->find("tr:eq({$idx})")->html()."</tr>");
				$totchk=array();
				foreach($grp["total"] as $fld => $total) {
					$trtot->find("td[data-fld={$fld}]")->html($total);
					$totchk[]=$fld;
				}
				$trtot->find("tr")->attr("data-group",$grp["grpidx"]);
				$trtot->find("tr")->attr("data-group-value",$grpid);
				$trtot->find("tr")->addClass("data-total success");
				$grpchk=explode("|",$grp["grpidx"]);
				$tmp=$trtot->find("td:not([data-fld])"); foreach($tmp as $temp) {$temp->html("");}
				$tdflds=$trtot->find("td[data-fld]");
				foreach($tdflds as $tdfld) {
					$data_fld=$tdfld->attr("data-fld");
					if (!in_array($data_fld,$grpchk) && !in_array($data_fld,$totchk)) {$tdfld->html("");}
					if (in_array($data_fld,$grpchk)) {$tdfld->addClass("data-group");}
					if (in_array($data_fld,$totchk)) {$tdfld->addClass("data-total");}
				}
				$inner.=$trtot->outerHtml();
				
			}
			$ready[]=$idx;
		}
		$tbody->append($inner);
	}
	// выводим общий итог
	if (count($grp["total"])>0) {
		$grtot=aikiFromString("<tr>".$out->find("tr:eq({$idx})")->html()."</tr>");
		$grtot->find("tr")->addClass("data-grand-total info");
		$tmp=$grtot->find("td"); foreach($tmp as $temp) {$temp->html("");}
		$grflds=$grtot->find("td[data-fld]");
		foreach($grflds as $grfld) {
			$data_fld=$grfld->attr("data-fld");
			if (in_array($data_fld,$totchk)) {
				$grfld->html($grand[$data_fld]);
				$grfld->addClass("data-total");
			}
		}
	}
	$tbody->append($grtot);
	$out->html($tbody->innerHtml());
}

function aikiGetItemImg($Item=null,$idx=0,$noimg="",$imgfld="images") {
	$res=false; $count=0;
	if ($Item==null) {$Item=$_SESSION["Item"];}
	if (!is_file("{$_SERVER["DOCUMENT_ROOT"]}/{$noimg}")) {
		if (is_file("{$_SERVER["DOCUMENT_ROOT"]}/engine/uploads/__system/{$noimg}")) {
			$noimg="/engine/uploads/__system/{$noimg}";
		} else {
			$noimg="/engine/uploads/__system/image.jpg";
		}
	}
	$image=$noimg;
	if (isset($Item[$imgfld])) {
		if (!is_array($Item[$imgfld])) {$Item[$imgfld]=json_decode($Item[$imgfld],true);}
		if (!is_array($Item[$imgfld])) {$Item[$imgfld]=array();}
		foreach($Item[$imgfld] as $key => $img) {
			if (!isset($img["visible"])) {$img["visible"]=1;}
			if ($res==false AND $img["visible"]==1 AND is_file("{$_SESSION["app_path"]}/uploads/{$Item["form"]}/{$Item["id"]}/{$img["img"]}")) {
				if ($idx==$count) {
					$image="{$_SESSION["prj_path"]}/uploads/{$Item["form"]}/{$Item["id"]}/{$img["img"]}"; $res=true;
				}
				$count++;
			}
		}; unset($img);
	}
	return urldecode($image);
}

function attrAddData($data,$Item,$mode=FALSE) {
	$data=stripcslashes(html_entity_decode($data));
	$data=json_decode($data,true);
	if (!is_array($Item)) {$Item=array($Item);}
	if ($mode==FALSE) $Item=array_merge($data,$Item);
	if ($mode==TRUE) $Item=array_merge($Item,$data);
	return $Item;
}

// ============================================================
// ============================================================

function aikiWhere($list,$where=NULL) {
	$where=htmlspecialchars_decode($where);
	$where=strtr($where,array("'"=>'"',"&#039;"=>'"',"&quot;"=>'"'));
	$result=array();
	if (!$where==NULL) {
		foreach($list as $key => $item) {

			if (!aikiWhereItem($item,$where)) {unset($list[$key]);}

		}; unset($item,$wSave,$where);
	}
				unset($_SESSION["aikiWhereColRef"]);
				unset($_SESSION["aikiWhereOper"]);

	return $list;
}

function aikiWhereItem($item,$where=NULL) {
	$where=htmlspecialchars_decode($where);
	$where=strtr($where,array("'"=>'"',"&#039;"=>'"',"&quot;"=>'"'));
	$result=array();
	$res=true;
	if (!$where==NULL) {
		if (substr($where,0,1)=="%") {$phpif=substr($where,1);} else {
			//if (!strpos(strtolower($where)," like ")) {
				$phpif=aikiWherePhp($where,$item);
			/*} else {
				$phpif="";
				$where=contentSetValuesStr($where,$item);
				$parser = new PHPSQLParser("where {$where}");
				$parser = $parser->parsed; $parser = $parser["WHERE"];
				foreach ($parser as $node) {$phpif.=aikiWhereNode($node,$item);}
			}*/
		}
		@eval('if ( '.$phpif.' ) { $res=1; } else { $res=0; } ;');
		unset($parser);
	};
	return $res;
}

function aikiWherePhp($str="",$item=array()) {
	$str=contentSetValuesStr($str,$item);
	$str=" ".trim(strtr($str,array(
					"("=>" ( ",
					")"=>" ) ",
					"="=>" == ",
					">"=>" > ",
					"<"=>" < ",
					">="=>" >= ",
					"<="=>" <= ",
					"<>"=>" !== "
	)))." ";
	$exclude=array("AND","OR","LIKE");
	preg_match_all('/\w+(?!\")\b/iu',$str,$arr);
	foreach($arr[0] as $a => $fld) {
		if (!in_array(strtoupper($fld),$exclude)) {
			$str=str_replace(" {$fld} ",' $item["'.$fld.'"] ',$str);
		}
	}

	if (strpos(strtolower($str)," like ")) {
		preg_match_all('/\S*\slike\s\S*/iu',$str,$arr);
		foreach($arr[0] as $a => $cls) {
			$tmp=explode(" like ",$cls); 
			if (count($tmp)==2) {
				$str=str_replace($cls,'aikiWhereLike('.$tmp[0].','.$tmp[1].')',$str);
			}
		}
	}
	return $str;
}


function aikiWhereNode($node,$item=array()) {
	$res="";
	switch ($node["expr_type"]) {
		case "colref":
			if (isset($item[$node["base_expr"]])) {
				if (is_array($item[$node["base_expr"]])) {
					$_SESSION["aikiWhereColRef"]='in_array({{_val}},$item["'.$node["base_expr"].'"])';
				} else {
					$_SESSION["aikiWhereColRef"]='$item["'.$node["base_expr"].'"]';
				}
				$_SESSION["aikiWhereColLike"]='$item["'.$node["base_expr"].'"]';
			} else {$_SESSION["aikiWhereColRef"]=' "" ';}
			break;
		case "operator":
			$op=$node["base_expr"];
			if ($op=="=") {$op="==";}
			if (strtoupper($op)=="AND") {$op="AND";}
			if (strtoupper($op)=="OR") {$op="OR";}
			if ($op=="like") {
				$_SESSION["aikiWhereColRef"]=$_SESSION["aikiWhereColLike"];
				$_SESSION["aikiWhereOper"]=$op;
			} else {
				if (isset($_SESSION["aikiWhereColRef"])) {
					if (substr($_SESSION["aikiWhereColRef"],0,8)=="in_array") {
						if ($op!=="==") {
							$res.=$_SESSION["aikiWhereColLike"]." ".$op." ";
							unset($_SESSION["aikiWhereColRef"],$_SESSION["aikiWhereColLike"]);
						}
					} else {
						$res.=$_SESSION["aikiWhereColRef"]." ".$op." ";
						unset($_SESSION["aikiWhereColRef"]);
					}
				} else {$res.=" {$op} ";}
			}
			unset($_SESSION["aikiWhereColLike"]);
			break;
		case "bracket_expression":
			$res.=" (";
			foreach($node["sub_tree"] as $sub) {
				$res.=aikiWhereNode($sub,$item);
			}
			$res.=")";
			break;
		default:
			if (isset($_SESSION["aikiWhereColRef"]) AND isset($_SESSION["aikiWhereOper"])) {
				$res.=' aikiWhereLike('.$_SESSION["aikiWhereColRef"].','.$node["base_expr"].') ';
				unset($_SESSION["aikiWhereColRef"],$_SESSION["aikiWhereOper"]);
			} else {
				if (isset($_SESSION["aikiWhereColRef"]) && substr(trim($_SESSION["aikiWhereColRef"]),0,8)=="in_array") {
					$res=str_replace("{{_val}}",$node["base_expr"],$_SESSION["aikiWhereColRef"]);
					unset($_SESSION["aikiWhereColRef"]);
				} else {
					$res.=$node["base_expr"];
				}
			}
			break;
	}
	$res.=" ";
	return $res;
}

function aikiWhereLike($ref,$val) {
	if (is_array($ref)) {
		$ref=implode("|",$ref);
	} else {
		$val=trim($val);
		$val=str_replace(" ","|",$val);
	}
	$res=preg_match("/{$val}/ui",$ref);
	return $res;
}


function engineSettingsRead() {
	if (isset($_SESSION["settings"])) {
		if (isset($_SESSION["settings"]["variables"])) {
			foreach($_SESSION["settings"]["variables"] as $key => $val) {unset($_SESSION["key"]);}
		}
		foreach($_SESSION["settings"] as $key => $val) {unset($_SESSION["key"]);}
		unset($_SESSION["settings"]);
	}
	$_SESSION["error"]="";
	$settings=fileReadItem("admin","settings");
	if (isset($settings["projects"]) && ($settings["projects"]=="on" OR $settings["projects"]=="1")) {
		$domain=explode(".",str_replace("www.","",strtolower($_SERVER["HTTP_HOST"])));
		if (count($domain)==3) {
			if ($domain[0]!="www") {
				$_SESSION["project"]=$domain[0];
				$_SESSION["app_path"].="/projects/".$domain[0];
				$settings=fileReadItem("admin","settings".$_SESSION["project"]);
				if (isset($_SESSION["settings"]["variables"])) {
					foreach($_SESSION["settings"]["variables"] as $key => $val) {unset($_SESSION["key"]);}
				}
				foreach($_SESSION["settings"] as $key => $val) {unset($_SESSION["key"]);}
			}
		} else {
			$_SESSION["project"]="";
		}
	}
	
	if (isset($settings["elogin"]) AND $settings["elogin"]=="on") {
		$settings["elogin"]="email";
	} else { $settings["elogin"]="login";}

	$_SESSION["settings"]=$settings; unset($_SESSION["settings"]["variables"]);
	if (isset($settings["variables"])) {
		$variables=$settings["variables"];
		if (is_array($variables)) { foreach($variables as $key => $val) {
			$_SESSION["settings"][$val["engine_variable"]]=$val["engine_value"];
		}}
	}
	if (!isset($_SESSION["settings"]["login"]) OR $_SESSION["settings"]["login"]=="") {$_SESSION["settings"]["login"]="admin"; $_SESSION["settings"]["pass"]="admin";}
	return $_SESSION["settings"];
}

function aikiDatabaseConnect() {
	$settings=$_SESSION["settings"];
	if (isset($settings["store"]) && $settings["store"]=="on") {
		$_SESSION["mysql"]= new mysqli($settings["dbhost"],$settings["dbuser"],$settings["dbpass"],$settings["dbname"]);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
		}
		$_SESSION["mysql"]->query("SET NAMES utf8");
		$_SESSION["mysql"]->query("SET collation_connection=utf8_general_ci");
		$_SESSION["mysql"]->query("SET collation_server=utf8_general_ci");
		$_SESSION["mysql"]->query("SET character_set_client=utf8");
		$_SESSION["mysql"]->query("SET character_set_connection=utf8");
		$_SESSION["mysql"]->query("SET character_set_results=utf8");
		$_SESSION["mysql"]->query("SET character_set_server=utf8");
		$_SESSION["mysql"]->query("
			CREATE TABLE IF NOT EXISTS `jdb` (
			`id` varchar(12) NOT NULL,
			`form` text NOT NULL,
			`project` text NOT NULL,
			`json` longtext NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
}

function engineReadDict($name) {
	$dict=fileReadItem("dict",$name);
	if (isset($dict["fields"]) && is_array($dict["fields"])) {
	foreach($dict["fields"] as $inx => $field) {
	foreach($dict["data"] as $key => $line) {
		$Item[$key]=$line;
	}}; unset($field); unset($line);
	} else {$Item=array();}
return $Item;
}

function aikiReadTree($name) {
	$tree=aikiReadItem("tree",$name);
	$tree["tree"]=json_decode($tree["tree"],true);
	return $tree;
}


function aikiTreeFindData($branch,$field,$value) {
	return aikiFindTreeData($branch,$field,$value);
}

function aikiTreeGetPath($tree=array(),$id,$path=null) {
	$res=aikiFindTreeData($tree,"id",$id);
	if ($res["parent"]>"") {
		$path="[\"children\"][0][{$res["idx"]}]".$path;
		$path=aikiTreeGetPath($tree,$res["parent"],$path)["path"];
		$res=$path;
	} else {
		$path="[{$res["idx"]}]".$path;
	}
	$res=array("idx"=>$res["idx"],"parent"=>$res["parent"],"path"=>$path);
	return $res;
}

function aikiTreeGetIdListFrom($tree,$id,$inc=true) {
	// Возвращает список id ветвей, начиная с текущего
	// если $inc=false то возвращаются id без текущего
	if (!is_array($tree)) {$tree=aikiReadTree($tree); $tree=$tree["tree"];}
	if ($inc==true) {$list=array($id);} else {$list=array();}
	$childs=aikiFindTreeData($tree,"id",$id);
	if (isset($childs["children"])) {
		foreach($childs["children"] as $key =>  $child) {
			$lid=aikiTreeGetIdListFrom($tree,$child["id"]);
			foreach($lid as $key => $val) {$list[]=$val;}
		}
	}
	return $list;
}

function aikiWhereFromTree($tree,$id,$field,$inc=true) {
	if (!is_array($tree)) {$tree=aikiReadTree($tree); $tree=$tree["tree"];}
	$list=aikiTreeGetIdListFrom($tree,$id,$inc);
	$where=array();
	foreach($list as $key => $val) {$where[]=$field.' = "'.$val.'"';}
	if (count($where)) {
		$where=implode(" OR ",$where);
		$where="( {$where} )";
		return $where;
	} else {return "";}
}


function aikiTreeAddBranch($tree=array(),$id=null,$data) {
	$path=aikiTreeGetPath($tree,$id);
	$str = 'tree'.$path["path"];
	if (!isset($data["id"])) $data["id"]=newIdRnd();
	if (!isset($data["name"])) $data["name"]="undefined";
	if (!isset($data["data"])) $data["data"]="";
	if ($path["path"]=="[]") {eval('$'.$str.'=$data;');} else {
		eval('$'.$str.'["children"][0][]=$data;');
	}
	return $tree;
}

function aikiTreeRemoveBranch($tree=array(),$id=null) {
	$path=aikiTreeGetPath($tree,$id);
	$str = 'tree'.$path["path"];
	if ($path["path"]!=="[]") eval('unset($'.$str.');');
	return $tree;
}

function aikiFindTreeData($branch,$field,$value,$parent=null) {
	if (is_string($branch)) {$branch=aikiReadTree($branch)["tree"];}
	$res=false;
	foreach($branch as $key => $val) {
		$val["parent"]=$parent;
		$val["idx"]=$key;
		if ($val[$field]==$value) {
			if (isset($val["children"])) $val["children"]=$val["children"][0];
			$res=$val; return $res;
		} else {
			if (isset($val["children"]) && $res==false) {
				$res=aikiFindTreeData($val["children"][0],$field,$value,$val["id"]);
			}
		}
	}
	return $res;
}

function aikiReadDict($name) {return engineReadDict($name);}
function aikiSettingsRead() {return engineSettingsRead();}

function mysqlCheckTable($form) {
	$res=false;
	if ($result = $_SESSION["mysql"]->query("SHOW TABLES LIKE '{$form}' ;")) {
		$row = $result->fetch_row();
		if ($form==$row[0]) {$res=true;}
		$result->close();
	} else {echo mysqli_error($_SESSION["mysql"]);}
	return $res;
}

function ReadItem($form,$id,$datatype="file") {
	$func=$datatype."ReadItem";
	$Item=$func($form,$id);
	return $Item;
}

function aikiReadItem($form=null,$id=null,$func=true) {
		$Item=array();
		if ($form==null && isset($_GET["form"])) {$form=$_GET["form"];}
		if ($id==null && isset($_GET["id"])) {$id=$_GET["id"];}
		if (isset($_SESSION["settings"]["store"]) AND $_SESSION["settings"]["store"]=="on") {$datatype="mysql";} else {$datatype="file";}
		$read=$datatype."ReadItem";
		if ($datatype=="file") {
			if ($form!==null && $id!==null) $Item=$read($form,$id,false,$func); // доп.параметр
		} else {
			if ($form!==null && $id!==null) $Item=$read($form,$id,$func);
		}
		if ($Item["id"]=="_new") {$Item["id"]=newIdRnd();}
	return $Item;
}

function aikiSaveItem($form,$Item,$func=true) {
	if ($_SESSION["settings"]["store"]=="on") {$datatype="mysql";} else {$datatype="file";}
	$save=$datatype."SaveItem";
	if ($form=="tree" && isset($Item["tree"]) && !is_string($Item["tree"])) {$Item["tree"]=json_encode($Item["tree"], JSON_HEX_QUOT | JSON_HEX_APOS);}
	if ($datatype=="file") {
		$res=$save($form,$Item,false,$func); // доп.параметр
	} else {
		$res=$save($form,$Item,$func);
	}
	$cache=$_SERVER["DOCUMENT_ROOT"].$_SESSION["prj_path"]."/contents/{$form}/_cache/{$Item["id"]}";
	if (is_file($cache)) {unlink($cache);}
	return $res;
}

function aikiListItems($form,$where=NULL,$engine=FALSE) {
	if (isset($_SESSION["settings"]["store"]) AND $_SESSION["settings"]["store"]=="on") {$datatype="mysql";} else {$datatype="file";}
	$func=$datatype."ListItems";
	$res=$func($form,$where,$engine);
	return $res;
}

function get_template($name=NULL) {
	if ($name==NULL) {$name=$_GET["name"];}
	if ($tpl==NULL) {$tpl=$_GET['name'];}
	$af="/tpl/{$tpl}";
	$ef="/engine/tpl/{$tpl}";
	if (is_file($_SESSION["app_path"].$af)) {
		$out=ki::fromFile("http://{$_SERVER["HTTP_HOST"]}{$af}");
	} else {
		$out=ki::fromFile("http://{$_SERVER["HTTP_HOST"]}{$ef}");
	}
	$out->contentSetData();
	return $out->outerHtml();
}


function dict_filter_value($dict,$field,$value) {
	if (is_string($dict)) {$dict=aikiReadDict($dict);}
	$res=false;
	foreach($dict as $line) {
		if ($res==false) {
			if ($line[$field]==$value) {$res=$line;}
		}
	}; unset($line);
	return $res;
}

function checkAllow($list) {
	if (!is_array($list)) {$list=explode(",",trim($list));}
	$list = array_map('trim', $list);
	$role=$_SESSION["user_role"];
	if (in_array($role,$list)) {$res=true;} else {$res=false;}
	return $res;
}

function checkDisallow($list) {
	if (!is_array($list)) {$list=explode(",",trim($list));}
	$list = array_map('trim', $list);
	$role=$_SESSION["user_role"];
	if (in_array($role,$list)) {$res=false;} else {$res=true;}
	return $res;
}



function aikiLogin() {
	if (isset($_POST["mode"]) && $_POST["mode"]=="login") {
		$_SESSION["user"]=$_SESSION["User"]=$_SESSION["user_id"]=$_SESSION["user-id"]=$_SESSION["user_role"]=$_SESSION["user-role"]="";
		if (($_POST["login"]==$_SESSION["settings"]["login"] AND $_POST["pass"]==$_SESSION["settings"]["pass"]))  {
			setcookie("user_id","",time()-3600,"/"); unset($_COOKIE["user_id"]);
			$_SESSION["User"]=$_SESSION["user"]="Admin";
			$_SESSION["user-id"]=$_SESSION["user_id"]="admin";
			$_SESSION["user-role"]=$_SESSION["user_role"]="admin";
		} else {
			$users=aikiListItems("users"," {$_SESSION['settings']['elogin']} = '{$_POST['login']}' ");
			$users=$users["result"]; $res=false;
			foreach($users as $user) {if ($res==false) {
				if ($user[$_SESSION['settings']['elogin']] == $_POST["login"]) {
					$error="";
					if ($user["active"]!="on") {$error="active";}
					if ($user["password"] == $_POST["pass"] OR $user["password"] == md5($_POST["pass"])) {} else {$error="pass";}
					if ($error=="") {
						$_SESSION["User"]=$_SESSION["user"]=$user[$_SESSION['settings']['elogin']];
						$_SESSION["user-id"]=$_SESSION["user_id"]=$user["id"];
						$_SESSION["user-role"]=$_SESSION["user_role"]=$user["role"];
						$res=true;
					}
				}
			}}; unset($user);
		}
		if ($_SESSION["user"]>"") {
			if (isset($_POST["login-remember-me"]) && $_POST["login-remember-me"]=="on") {setcookie("user_id",$_SESSION["user_id"],time()+3600*24*30,"/");}
			$role=dict_filter_value("user_role","code",$_SESSION["user_role"]);
			$redirect=$role["redirect"];
			header("Refresh: 0; URL={$_SERVER["HTTP_SCHEME"]}{$redirect}");
			echo "Вход успешно выполнен, ждите...";
			die;
		}
	}

	if (isset($_GET["form"]) && ( $_GET["form"]=="logout" OR $_GET["form"]=="page") && $_GET["id"]=="logout") {
		$_SESSION["User"]=$_SESSION["user"]=$_SESSION["user-role"]=$_SESSION["user_role"]=$_SESSION["user-id"]=$_SESSION["user_id"]="";
		setcookie("user_id","",time()-3600,"/"); unset($_COOKIE["user_id"]);
		$_GET["mode"]="home";
		header("Refresh: 0; URL=http://{$_SERVER["HTTP_HOST"]}");
		echo "Выход из системы, ждите...";
		die;
	}

	if (isset($_COOKIE["user_id"]) && $_COOKIE["user_id"]>"") {
		if ($_SESSION["user_id"]>"") {setcookie("user_id",$_SESSION["user_id"],time()+60*60*24*30,"/");} // запоминаем на месяц
		$user=aikiReadItem("users",$_COOKIE["user_id"]);
		$_SESSION["User"]=$_SESSION["user"]=$user[$_SESSION['settings']['elogin']];
		$_SESSION["user-id"]=$_SESSION["user_id"]=$user["id"];
		$_SESSION["user-role"]=$_SESSION["user_role"]=$user["role"];
	}

	if (!isset($_SESSION["user_role"]) OR $_SESSION["user_role"]=="") {$_SESSION["user-role"]=$_SESSION["user_role"]="noname";}
}

function aikiGetForm($form=NULL,$mode=NULL,$engine=false) {
	if ($form==NULL) {$form=$_GET["form"];}
	if ($mode==NULL) {$mode=$_GET["mode"];}
	return formGetForm($form,$mode,$engine);
}

function formGetForm($form,$mode,$engine=false) {
	$current=""; $flag=false;
	$path=array("/forms/{$form}_{$mode}.php","/forms/{$form}/{$form}_{$mode}.php","/forms/{$form}/{$mode}.php");
	foreach($path as $form) {
		if ($flag==false) {
			if (is_file($_SESSION["engine_path"].$form)) {$current=$_SESSION["engine_path"].$form; $flag=$engine;}
			if (is_file($_SESSION["root_path"].$form) && $flag==false) {$current=$_SESSION["root_path"].$form; $flag=true;}
			if (is_file($_SESSION["app_path"].$form) && $flag==false) {$current=$_SESSION["app_path"].$form; $flag=true;}
		}
	}; unset($form);
	$out=ki::fromFile("{$current}");
	if ($current=="") {
		$common="{$_SESSION["engine_path"]}/forms/common/common_{$mode}.php";
		if (is_file($common)) {
			$out=ki::fromFile($common);
		} else {
			$out="Error! Form not found.";
			$_SESSION["error"]="noform";
		}
	}
	return $out;
}

function aikiGetTpl($tpl=NULL,$path=FALSE) {
	$__page="";
	if ($tpl==NULL && isset($_SESSION["engine_tpl"])) {$tpl=$_SESSION["engine_tpl"];}
	if (!isset($_GET["mode"])) {
		$_GET["mode"]="show";
		$_GET["form"]="page";
		$_GET["id"]="home";
	} else {
		$mode=$_GET["mode"]; $form=$_GET["form"];
		if ($tpl==NULL) {$tpl="{$_GET["form"]}_{$_GET["mode"]}.php";}
	}
	if ($_GET["mode"]=="show" && $_GET["form"]=="login") {$tpl="login.php";}
	if ($path==FALSE) {
		$tpl="/tpl/".$tpl; $current=""; $res=false;
		if ($res==false && is_file($_SESSION["prj_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["prj_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["app_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["app_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["root_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["root_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["engine_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"]."/forms/common/common_{$_GET["mode"]}.php")) {$current=$_SESSION["engine_path"]."/forms/{common/common_{$_GET["mode"]}.php"; $res=true;}

		if ($res==false && is_file($_SESSION["app_path"].$tpl)) {$current=$_SESSION["app_path"].$tpl; $res=true;}
		if ($res==false && is_file($_SESSION["root_path"].$tpl)) {$current=$_SESSION["root_path"].$tpl; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"].$tpl)) {$current=$_SESSION["engine_path"].$tpl; $res=true;}
		$current=str_replace($_SESSION["root_path"],"",$current);

			if (isset($_GET["form"]) && $_GET["form"]>"") {$form=$_GET["form"];}
			if (isset($_GET["mode"]) && $_GET["mode"]>"") {$mode=$_GET["mode"];}

			if (!isset($_SESSION["getTpl"])) { // нужно, чтобы небыло зацикливания
				$inc=array(
					"{$_SESSION["root_path"]}/forms/{$_GET["form"]}.php", "{$_SESSION["root_path"]}/forms/{$_GET["form"]}/{$_GET["form"]}.php",
					"{$_SESSION["engine_path"]}/forms/{$_GET["form"]}.php", "{$_SESSION["engine_path"]}/forms/{$_GET["form"]}/{$_GET["form"]}.php"
				); $res=FALSE;
				foreach($inc as $k => $file) {
					if (is_file("{$file}") && $res==FALSE ) {include_once("{$file}"); $res=TRUE;}
				}
				include_once("{$_SESSION["engine_path"]}/forms/common/common.php");

				$res=false;	$_SESSION["getTpl"]=true;
				if ($res==false && is_callable($mode)) {$__page=$mode(); $res=true;}
				$call="{$form}_{$mode}"; if ($res==false && is_callable($call)) {  $__page=$call(); $res=true;} // в проектах
				$call="common__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в общем случае
				$call="{$form}__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в engine
				unset($_SESSION["getTpl"]);
			}
			if (!is_object($__page) && $current>"") $__page=ki::fromFile("{$_SERVER["DOCUMENT_ROOT"]}{$current}");
			if (!is_object($__page) && $current=="") $__page=ki::fromString("");
	} else {
		if (!is_file($tpl)) {$tpl=normalizePath("{$_SERVER["DOCUMENT_ROOT"]}/{$tpl}");}
		$__page=ki::fromFile($tpl);
	}
	$file=explode("/",$tpl); $file=$file[count($file)-1];
	if ($path==FALSE) $_SESSION["tplpath"]=strtr($tpl,array($_SERVER["DOCUMENT_ROOT"]=>"",$file=>""));
	aikiCheckCache($__page);
	if ($path==false) aikiBaseHref($__page);
	return $__page;
}

function aikiBaseHref($__page,$path=false) {
	if (!$__page->find("base")->length && $__page->find("head")->length) {
		if (!isset($_SESSION["tplpath"])) {$_SESSION["tplpath"]="/";}
		$_SESSION["tplpath"]=normalizePath($_SESSION["tplpath"]);
		$__page->find("head")->prepend("<base href='{$_SESSION["tplpath"]}'>");
	}
}


function aikiCheckCache($__page) {
	// 0 - кэш не используется
	// 1 - кэш используется
	// 2 - кэш требует обновления
	$res=$_SESSION["cache"]=0;
	if ($__page->find("meta[name=cache]")->length) {
		$timeout=$__page->find("meta[name=cache]")->attr("content");
		if ($timeout>0  OR $timeout=="*") {
			$dir=$_SERVER["DOCUMENT_ROOT"].$_SESSION["prj_path"]."/contents/{$_GET["form"]}/_cache";
			if (!is_dir($dir)) { mkdir($dir);}
			if (is_file($dir."/{$_GET["id"]}")) {
				$cache=ki::fromFile($dir."/{$_GET["id"]}");
				$expired=$cache->find("meta[name=cache_expired]")->attr("content");
				if (time()<$expired OR $timeout=="*") {
					$__page->find("head")->remove();
					$__page->find("body")->before($cache->find("head")->outerHtml());
					$__page->find("body")->remove();
					$__page->find("head")->after($cache->find("body")->outerHtml());
					$res=$_SESSION["cache"]=1;
				} else {$res=$_SESSION["cache"]=2;}
			} else {$res=$_SESSION["cache"]=2;}
		}
	}
	return $res;
}

function aikiSaveCache($__page) {
	if ($_SESSION["cache"]==2) {
		$__page->find("meta[name=cache_expired]")->remove();
		$file=$_SERVER["DOCUMENT_ROOT"].$_SESSION["prj_path"]."/contents/{$_GET["form"]}/_cache/{$_GET["id"]}";
		$expired=$__page->find("meta[name=cache]")->attr("content")+time();
		$__page->find("meta[name=cache]")->after('<meta name="cache_expired" content="'.$expired.'">');
		$content=$__page->outerHtml();
		$res=file_put_contents($file,$content, LOCK_EX);
		$_SESSION["cache"]=1;
	}
}

function getTemplate($tpl=NULL,$path=FALSE) { // устаревшая функция
	if ($tpl==NULL && isset($_SESSION["engine_tpl"])) {$tpl=$_SESSION["engine_tpl"];}
	if (!isset($_GET["mode"])) {
		$_GET["mode"]="show";
		$_GET["form"]="page";
		$_GET["id"]="home";
	} else {
		$mode=$_GET["mode"]; $form=$_GET["form"];
		if ($tpl==NULL) {$tpl="{$_GET["form"]}_{$_GET["mode"]}.php";}
	}
	if ($_GET["mode"]=="show" && $_GET["form"]=="login") {
		$tpl="login.php";
	}
	if ($path==FALSE) {
		$tpl="/tpl/".$tpl; $current=""; $res=false;
		if ($res==false && is_file($_SESSION["prj_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["prj_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["app_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["app_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["root_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["root_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php")) {$current=$_SESSION["engine_path"]."/forms/{$_GET["form"]}_{$_GET["mode"]}.php"; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"]."/forms/common/common_{$_GET["mode"]}.php")) {$current=$_SESSION["engine_path"]."/forms/{common/common_{$_GET["mode"]}.php"; $res=true;}

		if ($res==false && is_file($_SESSION["app_path"].$tpl)) {$current=$_SESSION["app_path"].$tpl; $res=true;}
		if ($res==false && is_file($_SESSION["root_path"].$tpl)) {$current=$_SESSION["root_path"].$tpl; $res=true;}
		if ($res==false && is_file($_SESSION["engine_path"].$tpl)) {$current=$_SESSION["engine_path"].$tpl; $res=true;}
		$current=str_replace($_SESSION["root_path"],"",$current);

			if (isset($_GET["form"]) && $_GET["form"]>"") {$form=$_GET["form"];}
			if (isset($_GET["mode"]) && $_GET["mode"]>"") {$mode=$_GET["mode"];}

			if (!isset($_SESSION["getTpl"])) { // нужно, чтобы небыло зацикливания
				$inc=array(
					"{$_SESSION["root_path"]}/forms/{$_GET["form"]}.php", "{$_SESSION["root_path"]}/forms/{$_GET["form"]}/{$_GET["form"]}.php",
					"{$_SESSION["engine_path"]}/forms/{$_GET["form"]}.php", "{$_SESSION["engine_path"]}/forms/{$_GET["form"]}/{$_GET["form"]}.php"
				); $res=FALSE;
				foreach($inc as $k => $file) {
					if (is_file("{$file}") && $res==FALSE ) {include_once("{$file}"); $res=TRUE;}
				}
				include_once("{$_SESSION["engine_path"]}/forms/common/common.php");

				$res=false;	$_SESSION["getTpl"]=true;
				if ($res==false && is_callable($mode)) {$__page=$mode(); $res=true;}
				$call="{$form}_{$mode}"; if ($res==false && is_callable($call)) {  $__page=$call(); $res=true;} // в проектах
				$call="common__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в общем случае
				$call="{$form}__{$mode}"; if ($res==false && is_callable($call)) {$__page=$call(); $res=true;} // в engine
				unset($_SESSION["getTpl"]);
			}
			if (!isset($__page) OR (!is_object($__page) && $current>"")) $__page=ki::fromFile("{$_SERVER["DOCUMENT_ROOT"]}{$current}");
			if (!is_object($__page) && $current=="") $__page=ki::fromString("");
	} else {
		if (!is_file($tpl)) {$tpl="{$_SERVER["DOCUMENT_ROOT"]}/{$tpl}";}
		$__page=ki::fromFile($tpl);
	}
	return $__page;
}

function fileListItems($form,$where=NULL,$engine=FALSE) {
	$result="";
	if ($engine==TRUE) {
		$dir=$_SERVER['DOCUMENT_ROOT']."/engine/contents/$form/";
	} else {
		$dir=$_SESSION["app_path"]."/contents/$form/";
	}
	formCurrentInclude($form);
	// обрабатываем переменную сессии с типом данных
	// engine - читать из данных движка
	// app - читать из данных коренвого проекта
	// prj (не обязательно) - по-умолчанию данные проекта
	if (isset($_SESSION[$form]["data-type-tmp"])) {
		$type=$_SESSION[$form]["data-type-tmp"];
		if ($type=="engine") {$вшп=$_SESSION["engine_path"]."/contents/".$form."/";}
		if ($type=="app") {
			$dir=$_SESSION["app_path"]."/contents/".$form."/";
			$dir=str_replace($_SESSION["prj_path"],"/",$dir);
		}
	}

	if (is_dir($dir)) {  if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		if (is_file($dir.$file)) {
			$data=fileReadItem($form,$file);
			$result[$file]=$data;
		}
        }; unset($file,$data);
        closedir($dh);
   	}}
	if ($where!=NULL) {$result=aikiWhere($result,$where);}
		$after="_".$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($result);}
		$after=$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($result);}
	if (!is_array($result)) $result=array();
	$out['result']=$result;
//	$out=json_encode($out);
return $out;
}

function aikiDictOconv($dict,$value,$search_fld="id",$return_fld=null) {
	if (is_string($dict)) {$dict=aikiReadDict($dict);}
	return $dict[array_search_assoc($dict,$value)][$return_fld];
}

function array_search_assoc($array,$value,$search_fld="id",$return_fld=null) {
	$i=0;
	do
		if($array[$i][$search_fld] == $value) {
			if ($return_fld==null) {return $i;} else {return $array[$i][$return_fld];}
		}
	while(++$i<count($array));
}

function fileDeleteItem($form,$id,$path=false) {
	$res=false;
	$file=$_SESSION["app_path"]."/contents/".$form."/".$id;
	if ($path==true) { $file=$_SERVER['DOCUMENT_ROOT'].$form.$id; }
	// обрабатываем переменную сессии с типом данных
	// engine - читать из данных движка
	// app - читать из данных коренвого проекта
	// prj (не обязательно) - по-умолчанию данные проекта
	if (isset($_SESSION[$form]["data-type-tmp"])) {$type=$_SESSION[$form]["data-type-tmp"];} else {$type="file";}
	if ($type=="engine") {$file=$_SESSION["engine_path"]."/contents/".$form."/".$id;}
	if ($type=="app") {
		$file=$_SESSION["app_path"]."/contents/".$form."/".$id;
		$file=str_replace($_SESSION["prj_path"],"",$file);
	}
	if (is_file($file)) {unlink($file); $res=true;}
	return $res;
}

function fileReadItem($form,$id,$path=false,$func=true) {
	$_SESSION["error"]="";
	$Item=array(); $Item["id"]=$id;
	$before="_{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }
	$before="{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }
	$file=$_SESSION["app_path"]."/contents/".$form."/".$id;
	if ($path==true) { $file=$_SERVER['DOCUMENT_ROOT'].$form.$id; }
	// обрабатываем переменную сессии с типом данных
	// engine - читать из данных движка
	// app - читать из данных коренвого проекта
	// prj (не обязательно) - по-умолчанию данные проекта
	if (isset($_SESSION[$form]["data-type-tmp"])) {
		$type=$_SESSION[$form]["data-type-tmp"];
		if ($type=="engine") {$file=$_SESSION["engine_path"]."/contents/".$form."/".$id;}
		if ($type=="app") {
			$file=$_SESSION["app_path"]."/contents/".$form."/".$id;
			$file=str_replace($_SESSION["prj_path"],"",$file);
		}
	}
	if (is_file($file)) {
		$file=file_get_contents($file); 
		/*if (strpos($file,'\\\"')) {
			$Item=data_json_decode($file);
			aikiSaveItem($form,$Item); // сохраняем, чтобы при следующем чтении был короткий вариант
		} else {
			$Item=json_decode($file,TRUE);
		}*/
		$Item=json_decode($file,TRUE);
	} else {$_SESSION["error"]="noitem";}
	//if (is_file($file)) {$Item=json_decode(file($file)[0],TRUE);} else {$_SESSION["error"]="noitem";}
	$after="_".$form."AfterReadItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	$after=$form."AfterReadItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	if (isset($form) && !isset($Item["form"])) {$Item["form"]=$form;}
	unset($file,$before,$after,$type);
	return $Item;
}

function fileSaveItem($form,$Item,$path=false,$func=true) {
	if ($path==false) {
		formPathCheck($form);
		if ($Item["id"]=="_new" ) {$Item["id"]=newIdRnd();}
	}
	$before="_{$form}BeforeSaveItem"; 	if (is_callable($before) && $func==true) {$Item=$before($Item);}
	$before="{$form}BeforeSaveItem";	if (is_callable($before) && $func==true) {$Item=$before($Item);}
	if ($path==true) {
		$file=$_SESSION["app_path"]."/".$form;
	} else {
		$file=$_SESSION["app_path"]."/contents/".$form."/".$Item["id"];
	}
	$file=str_replace("//","/",$file);
	$jsonItem=json_encode($Item, JSON_HEX_QUOT | JSON_HEX_APOS);
	$res=file_put_contents($file,$jsonItem, LOCK_EX);
	$after="_{$form}AfterSaveItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	$after="{$form}AfterSaveItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	unset($Item,$jsonItem,$file,$before,$after,$form,$path);
	return $res;
}

function aikiFormSave($form,$datatype=NULL) {
	if ($datatype==NULL) {$datatype="file";}
	if (isset($_SESSION["settings"]["store"]) && $_SESSION["settings"]["store"]=="on") {$datatype="mysql";}
	if ($datatype=="file") {
		$Item=fileReadItem($form,$_POST["id"]);
		if (isset($fields) && is_array($fields)) {
			foreach ($fields as $key => $val) {if (isset($_POST[$val])) {$Item[$val]=$_POST[$val];}}
		} else {
			foreach($_POST as $key => $val) {$Item[$key]=$_POST[$key];}
		}
//		if ($form=="admin") $Item=adminBeforeFormSave($Item);
		$Item["form"]=$form;
		unset($Item["firstImg"]);
		formPathCheck($form,$Item["id"]);
		$res=aikiSaveItem($form,$Item);
	} else {
			if ($form=="admin") {
				$Item=$_POST;
				$res=fileSaveItem($form,$Item);
			} else {
				if (mysqlCheckTable($form)) {
					//$fields=mysqlReadDict($form);
					$Item=mysqlReadItem($form,$_POST["id"]);
					if ($Item==FALSE) {$Item=""; $_POST["id"]=mysqlInsertItem($form,$_POST["id"]); }
					//foreach ($fields as $key => $val) {if (isset($_POST[$val])) {$Item[$val]=$_POST[$val];}}
				} 
				$Item=$_POST;
				$Item["form"]=$form;
				unset($Item["firstImg"]);
				formPathCheck($form,$Item["id"]);
				$res=aikiSaveItem($form,$Item);
			}
	}
	return $res;
}


function formSave($form,$datatype="file",$table="") {
// $datatype: file - file system; mysql = database system
	if ($datatype=="file") {
		$Item=fileReadItem($form,$_POST["id"]);
		if (isset($fields) && is_array($fields)) {
			foreach ($fields as $key => $val) {if (isset($_POST[$val])) {$Item[$val]=$_POST[$val];}}
		} else {
			$Item=$_POST;
		}
		$Item["form"]=$form;
		formPathCheck($form,$Item["id"]);
//		if ($form=="admin") $Item=adminBeforeFormSave($Item);
		$res=fileSaveItem($form,$Item);
	} else {
		if ($table=="") $table=$form;
		$Item=mysqlReadItem($table,$_POST["id"]);
		if ($Item==FALSE) {$Item=""; $_POST["id"]=mysqlInsertItem($table,$_POST["id"]); }
		$Item=$_POST;
		$Item["form"]=$form;
		formPathCheck($form,$Item["id"]);
		$res=mysqlSaveItem($table,$Item);
	}
	return $res;
}

function aikiDeleteItem($form,$id) {
	$Item=aikiReadItem($form,$id);
	$before=$form."BeforeDeleteItem";
	if (is_callable ($before)) { $Item=aikiReadItem($form,$id); $Item=$before($Item) ; }
	if ($_SESSION["settings"]["store"]=="on") {$datatype="mysql";} else {$datatype="file";}
	$res=array(); $res["error"]=0;
	$dir=formPathGet($_GET["form"],$_GET["item"]);
	if ($_GET["upl"]=="true" OR !isset($_GET["ulp"])) {	$res1=DeleteDir($dir["uplitem"]); }
	$call="{$datatype}DeleteItem";
	$del=$call($form,$id);
	if ($del==false) {$res["error"]=1;}
	$after=$form."AfterDeleteItem";
	if (is_callable ($after)) {$Item=$after($Item) ;}
	return $res;
}

function aikiDeleteList($form,$where) {
	$list=aikiListItems($form);
	foreach($list["result"] as $key => $Item) {
		aikiDeleteItem($form,$Item["id"]);
	}
}


function jdbListItems($form,$where=NULL,$engine=FALSE) {
	if (mysqlCheckTable("jdb")) {
		$func=$form."MysqlListItems";
		if (is_callable($func)) {
			$out['result']=$func();
		} else {
			$SQL="select * from jdb WHERE form = '{$form}';";
			$func=$form."BeforeGetListItems";
			if (is_callable($func)) {$SQL=$func($SQL);}
			$result = $_SESSION["mysql"]->query($SQL) or die("Query failed (jdbListItems): " . mysqli_error($_SESSION["mysql"]));
			$array=array();
			while($data = mysqli_fetch_array($result)) {
				$array[]=json_decode($data["json"],true);
			}; unset($data);
			if ($where!=NULL) {$result=aikiWhere($array,$where);}
			$after="_".$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($array);}
			$after=$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($array);}
				$out['result']=$array;
			}
	} else {
		echo "Error: 'jdb' table is not present!";
		$out["result"]=array();
	}
	if (!is_array($out["result"])) $out["result"]=array();
	return $out;
}

function jdbReadItem($form,$id,$func=true) {
	$Item=FALSE;
	$before="_{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }
	$before="{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }
	$result = $_SESSION["mysql"]->query("SELECT * FROM jdb WHERE id = '{$id}' AND form = '{$form}' LIMIT 1;");
	if ($result) {
		$Item=$result->fetch_array(MYSQLI_ASSOC);
		$Item=json_decode($Item["json"],true);
		mysqli_free_result($result);
	}
	$after="_{$form}AfterReadItem"; if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
	$after="{$form}AfterReadItem";	if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
return $Item;
}

function jdbSaveItem($form,$Item=array(),$func=true) {
	if (!isset($Item["id"]) OR $Item["id"]=="" OR $Item["id"]=="_new") {
		$Item["id"]=newIdRnd();
	}
	$check=mysqlReadItem("jdb",$Item["id"]);
	if ($check==NULL) {mysqlInsertItem("jdb",$Item["id"]);}
	$Item["form"]=$form;
	$before="_{$form}BeforeSaveItem"; 	if (is_callable($before) && $func==true) {$Item=$before($Item);}
	$before="{$form}BeforeSaveItem";	if (is_callable($before) && $func==true) {$Item=$before($Item);}
	//$Item["project"]="";
	$JDB=array();
	$JDB["id"]=$Item["id"];
	$JDB["form"]=$form;
	//$JDB["project"]="";
	$JDB["json"]=json_encode($Item, JSON_HEX_QUOT | JSON_HEX_APOS);
	$JDB["json"]=$_SESSION["mysql"]->real_escape_string($JDB["json"]);
	$res=mysqlSaveItem("jdb",$JDB);
	$after="_{$form}AfterSaveItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	$after="{$form}AfterSaveItem"; if (is_callable ($after) && $func==true) { $Item =$after($Item) ; }
	return $res;
}

function mysqlReadDict($form) {
    $result = $_SESSION["mysql"]->query("SHOW COLUMNS FROM {$form} ;") or die("Query failed (mysqlReadDict): " . mysqli_error($_SESSION["mysql"]));
    while($col = $result->fetch_assoc()){
		$Dict[]=$col["Field"];
	}
    mysqli_free_result($result);
    return $Dict;
}

function mysqlListItems($form,$where=NULL,$engine=FALSE) {
	if (mysqlCheckTable($form)) {
		$func=$form."MysqlListItems";
		if (is_callable($func)) {
			$out['result']=$func();
		} else {
			if (!$where==NULL) {$where="WHERE {$where}";} else {$where="";}
			$SQL="select * from {$form} {$where} ;";
			$func=$form."BeforeGetListItems";
			if (is_callable($func)) {$SQL=$func($SQL);}
			$result = $_SESSION["mysql"]->query($SQL) or die("Query failed (mysqlListItems): " . mysqli_error($_SESSION["mysql"]));
		$array=array();
		while($data = $result->fetch_array(MYSQLI_ASSOC)) {
			$array[]=$data;
		}; unset($data);
			$after="_".$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($array);}
			$after=$form."AfterGetListItems"; if (is_callable($after)) {$array=$after($array);}
			$out['result']=$array;
		}

	} else {
		$out=jdbListItems($form,$where=NULL,$engine=FALSE);
	}
	if (!is_array($out["result"])) $out["result"]=array();
return $out;
}

function mysqlReadItem($form,$id,$func=true) {
	$_SESSION["error"]="";
	$Item=FALSE;
	$before="_{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }
	$before="{$form}BeforeReadItem";	if (is_callable ($before) && $func==true) { $Item =$before($Item) ; }

	if (mysqlCheckTable($form)) {
		$result = $_SESSION["mysql"]->query("SELECT * FROM {$form} WHERE id = '{$id}' LIMIT 1;");
		if ($result) { $Item=$result->fetch_array(MYSQLI_ASSOC); mysqli_free_result($result);} else {$_SESSION["error"]="noitem";}
		$jItem=jdbReadItem($form,$id);
		$Item=(array)$jItem+(array)$Item;
		$after="_".$form."AfterReadItem"; if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
		$after=$form."AfterReadItem";	if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
	} else {
		$Item=jdbReadItem($form,$id);
	}
	//$Item["firstImg"]=aikiGetItemImg($Item);
	return $Item;
}

function mysqlSaveItem($form,$Item,$func=true) {
	if (mysqlCheckTable($form)) {
		$before="_{$form}BeforeSaveItem"; if (is_callable ($before) && $func=true) { $Item=$before($Item);}
		$before="{$form}BeforeSaveItem"; if (is_callable ($before) && $func=true) { $Item=$before($Item);}
		$Dict=mysqlReadDict($form);
		$FLD="";
		$jItem=$Item;
		foreach ($Dict as $key => $fieldname) {
			 if (isset($Item[$fieldname]) AND $fieldname!="id") {
				 unset($jItem[$fieldname]);
				$Value = str_replace("'","&#039;",$Item[$fieldname]);
				$FLD .= "$fieldname = '$Value', ";
			 }
		}
		$FLD=substr($FLD,0,-2);
		if ($Item["id"]>"" AND $Item["id"]!="_new") {
				$SQL = "UPDATE {$form} SET {$FLD} WHERE id  = '{$Item["id"]}';";
		} else {
				$SQL = "INSERT HIGH_PRIORITY INTO {$form} SET {$FLD}";
		}
		$result = $_SESSION["mysql"]->query($SQL);
		if ($jItem!==array("id"=>$Item["id"]) && $form!=="jdb") {jdbSaveItem($form,$jItem);}
		$after="_{$form}AfterSaveItem"; if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
		$after="{$form}AfterSaveItem"; if (is_callable ($after) && $func=true) { $Item =$after($Item) ; }
	} else {
		$result=jdbSaveItem($form,$Item);
	}
	unset($Item,$result,$before,$after,$form);
return mysqli_error($_SESSION["mysql"]);
}

function mysqlInsertItem($form,$id="DEFAULT") {
$SQL = "INSERT HIGH_PRIORITY INTO {$form} SET id='{$id}' , form='' , project='' , json='' ;";
$result = $_SESSION["mysql"]->query($SQL);
$error=mysqli_error($_SESSION["mysql"]);
if ($error) {echo $error;} else {
   if ($id=="DEFAULT") {$id=mysqli_insert_id();}
   return $id;
}
}

function mysqlDeleteItem($form,$id) {
	$res=true;
	if (mysqlCheckTable($form)) {
		$SQL="DELETE QUICK FROM {$form} WHERE id = '{$id}' ;";
	}
	$result = $_SESSION["mysql"]->query($SQL) or die("Query failed (mysqlDeleteItem): " . mysqli_error($_SESSION["mysql"]));
	jdbDeleteItem($form,$id);
	return $res;
}

function jdbDeleteItem($form,$id) {
	$res=true;
	$SQL="DELETE QUICK FROM  jdb WHERE id = '{$id}' AND form = '{$form}';";
	$result = $_SESSION["mysql"]->query($SQL) or die("Query failed (mysqlDeleteItem): " . mysqli_error($_SESSION["mysql"]));
	return $res;
}

function newIdRnd($separator="") {
	$mt=explode(" ",microtime());
	$md=substr(str_repeat("0",2).dechex(ceil($mt[0]*10000)),-4);
	$id=dechex(time()+rand(100,999)).$separator.$md;
	$_SESSION["newIdLast"]=$id;
	return $id;
}

function formPathGet($form="page",$id="_new") {
	if (isset($_SESSION["project"]) && $_SESSION["projects"]=="true" && $_SESSION["project"]>"") {$prj="/projects/".$_SESSION["project"];} else {$prj="";}
	$savePath["contents"]="{$prj}/contents/";
	$savePath["form"]=$savePath["contents"].$form."/";
	$savePath["item"]=$savePath["form"].$id;
	$savePath["uploads"]="{$prj}/uploads/";
	$savePath["uplform"]=$savePath["uploads"].$form."/";
	$savePath["uplitem"]=$savePath["uplform"].$id."/";
	//$savePath["cache"]=$savePath["uplitem"]."_cache/";
return $savePath;
}

function formPathCheck($form="page",$id="_new",$uplflds=array("images")) {
	$savePath=formPathGet($form,$id);
	if (!isset($_SESSION["proj_path"])) {$_SESSION["proj_path"]="";}
	$base=$_SESSION["root_path"].$_SESSION["proj_path"];
	$dir=$base.$savePath["form"];
	if (!is_dir($dir)) { mkdir($dir);}
	$uplpath=$base.$savePath["uploads"];
	if (!is_dir($uplpath)) { mkdir($uplpath);}

	$dir=$base.$savePath["uplform"];
	if (!is_dir($dir)) { mkdir($dir);}
	if ($uplflds==true) {
			$dir=$base.$savePath["uplitem"];
			if (!is_dir($dir)) { mkdir($dir);}
	} else {
	foreach($uplflds as $key => $fld) {
		if ($_POST[$fld]>"") {
			$dir=$base.$savePath["uplitem"];
			if (!is_dir($dir)) { mkdir($dir);}
		}
	}
	}
	return $savePath;
}

function comSession() {
	$_SESSION["cache"]=0;
	$_SESSION["_new"]=newIdRnd();
	$_SESSION["app_path"]="{$_SERVER['DOCUMENT_ROOT']}";
	$_SESSION["root_path"]="{$_SERVER['DOCUMENT_ROOT']}";
	$_SESSION["HTTP_HOST"]=$_SERVER['HTTP_HOST'];
	$domain=explode(".",str_replace("www.","",strtolower($_SERVER["HTTP_HOST"])));
	if (count($domain)==3 AND $_SESSION["projects"]=="on") {
		if ($domain[0]!="www") {
			$_SESSION["project"]=$domain[0];
			$_SESSION["app_path"].="/projects/".$domain[0];
			if (!is_dir($_SESSION["app_path"])) {
				//if (!is_dir($_SESSION["app_path"])) {mkdir($_SESSION["app_path"]);}
			}
		}
	} else {
		$_SESSION["project"]="";
	}
	$_SESSION["prj_path"]=str_replace($_SESSION["root_path"],"",$_SESSION["app_path"]);
	if (!isset($_SESSION["User"])) {$_SESSION["User"]="User";}
	include_once("{$_SESSION["engine_path"]}/functions.php");
	if (!isset($_SESSION["order_id"])) {$_SESSION["order_id"]=get_order_id();}
	if (!is_file($_SESSION["app_path"]."/contents/dict/user_role")) {
		copy("{$_SESSION["engine_path"]}/uploads/__contents/dict/user_role",$_SESSION["app_path"]."/contents/dict/user_role");
	}
}

function comAdminMenu($__page) {
	if ($__page->find("ul.formlist")->length) {
		$forms=array();
		if (isset($_SESSION["settings"]["forms"])) {$forms=$_SESSION["settings"]["forms"];}
		if (count($forms)==0 OR !is_array($forms) OR (count($forms)==1 AND $forms[0]["name"]=="")) {
			$f=array("name","descr","allow","disallow");
			$v=array(
			array("page","Страницы","",""),
			array("prod","Продукция","",""),
			array("orders","Заказы","",""),
			array("news","Новости","",""),
			array("comments","Отзывы","",""),
			array("users","Пользователи","",""),
			array("dict","Справочники","",""),
			array("tree","Каталоги","","")
			);
			foreach($v as $k => $vv) {
				foreach($vv as $i => $val) {
					$forms[$k][$f[$i]]=$val;
				}
			}
		}
		foreach($forms as $form) {
			comAdminMenuAdd($__page,$form["name"],$form["descr"],$form["allow"],$form["disallow"]);
		}
	}
}

function comAdminMenuAdd($__page,$form,$name,$allow="",$disallow="") {
	if ($allow>"") {$allow=' data-allow="'.$allow.'" ';}
	if ($disallow>"") {$disallow=' data-disallow="'.$disallow.'" ';}
	$__page->find("ul.formlist")->append('<li '.$allow.' '.$disallow.' ><a href="#" data-ajax="mode=list&form='.$form.'" data-html="div.main">'.$name.'
				<span class="pull-right glyphicon glyphicon-plus-sign add-item" data-ajax="mode=edit&form='.$form.'&id=_new" data-toggle="modal" data-target="#'.$form.'Edit" data-html="#'.$form.'Edit .modal-body">
				</span></a></li>');
	if (is_object($__page->find("ul.formlist",0))) {$__page->find("ul.formlist",0)->contentCheckAllow();}
}

function comPathCheck() {
	if (!is_dir($_SESSION["root_path"]."/projects")) {mkdir($_SESSION["root_path"]."/projects");}
if (is_dir($_SESSION["app_path"])) {
	if (!is_dir($_SESSION["app_path"]."/contents")) {mkdir($_SESSION["app_path"]."/contents");}
	if (!is_dir($_SESSION["app_path"]."/uploads")) {mkdir($_SESSION["app_path"]."/uploads");}
	if (!is_dir($_SESSION["app_path"]."/forms")) {mkdir($_SESSION["app_path"]."/forms");}
	if (!is_dir($_SESSION["app_path"]."/tpl")) {mkdir($_SESSION["app_path"]."/tpl");}
}
}


function data_json_decode($data) {

// тут всё нормально массив преобразуется как надо
// теперь нужно разобраться со вставкой значений (многозначных полей)

	if (!is_array($data)) {
		if (substr($data,0,1)=="{" OR substr($data,0,1)=="[") {$data=json_decode($data,TRUE);}
		//$data=ItemStripSlashes($data);
	} else {
		$data=ItemStripSlashes(json_decode($data[0],TRUE));
	}
	return $data;
}

function clearValueTags($out) {
	$out=preg_replace("|\{\{([^\}]+?)\}\}|","",$out);
	return $out;
}

function aikiDatePickerOconv($out) {
	foreach($out->find("[type^=date]") as $dp) {
		if ($dp->attr("value")>"") {
			if ($dp->attr("type")=="datepicker") {$data_format="d.m.Y";}
			if ($dp->attr("type")=="datetimepicker") {$data_format="d.m.Y H:i";}
			if ($dp->attr("type")=="date") {$data_format="Y-m-d";}
			if ($dp->attr("data-oconv")>"") {$data_format=$dp->attr("data-oconv");}
			$dp->attr("value",date($data_format,strtotime($dp->attr("value"))));
		}
	}
}

function aikiCheckoutForms() {
	$res=array();
	$dir=$_SESSION["app_path"]."/forms";
	exec("ls {$dir} -R --ignore'=*_*.php' -D -1 ",$list);
	foreach($list as $val) {
		if (substr($val,-1)==":") {$dir=substr($val,0,-1);}
		$file="{$dir}/{$val}";
		if (is_file($file)) {
			$php=strtolower(trim(file_get_contents($file)));
			$form=explode(".php",$val); $form=$form[0];
			if ( strpos($php,"function {$form}_checkout") AND strpos($php,"function {$form}_success") ) {
				$arr=array();
				$arr["name"]=$form;
				$arr["dir"]=$dir;
				$res[]=$arr;
			}
		}
	}
	unset($dir,$list,$val,$form,$php,$file,$arr);
	return $res;
}


function formCurrentInclude($form) {
	$current="";
	$inc=array(
		"{$_SESSION["engine_path"]}/forms/{$form}.php", "{$_SESSION["engine_path"]}/forms/{$form}/{$form}.php",
		"{$_SESSION["root_path"]}/forms/{$form}.php", "{$_SESSION["root_path"]}/forms/{$form}/{$form}.php",
		"{$_SESSION["app_path"]}/forms/{$form}.php", "{$_SESSION["app_path"]}/forms/{$form}/{$form}.php",
		"{$_SESSION["engine_path"]}/forms/common/common.php"
	);
	$res=FALSE;
	foreach($inc as $k => $file) {
		if (is_file("{$file}") && $res==FALSE ) {
			include_once("{$file}");
			if ($k>1) {$res=TRUE;} // чтобы engine обязательно был включен
			$current=$file;
		}
	}
	return $current;
}

function ItemStripSlashes($arr) {
	if (is_array($arr)) {
		foreach($arr as $key => $val) {
			if (is_array($val)) {$val=ItemStripSlashes($val);} else {
				$arr[$key]=stripcslashes($val);
				$arr[$key]=str_replace("&quot;",'"',$arr[$key]);
			}
		}
	} else {
		$arr=stripcslashes($arr);
		$arr=str_replace("&quot;",'"',$arr);
	}
	return $arr;
}


function filesList($path="/content") {
$result=array();
$ll=DIRECTORY_SEPARATOR;
$dir=$_SESSION["app_path"].$path;
if (is_dir($dir)) { if ($dh = opendir($dir)) {
while (($file = readdir($dh)) !== false) {
		$cont['name']=$file;
		$cont['size']=filesize($dir.$ll.$file);
		$cont['type']=filetype($dir.$ll.$file);

		if (($cont['type']=="dir" OR $cont['type']=="file") AND $file!=".." AND $file!=".") {
			$result[]=$cont;
		}
}
closedir($dh);
}}
return $result;
}

function DeleteFile($filename) {
	$error=0;
	$filename=$_SERVER['DOCUMENT_ROOT'].$filename;
	if (!is_file($filename)) { $error="File not found";} else {
		unlink($filename); }
	if (is_file($filename)) {$error="Can't delete file";}
	return $error;
}

function DeleteDir($dir) {
	$dirname=$_SERVER['DOCUMENT_ROOT'].$dir;
     if (is_dir($dirname))
           $dir_handle = opendir($dirname);
	 if (!$dir_handle)
	      return "No dirname";
	 while($file = readdir($dir_handle)) {
	       if ($file !== "." && $file !== "..") {
			   $path=str_replace("//","/",$dirname."/".$file);
	            if (!is_dir($path)) {
					unlink($path);
	            } else {
	                rmdir($path);
	            }
	       }
	 }
	 closedir($dir_handle);
	 rmdir($dirname);
	 return 0;
}

function sitemapGeneration() {
	$host="http://{$_SESSION['HTTP_HOST']}";
	$sitemap=ki::fromString('
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>'.$host.'/</loc>
		<lastmod>'.date("Y-m-d").'</lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
</urlset>
	');
	$forms=array("page","news");
	foreach($forms as $form) {
		$list=aikiListItems($form);
		sitemapGenerationUrl($sitemap,$form,$list);
	}; unset($form);

	fileSaveItem("{$_SESSION['prj_path']}/sitemap.xml",$sitemap,true);
	return true;
}

function sitemapGenerationUrl($sitemap,$form,$list) {
	$list=$list["result"];
	foreach($list as $item) {
		$flag=true;
		if ($form=="page" AND $item["template"]=="") {$flag=false;}
		if ($form=="page") {$path="/";} else {$path="/".$form."/";}
		if ($flag==true) {
			$url='
<url>
	<loc>http://'.$_SESSION['HTTP_HOST'].$path.$item["id"].'.htm</loc>
	<changefreq>weekly</changefreq>
</url>';
			$sitemap->find("urlset")->append($url);
			if ($item["images"]>"" AND $item["id"]>"" AND $item["form"]>"") {
				if (!is_array($item["images"])) {$item["images"]=json_decode($item["images"],true);}
				foreach($item["images"] as $img) {
					$file="{$_SERVER['DOCUMENT_ROOT']}/{$_SESSION['prj_path']}uploads/{$item['form']}/{$item['id']}/{$img['img']}";
					$image="http://{$_SESSION['HTTP_HOST']}/uploads/{$item['form']}/{$item['id']}/{$img['img']}";
					if (($img["visible"]==1 OR $img["visible"]=="on") AND is_file($file) ) {
						$url='
	<url>
		<loc>'.$image.'</loc>
		<changefreq>never</changefreq>
	</url>';
						$sitemap->find("urlset")->append($url);
					}
				}
			}
		}
	}
}

function array_sort($array, $key=0, $order=SORT_ASC) {
	if (!is_array($array)) {$array=array($array);}
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
		if (isset($va[$key])) $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    if ($order==SORT_DESC) {$ret=array_reverse($ret);}
    return $ret;
}

function array_sort_multi( $array=array(), $args = array('votes' => 'd') ){
	// если передан атрибут, то предварительно готовим массив параметров
	if (is_string($args) && $args>"") {
			$args=attrToArray($args);
			$param=array();
			foreach($args as $ds) {
				$tmp=explode(":",$ds);
				if (!isset($tmp[1])) {$tmp[1]="a";}
				$param[$tmp[0]]=$tmp[1];
			}
			$args=$param;
			unset($param,$tmp,$ds);
	}
	// сортировка массива по нескольким полям
	usort( $array, function( $a, $b ) use ( $args ){
		$res = 0;
		$a = (object) $a;
		$b = (object) $b;
		foreach( $args as $k => $v ){
			if (isset($a->$k) && isset($b->$k)) {
				if( $a->$k == $b->$k ) continue;

				$res = ( $a->$k < $b->$k ) ? -1 : 1;
				if( $v=='d' ) $res= -$res;
				break;
			}
		}
		return $res;
	} );
	return $array;
}

function attrToArray($attr) {
	$attr=str_replace(","," ",$attr);
	$attr=str_replace(";"," ",$attr);
	return explode(" ",trim($attr));
}

function array_filter_value ($array, $index, $value1, $value2=NULL){
	$newarr=array();
	if (is_array($array) && count($array)>0)  {
		foreach(array_keys($array) as $key) {
			if ($value2==NULL) {
				if (isset($array[$key][$index]) && $array[$key][$index]==$value1) {$newarr[]=$array[$key];}
			} else {
				if (isset($array[$key][$index]) && $array[$key][$index]>=$value1 AND $array[$key][$index]<=$value2) {$newarr[]=$array[$key];}
			}
		}
	}
	return $newarr;
}

function getWords($str,$w) {
	$res="";
	$arr=explode(" ",trim($str));
	for ($i=0; $i<=$w; $i++) {
		if (isset($arr[$i])) $res=$res." ".$arr[$i];
	}
	if (count($arr)>$w) {$res=$res."...";}
	$res=trim($res);
	return $res;
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else { 
				copy($src . '/' . $file,$dst . '/' . $file); 
				chmod($dst.'/'.$file,0766);
			}
        }
    }
    closedir($dir);
}

function normalizePath( $path ) {
    $patterns = array('~/{2,}~', '~/(\./)+~', '~([^/\.]+/(?R)*\.{2,}/)~', '~\.\./~');
    $replacements = array('/', '/', '', '');
    return preg_replace($patterns, $replacements, $path);
}
?>
