<?php
ini_set('display_errors', 0	);
session_start();
$_SESSION["app_path"]=$_SERVER["DOCUMENT_ROOT"];
$_SESSION["engine_path"]="{$_SERVER['DOCUMENT_ROOT']}/engine";
include_once(__DIR__ ."/functions.php");

if (is_file("{$_SERVER['DOCUMENT_ROOT']}/contents/admin/settings")) {
	header("Refresh: 0; URL=http://{$_SERVER["HTTP_HOST"]}/login.htm");
	die;
}

comPathCheck();
copy("{$_SESSION["engine_path"]}/.htaccess","{$_SERVER['DOCUMENT_ROOT']}/.htaccess");
copy("{$_SESSION["engine_path"]}/tpl/default.php","{$_SERVER['DOCUMENT_ROOT']}/tpl/default.php");
copy("{$_SESSION["engine_path"]}/uploads/__system/index.php","{$_SERVER['DOCUMENT_ROOT']}/index.php");

chmod("{$_SERVER['DOCUMENT_ROOT']}/.htaccess",0766);
chmod("{$_SERVER['DOCUMENT_ROOT']}/tpl/default.php",0766);
chmod("{$_SERVER['DOCUMENT_ROOT']}/index.php",0766);

recurse_copy("{$_SESSION["engine_path"]}/uploads/__contents","{$_SERVER['DOCUMENT_ROOT']}/contents");

$__page=ki::fromFile("{$_SESSION["engine_path"]}/tpl/admin.php");
$form=aikiGetForm("admin","settings");
foreach($form->find(".form-group") as $fg) {
	if (!$fg->hasClass("setup")) {$fg->remove();}
}
$form->find("[data-role=multiinput]")->remove();
$form->find("ul.nav li:not(.active)")->remove();
$__page->find("script[src=/engine/tpl/js/admin.js])")->remove();
$__page->find("head title")->html("Настройки");
$__page->find("head")->prepend("<script src='/engine/js/jquery.min.js'></script>");
$__page->find("head")->append("<link rel='stylesheet' href='/engine/appUI/css/bootstrap.min.css'>");
$__page->find("head")->prepend("<script src='/engine/js/jquery.min.js'></script>");
$__page->find("head")->append("<script src='/engine/bootstrap/js/bootstrap.min.js'></script>");
$__page->find("head")->append("<script src='/engine/js/functions.js'></script>");
$__page->find("head")->append("<script src='/engine/appUI/js/plugins.js'></script>");
$__page->find("body")->html("<div class='col-sm-2'></div><div id='engine__setup' class='col-sm-8'>{$form}</div><div class='col-sm-2'></div>");
$__page->find("body")->prepend("<div class='jumbotron col-sm-12'><div class='col-sm-2'></div><div class='col-sm-8'><h1>AiKi :: 合気</h1></div><div class='col-sm-2'></div></div>");
$__page->find("#adminCommon")->append("<p class='alert alert-info'>Поддержка и вопросы: <a href='https://vk.com/aikiengine' target='_blank'>https://vk.com/aikiengine</a></p>");
$__page->find("button")->appendTo("#adminCommon");

$__page->find("#admin_btn_update")->remove();

if (!is_file("{$_SERVER['DOCUMENT_ROOT']}/tpl/default.php")) {
	$error="
	<div class='alert alert-danger'>
		<b>Ошибка!</b><br>
		<p>Невозможно создать папки и файлы в рабочей дирректории: <i>{$_SESSION["app_path"]}</i></p>
		<p>Пожалуйста, проверьте права доступа...</p>
	</div>
	";
	
	
	$__page->find("#engine__setup")->html($error);
}






echo $__page;
?>
<script>
$(document).ready(function(){
	$(document).on("admin_after_formsave",function(){
		document.location.href="/login.htm";
	});
});
</script>
