<form id="pageEditForm" name="page" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#pageDescr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#pageText" class="call-editor" data-toggle="tab" >Контент</a></li>
	<li><a href="#pageSource" class="call-source" data-toggle="tab" >Исходный код</a></li>
	<li><a href="#pageImages" class="call-imgloader" data-toggle="tab">Изображения</a></li>
</ul>

<div class="tab-content">
<br />
<div id="pageDescr" class="tab-pane active">
	<div class="form-group">
	  <label class="col-sm-2 control-label">Имя записи</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя записи" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Заголовок</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="Заголовок"></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Подвал</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="footer" placeholder="Подвал"></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Шаблон</label>
	   <div class="col-sm-10">
		   <select class="form-control" name="template" placeholder="Шаблон" data-role="foreach" from="tpllist">
				<option value="{{0}}">{{0}}</option>
		   </select>
		</div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-description</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="meta_description" placeholder="Описание"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-keywords</label>
	   <div class="col-sm-10"><input type="text" class="form-control input-tags" name="meta_keywords" placeholder="Ключевые слова"></div>
	</div>

</div>

<div id="pageText" class="tab-pane">
<textarea name="text" id="text" class="editor" placeholder="Контент" ></textarea>
</div>
<div id="pageSource" class="tab-pane">
	<div class="panel panel-default">
		<div id="sourceEditorToolbar" class="panel-heading">
			<div class="btn-group">
				<button class="btn btn-sm btn-default btnCopy"><i class="fa fa-files-o"></i></button>
				<button class="btn btn-sm btn-default btnPaste"><i class="fa fa-clipboard"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-sm btn-default btnUndo"><i class="gi gi-undo"></i></button>
				<button class="btn btn-sm btn-default btnRedo"><i class="gi gi-redo"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-sm btn-default btnFind"><i class="fa fa-search"></i></button>
				<button class="btn btn-sm btn-default btnReplace"><i class="gi gi-translate"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-sm btn-default btnFontDn"><i class="gi gi-text_smaller"></i></button>
				<button class="btn btn-sm btn-default btnFontUp"><i class="gi gi-text_bigger"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-sm btn-default btnLight"><i class="fa fa-sticky-note-o"></i></button>
				<button class="btn btn-sm btn-default btnDark"><i class="fa fa-sticky-note"></i></button>
			</div>
			<button class="btn btn-sm btn-default btnFullScr"><i class="fa fa-arrows-alt "></i></button>
			<button class="btn btn-sm btn-default btnSave"><i class="fa fa-save "></i></button>
		</div>
		<textarea id="sourceEditor"></textarea>
	</div>
</div>
<div id="pageImages" class="tab-pane" data-role="imageloader" data-ext="jpg png gif zip pdf doc"></div>
</div>
</form>
<style type="text/css" media="screen">
    .sourceModal.fullscr, .sourceModal.fullscr .modal-dialog {width:100% !important; padding:0 !important; margin:0 !important;}
    .sourceModal.fullscr .modal-body {padding:0;}
    .sourceModal.fullscr .modal-header, .sourceModal.fullscr .modal-footer {display:none;}
    .sourceModal.fullscr .nav {display:none;}
    #sourceEditor {position: relative;}
</style>
<script language="javascript" src="/engine/js/ace/ace.js"></script>
<script language="javascript">
	$("#pageEdit").addClass("sourceModal");
	$(document).data("sourceFile",null);
	var theme=getcookie("sourceEditorTheme");
	var fsize=getcookie("sourceEditorFsize")*1;
	if (theme==undefined || theme=="") {var theme="ace/theme/chrome"; 	setcookie("sourceEditorTheme",theme);}
	if (fsize==undefined || fsize=="") {var fsize=12; 					setcookie("sourceEditorFsize",fsize);}
	if ($(document).data("sourceClipboard")==undefined) {$(document).data("sourceClipboard","");}

	
    editor=aikiCallSourceEditor();
	editor.setTheme(theme);
	editor.setFontSize(fsize);
	editor.setValue($("#text").val());
	editor.gotoLine(0,0);


	$("#pageEditForm [data-toggle=tab],#pageEdit [data-formsave]").click(function(){
		if ($("#pageEditForm .call-source").parent("li").hasClass("active")) {$("#text").val(editor.getValue());} else {
				var ace_height=$("#cke_text .cke_contents").height();
				if (ace_height==undefined || ace_height<500) {ace_height=500;}
				$(".ace_editor").css("height",ace_height);
				editor.getSession().setMode("ace/mode/php");
				editor.setValue($("#text").val());
				editor.gotoLine(0,0);
				editor.resize(true);
		}

	});



</script>
