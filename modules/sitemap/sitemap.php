<?php
function sitemap_init() {
	$out=aikiFromFile(__DIR__."/sitemap_show.php");
	echo $out->outerHtml();
//	echo sitemap_generate();
}

function sitemap_ajax() {
	set_time_limit(0);
	echo sitemap_generate();
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
			if (!isset($l["host"])) {$link="{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["HTTP_HOST"]}{$l["path"]}";} else {$link=$href;}
			if (!in_array($link,$_SESSION["moduleSitemap"]["ready"])) {
				$count++;
				sitemap_node($link,$out);
				$_SESSION["moduleSitemap"]["ready"][]=$link;
				$content=file_get_contents($link);
				if ($content) {
					$out=aikiFromString($content);
					$oLinks=$out->find("a[href]");
					foreach($oLinks as $oLink) {
						$href=$oLink->attr("href");
						$l=parse_url($href);
						if (!isset($l["host"])) {$link="{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["HTTP_HOST"]}{$l["path"]}";} else {$link=$href;}
						if (!in_array($link,$_SESSION["moduleSitemap"]["ready"])) {
							$_SESSION["moduleSitemap"]["level"]++;
							sitemap_generate($link);
							$_SESSION["moduleSitemap"]["level"]--;
						}
					}
				}
			}
		}

	//echo $out->outerHtml();
	if ($_SESSION["moduleSitemap"]["level"]==0) {

		if (count($_SESSION["moduleSitemap"]["ready"])>2000 OR $_SESSION["moduleSitemap"]["level"]==0) {
			$sitemap='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
'.file_get_contents("{$_SESSION['app_path']}/sitemap.xml").'
</urlset>';
			file_put_contents("{$_SESSION['app_path']}/sitemap.xml",$sitemap);
			unset($_SESSION["moduleSitemap"]);
			die("Готово");
			die;
		}	
	}
}


function sitemap_node($link,$out) {
$date=date("Y-m-d H:i:s");
$node='<url>
	<loc>'.$link.'</loc>
	<lastmod>'.$date.'</lastmod>
	<changefreq>dayly</changefreq>
</url>
';
file_put_contents("{$_SESSION['app_path']}/sitemap.xml",$node,FILE_APPEND);
}

?>
