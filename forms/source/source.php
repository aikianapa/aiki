<?php
if ($_GET["mode"]=="ajax") {echo source__ajax();}

function source__list() {
$out=aikiGetForm();
$Item=array();
$dirlist = getDirectoryTree($_SESSION["app_path"],'',array("engine","contents"));
$Item["dirTree"]="<div  class='sourceTree' role='menu'>".array2html($dirlist)."</div>\n";
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
