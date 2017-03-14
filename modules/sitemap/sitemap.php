<?php
function sitemap_init() {
	comSession();
	$out=aikiFromFile(__DIR__."/sitemap_show.php");
	echo $out->outerHtml();
//	echo sitemap_generate();
}

function sitemap_ajax() {
	comSession();
	if (isset($_POST["link"])) {
		if ($_POST["link"]=="__finish__") {
			sitemap_generate_finish();
		} else {
			echo sitemap_generate($_POST["link"]);
		}
	} else {
		echo sitemap_generate();
	}
	
}

function sitemap_check_show($form) {
	$formfile="{$_SESSION["app_path"]}{$form["dir"]}/{$form["name"]}.php";
	
	if (is_file($formfile)) {$formfile=file_get_contents($formfile);}
	if (strpos($formfile,"{$form["form"]}_show") OR strpos($formfile,"{$form["form"]}__show")) {$func=true;} else {$func=false;}

		if (($form["mode"]=="show" OR $func==true) && !in_array($form["form"],$forms)) {
			return true;
		}	
		return false;
}

function sitemap_generate($href=null) {
	if (!isset($_SESSION["moduleSitemap"]) OR $href==null) {
		$_SESSION["moduleSitemap"]=array("ready"=>array(""),"sitemap"=>"","level"=>0);
		$sitemap='';
		file_put_contents("{$_SESSION['app_path']}/sitemap.xml",$sitemap);
	}
	if ($href==null) {$href="/";}
		$l=parse_url($href);
		if (!isset($l["host"]) OR $l["host"]==$_SERVER["HTTP_HOST"]) {
			if (!isset($l["host"])) {$link="{$_SESSION["HOST"]}{$l["path"]}";$l["host"]=$_SESSION["HTTP_HOST"];} else {$link=$href;}

			if (!in_array($link,$_SESSION["moduleSitemap"]["ready"]) && $l["host"]==$_SESSION["HTTP_HOST"] && $l["path"]!=="void(0)") {
				$count++;
				sitemap_node($link);
				$content=file_get_contents($link);
				if ($content) {
					$out=aikiFromString($content);
					$oLinks=$out->find("a[href]");
					foreach($oLinks as $oLink) {
						$href=$oLink->attr("href");
						$l=parse_url($href);
						if (!isset($l["host"])) {$link="{$_SESSION["HOST"]}{$l["path"]}";$l["host"]=$_SESSION["HTTP_HOST"];} else {$link=$href;}
						if (!in_array($link,$_SESSION["moduleSitemap"]["ready"]) && $l["host"]==$_SESSION["HTTP_HOST"] && $l["path"]!=="void(0)") {
							$list[]=$link;
						}
					}
				}
			}
		}

	//echo $out->outerHtml();
	if (count($list)==0) {

		return json_encode(false);
		
	} else {
		return json_encode($list);
	}
}

function sitemap_generate_finish() {
$sitemap='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
'.$_SESSION["moduleSitemap"]["sitemap"].'
</urlset>';
file_put_contents("{$_SESSION['app_path']}/sitemap.xml",$sitemap);
unset($_SESSION["moduleSitemap"]);
return json_encode(false);
}


function sitemap_node($link) {
$date=date("Y-m-d H:i:s");
$node='<url>
	<loc>'.$link.'</loc>
	<lastmod>'.$date.'</lastmod>
	<changefreq>daily</changefreq>
</url>
';
$_SESSION["moduleSitemap"]["sitemap"].=$node;
$_SESSION["moduleSitemap"]["ready"][]=$link;
}

?>
