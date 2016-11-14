<?php
if ($_GET["mode"]=="ajax") {echo source__ajax();}

function source__list() {
$out=aikiGetForm();
$Item=array();
//$dirlist = getDirectoryTree($_SESSION["app_path"],'',array("engine","contents"));
$Item["dirTree"]="<div  class='sourceTree' role='menu'></div>\n";
$out->contentSetData($Item);
return $out->outerHtml();
}

function source__ajax() {
	session_start();
	if ($_SESSION["user_role"]=="admin") {
		$result=false;
		switch($_GET["action"]) {
			case "getfile":
				$result=file_get_contents($_SESSION["app_path"].$_GET["file"]);
				break;
			case "setfile":
				$result=file_put_contents($_SESSION["app_path"].$_POST["file"],$_POST["value"]);
				break;
			case "getdir":
				if (!isset($_GET["dir"])) {$dir="";} else {$dir=$_GET["dir"];}
				$result = json_encode(getDirectoryJson($_SESSION["app_path"].$dir,'',array("engine")));
				break;
			default:
				break;
		}
	}
	return $result;
}


function array2html($dirlist,$path="/") {
	$html="<ul>"; 
	if ($path=="/") {$html.="<li class='isFolder {$path}'><a href='{$path}' target=''>.</a></li>";} 
	foreach($dirlist as $key => $item) {
		if (is_string($item)) $html.="<li class='isFile {$path}'><a href='{$path}{$key}' target='{$path}'>{$key}</a></li>";
		if (is_array($item)) {
			$cpath="{$path}{$key}/";
			$html.="<li class='isFolder {$path}'><a href='{$cpath}' target='{$path}'>{$key}</a>".array2html($item,$cpath)."</li>";
		}
	}
	$html.="</ul>";
	return $html;
}

function getDirectoryJson( $outerDir , $x, $exclude=array()){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
    $dir_array = Array();
    $i=0;
    foreach( $dirs as $d ){
        if (!in_array($d,$exclude)) {
			$dir=substr($outerDir,strlen($_SESSION["app_path"]));
			
			if( is_dir($outerDir."/".$d)  ){
				$dir_array[ $i ]["id"]=null;
				$dir_array[ $i ]["isFolder"]=true;
				$dir_array[ $i ]["isActive"]=false;
				$dir_array[ $i ]["isExpanded"]=false;
				$dir_array[ $i ]["isLazy"]=true;
				$dir_array[ $i ]["text"]=$d;
				$dir_array[ $i ]["href"]=$dir."/".$d."/";
				$dir_array[ $i ]["hrefTarget"]=$dir;
				//$dir_array[ $i ]["children"] = getDirectoryJson( $outerDir."/".$d , $x);
			} else {
				preg_match("/{$x}/",$d,$tmp);
				if (isset($tmp[0])) {
					$dir_array[ $i ]["id"]=null;
					$dir_array[ $i ]["isActive"]=false;
					$dir_array[ $i ]["isFolder"]=false;
					$dir_array[ $i ]["href"]=$dir."/".$d;
					$dir_array[ $i ]["hrefTarget"]=$dir;
					$dir_array[ $i ]["text"]=$d;					
					//$dir_array[ $d ] = $d;
				}
			}
			$i++;
        }
    }
    return $dir_array;
}

function getDirectoryTree( $outerDir , $x, $exclude=array()){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
    $dir_array = Array();
    foreach( $dirs as $d ){
        if (!in_array($d,$exclude)) {
			if( is_dir($outerDir."/".$d)  ){
				$dir_array[ $d ] = getDirectoryTree( $outerDir."/".$d , $x);
			} else {
				preg_match("/{$x}/",$d,$tmp);
				if (isset($tmp[0])) $dir_array[ $d ] = $d;
			}
        }
    }
    return $dir_array;
}
?>
