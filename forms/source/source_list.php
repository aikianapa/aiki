<div class="content" data-allow="admin" id="sourceList">
	<div class="col-xs-12 sourcePanels">
		<div class="col-xs-6">
			<div class="panel panel-default l active">
				{{dirTree}}
			</div>
		</div>
		
		<div class="col-xs-6">
			<div class="panel panel-default r">
				{{dirTree}}
			</div>
		</div>
		
		<div class="col-xs-12 sourceButtons">
			<a class="btn btn-info btn-sm btnDir 	ePanel eDir eFile"	><i class="fa fa-folder-o"></i><span class="hidden-xs"> Создать каталог</span></a>
			<a class="btn btn-info btn-sm btnFile 	ePanel eDir eFile"	><i class="fa fa-file-o"></i><span class="hidden-xs"> Создать файл</span></a> 
			<a class="btn btn-info btn-sm btnLink 	ePanel eDir eFile"	><i class="fa fa-link"></i><span class="hidden-xs"> Создать ссылку</span></a> 
			<a class="btn btn-info btn-sm btnRen 	eDir eFile"			><i class="fa fa-retweet"></i><span class="hidden-xs"> Переименовать</span></a> 
			<a class="btn btn-info btn-sm btnEdit 	eFile"				><i class="fa fa-edit"></i><span class="hidden-xs"> Редактировать</span></a> 
			<a class="btn btn-info btn-sm btnView 	eFile"				><i class="fa fa-search-plus"></i><span class="hidden-xs"> Просмотреть</span></a>
			<a class="btn btn-info btn-sm btnCopy	eDir eFile"			><i class="fa fa-clone"></i><span class="hidden-xs"> Копировать</span></a> 
			<a class="btn btn-info btn-sm btnDel	eDir eFile"			><i class="fa fa-trash-o"></i><span class="hidden-xs"> Удалить</span></a> 
		</div>
		<div data-role="include" src="modal" data-id="sourcePanelAction" data-formsave="false" data-hide="*"></div>
		
	</div>
	</div>
	<div data-role="include" src="modal" data-id="sourceModal" data-hide="*">
		<div append="#sourceModal .modal-body" class="panel panel-default">
			<div id="sourceEditorToolbar">
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
			<ul class="nav nav-tabs">
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tabSource">
					<textarea id="SourceEditor"></textarea>
				</div>
			</div>
		</div>
	</div>
	
	<div id="sourceActions" style="display:none;">
		<div class="btnDir" title="Создание каталога">
			<form id="sourceActionDir">
				<label>Путь к каталогу:</label>
				<input type="text" class="form-control iPath" readonly style="width:100%;">
				<label>Имя каталога:</label>
				<input type="text" class="form-control iName" style="width:100%;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
				<button class="btn btn-success btnDir"><i class="fa fa-check"></i> Создать</button>
			</form>
		</div>
		
		<div class="btnDel" title="Действительно удалить?">
			<form id="sourceActionDel">
				<p type="text" class="iName" style="width:100%;"></p>
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
				<button class="btn btn-warning btnDel"><i class="fa fa-trash"></i> Удалить</button>
			</form>
		</div>

		<div class="btnFile" title="Создание файла">
			<form id="sourceActionFile">
				<label>Путь к файлу:</label>
				<input type="text" class="form-control iPath" readonly style="width:100%;">
				<label>Имя файла:</label>
				<input type="text" class="form-control iName" style="width:100%;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
				<button class="btn btn-success btnFile"><i class="fa fa-check"></i> Создать</button>
			</form>
		</div>

		<div class="btnRen" title="Переименование">
			<form id="sourceActionRen">
				<label>Путь:</label>
				<input type="text" class="form-control iPath" readonly style="width:100%;">
				<input type="text" class="hidden iOld" style="width:100%;">
				<label>Новое имя:</label>
				<input type="text" class="form-control iName" style="width:100%;">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
				<button class="btn btn-success btnRen"><i class="fa fa-check"></i> Переименовать</button>
			</form>
		</div>
		
		<div class="btnCopy" title="Копирование">
			<form id="sourceActionCopy" class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-2 control-label">Исходик:</label>
					<div class="col-sm-10"><input type="text" class="form-control iPath" readonly></div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Копия:</label>
					<div class="col-sm-10"><input type="text" class="form-control iName"></div>
				</div>
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
				<button class="btn btn-success btnCopy"><i class="fa fa-check"></i> Копировать</button>
				<button class="btn btn-warning btnMove"><i class="fa fa-check"></i> Переместить</button>
			</form>
		</div>
		
	</div>
</div>
<style type="text/css" media="screen">
    .sourceModal.fullscr, .sourceModal.fullscr .modal-dialog {width:100% !important; padding:0 !important; margin:0 !important;}
    .sourceModal.fullscr .modal-header {display:none;}
    #sourceEditor {position: relative;}
    .sourceTree {overflow-x:auto; margin-right:10px;}
    .sourceTree .easytree-exp-c > .fa-folder-o::before {content:"\f114";}
    .sourceTree .easytree-exp-cl > .fa-folder-o::before {content:"\f114";}
    .sourceTree .easytree-exp-e > .fa-folder-o::before {content:"\f115";}
    #sourceModal .panel {border-radius:0; border:0;}
    #sourceModal .nav-tabs li a {padding: 5px 10px;}
    #sourceModal .modal-body {padding:5px;}
	#sourceList .sourcePanels {padding:0;}
    #sourceList .sourcePanels .panel {overflow:auto;}
    #sourceList .sourcePanels > div {padding:5px;} 
	#sourceList .sourcePanels .panel.active		  li.active > .easytree-node {border:1px #26A0DA solid; background-color:#CBE8F6;}
    #sourceList .sourcePanels .panel:not(.active) li.active > .easytree-node {border:1px transparent solid; background-color:#CBE8F6;}
    #sourceList .sourcePanels .panel.active {box-shadow: 1px 1px 2px #999; border-color:#999;}
    #sourcePanelAction .alert-danger .modal-header, #sourcePanelAction .alert-danger .modal-footer {background:transparent;}
    #sourcePanelAction .alert-danger .modal-body {padding-top:5px; padding-bottom:5px;}
</style>

<script language="javascript" src="/engine/js/ace/ace.js"></script>
<script data-allow="admin">
// Tree plugin, for more examples you can check out http://www.easyjstree.com/

$(document).ready(function(){
	$("#sourceModal").addClass("sourceModal");
	$("#sourceModal").noSelect();
	$("#sourceList .sourceTree :not(li span)").noSelect();
	
	$(".sourceTree").removeClass("dropdown-menu");
	$("#sourceModal").attr("data-backdrop","static");
	$("#sourceModal .modal-dialog").addClass("modal-lg");
	$("#sourceModal .modal-footer").remove();
	$("#sourceList .sourcePanels .l .sourceTree").attr("id","leftTree");
	$("#sourceList .sourcePanels .r .sourceTree").attr("id","rightTree");
	
	var leftNodes, rightNodes, leftTree, rightTree;
	var defr=sourceGetDir('');
	defr.done(function(d){
		leftTree=$("#sourceList #leftTree").easytree({
				ordering: 'orderedFolder',
				enableDnd: true,
				dropped: sourceDrop,
				openLazyNode: sourceLazyNode,
				opened: sourceOpenedNode,
				data: $("#sourceList").data("dirlist")
				//disableIcons:true
		});

		rightTree=$("#sourceList #rightTree").easytree({
				ordering: 'orderedFolder',
				enableDnd: true,
				dropped: sourceDrop,
				openLazyNode: sourceLazyNode,
				opened: sourceOpenedNode,
				data: $("#sourceList").data("dirlist")
				//disableIcons:true
		});
		$("#sourceList").data("dirlist","");
		leftNodes=leftTree.getAllNodes();
		rightNodes=rightTree.getAllNodes();
	});
	

		
		$(document).data("sourceFile",null);
		var theme=getcookie("sourceEditorTheme");
		var fsize=getcookie("sourceEditorFsize")*1;
		if (theme==undefined || theme=="") {var theme="ace/theme/chrome"; 	setcookie("sourceEditorTheme",theme);}
		if (fsize==undefined || fsize=="") {var fsize=12; 					setcookie("sourceEditorFsize",fsize);}
		if ($(document).data("sourceClipboard")==undefined) {$(document).data("sourceClipboard","");}



		editor=aikiCallSourceEditor();
		editor.setTheme(theme);
		editor.setFontSize(fsize);
	

	
	$(document).undelegate("#sourceModal .nav-tabs li:not(.active) a","click");
	$(document).delegate("#sourceModal .nav-tabs li:not(.active) a","click",function(){
		var saveNode=$("#sourceEditorToolbar").attr("data-id");
		var loadNode=$(this).attr("data-id");
		sourceEditorSaveNode(saveNode);
		if ($("#sourceModal .tab-content #"+loadNode).length) {
			sourceEditorLoadNode(loadNode);
			editor.gotoLine(0,0);
			editor.getSession().setMode("ace/mode/autohotkey");
			editor.resize(true);
		}
	});

    $(document).undelegate('.sourceTree li',"click");
    $(document).delegate('.sourceTree li',"click",function(e) {
        e.stopPropagation();
    });


	$(document).undelegate("#sourceModal .nav-tabs li a i.fa-close","click");
	$(document).delegate("#sourceModal .nav-tabs li a i.fa-close","click",function(){
		var node=$(this).parent("a").attr("data-id");
		var idx=$(this).parents("li").index();
		var len=$(this).parents("ul").find("li").length;
		if (len>1 && $(this).parents("li").hasClass("active")) {
			if (idx>0) {
				$(this).parents("ul").find("li:eq("+(idx*1-1)+") a").trigger("click");
			} else {
				$(this).parents("ul").find("li:eq("+(idx*1+1)+") a").trigger("click");
			}
		}

		$(this).parents("li").remove();
		$('#sourceModal .tab-content #'+node).remove();
		if (len==1) {
			if ($("#sourceEditorToolbar").parent().hasClass("fullscr")) {
				$("#sourceEditorToolbar .btnFullScr").trigger("click");
			}
			$('#sourceModal').modal("hide");
		}
	});

sourceNodeClick();
sourceListButtons();

$("#sourceList .sourcePanels #rightTree .easytree-node:first").trigger("click");
$("#sourceList .sourcePanels #leftTree .easytree-node:first").trigger("click");
	
function sourceDrop(event, nodes, isSourceNode, source, isTargetNode, target) {
	
}

function sourceLazyNode(event, nodes, node, hasChildren) {
	$("body").addClass("cursor-wait");
	node.lazyUrl = "/engine/forms/source/source.php?mode=ajax&action=getdir&dir="+node.href;
}

function sourceOpenedNode(event, nodes, node, hasChildren) {
	$("body").removeClass("cursor-wait");
}


function sourceGetDir(dir) {
	var d = $.Deferred();
	var res;
	$("body").addClass("cursor-wait");
	$.get("/engine/forms/source/source.php?mode=ajax&action=getdir&dir="+dir,function(data){
		$("#sourceList").data("dirlist",data);
		d.resolve();
		$("body").removeClass("cursor-wait");
	});
	return d;
}

function sourceNodeClick() {
	$(document).undelegate("#sourceList .sourceTree li","click");
	$(document).delegate("#sourceList .sourceTree li","click",function(e){
		var node=$(this).children(".easytree-node").attr("id");
		if ($(this).parents(".panel").hasClass("l")) {
				var easytree=leftTree; nodes=leftNodes;
		} else {var easytree=rightTree;nodes=rightNodes;}
		var obj=easytree.getNode(node);
		var name=obj.text;
		if (obj.isFolder==true) {var path=obj.href;} else {var path=obj.hrefTarget;}
		if (path=="") {path="/";}

		$(this).parents(".sourceTree").data("path",path);
		$(this).parents(".sourceTree").data("name",name);
		$(this).parents(".sourceTree").data("node",node);
		
		return false;
	});	
	
	$(document).delegate("#sourceList .sourceTree li span","click",function(e){
		$("#sourceList .sourcePanels .panel").removeClass("active");
		$(this).parents(".panel").find("li").removeClass("active");
		$(this).parents(".panel").addClass("active");
		$(this).parent("li").addClass("active");
		sourceListButtons();
		e.preventDefault();
	});	
	
	$(document).undelegate("#sourceList .sourceTree li","dblclick");
	$(document).delegate("#sourceList .sourceTree li","dblclick",function(){
		var node=$(this).children(".easytree-node").attr("id");
		if ($(this).parents(".sourceTree").parent(".panel").hasClass("l")) {
				easytree=leftTree; nodes=leftNodes;
		} else {easytree=rightTree; nodes=rightNodes;}
		var obj=easytree.getNode(node);

		var path=obj.hrefTarget;
		var name=obj.text;
		if (path!==undefined && obj.isFolder==false) {
			var saveNode=$("#sourceEditorToolbar").attr("data-id");
			sourceEditorSaveNode(saveNode);
		
			$("#sourceEditorToolbar").data("sourceFile",obj.href);
			var send={"file":$("#sourceEditorToolbar").data("sourceFile")};
			var ext=explode(".",obj.text); var ext=ext[count(ext)-1];
			if (ext=="js") {ext="javascript";}
			if (!$("#sourceModal .tab-content #"+node).length) {
				$.get("/engine/forms/source/source.php?mode=ajax&action=getfile",send,function(data){
					$("#sourceEditorToolbar .btnSave").removeClass("btn-danger");
						$('#sourceModal .nav-tabs').append('<li class="active"><a data-toggle="tab"><span>'+name+'</span>&nbsp;<i class="fa fa-close"></i></a></li>');
						$('#sourceModal .nav-tabs li.active a').attr("href","#"+node);
						$('#sourceModal .nav-tabs li.active a').attr("data-id",node);
						$('#sourceModal .tab-content').append('<div class="tab-pane"></div>');
						$('#sourceModal .tab-content .tab-pane:last').attr("id",node);
						$('#sourceModal .tab-content #'+node).data("sourceFile",path+name);
						editor.setValue(data);
						$(".ace_editor").css("height",500);
						editor.getSession().setMode("ace/mode/autohotkey");
						editor.getSession().setUndoManager(new ace.UndoManager());
						sourceEditorSaveNode(node);
						sourceEditorLoadNode(node);
						editor.gotoLine(0,0);
				});
			} else {
				editor.setValue($("#sourceModal .tab-content #"+node).data("editor"));
				editor.gotoLine(0,0);
			}
			editor.resize(true);
			$('#sourceModal .nav-tabs li').removeClass("active");
			$('#sourceModal .nav-tabs li a[href=#'+node+']').trigger("click");
			$("#sourceModal .modal-title").html($('#sourceModal .tab-content #'+node).data("sourceFile"));
			$('#sourceModal').modal("show");
			//editor.getSession().on("change",function(){
			//	$('#sourceModal .nav-tabs li a[href=#'+node+'] span').addClass("text-info");
			//});
		}
	});

}

function sourceListButtons() {
	var panels=$("#sourceList .sourcePanels");
	var buttons=$("#sourceList .sourcePanels .sourceButtons");
	var node=$("#sourceList .panel.active .sourceTree li span[id].easytree-active");
	buttons.find(".btn").addClass("disabled");
	buttons.find(".btn.ePanel").removeClass("disabled");
	
	if (node.length) {	
		if (node.hasClass("easytree-ico-c")) {buttons.find(".btn.eFile").removeClass("disabled");}
		if (node.hasClass("easytree-ico-cf")) {buttons.find(".btn.eDir").removeClass("disabled");}
	}
	
	$(buttons).undelegate("a:not(.disabled)","click");
	$(buttons).delegate("a:not(.disabled)","click",function(){
		var tree=$("#sourceList .sourcePanels .panel.active .sourceTree");
		var _tree=$("#sourceList .sourcePanels .panel:not(.active) .sourceTree");
		var path=$(tree).data("path");
		var name=$(tree).data("name");
		var node=$(tree).data("node");

		var _path=$(_tree).data("path");
		var _name=$(_tree).data("name");
		var _node=$(_tree).data("node");

		if (_path==undefined) {_path="/";}

		if ($(tree).parent(".panel").hasClass("l")) {
				var easytree=leftTree; 			var _easytree=rightTree; 
				var nodes=leftNodes;			var _nodes=rightNodes;
				var obj=leftTree.getNode(node); var _obj=rightTree.getNode(node);
		} else {
				var easytree=rightTree;			 var _easytree=leftTree;
				var nodes=rightNodes;			 var _nodes=leftNodes;
				var obj=rightTree.getNode(node); var _obj=leftTree.getNode(node);
		}

		
		if ($(this).hasClass("btnEdit")) {$("#"+node).trigger("dblclick");}
		if ($(this).hasClass("btnDir")) {
			$("#sourcePanelAction .modal-dialog").removeClass("modal-lg");
			$("#sourcePanelAction .modal-content").removeClass("alert alert-danger");
			$("#sourcePanelAction .modal-title").html($("#sourceActions .btnDir").attr("title"));
			$("#sourcePanelAction .modal-body").html($("#sourceActions .btnDir").html());
			$("#sourcePanelAction .modal-footer").html("");
			$("#sourcePanelAction .modal-body button").appendTo($("#sourcePanelAction .modal-footer"));
			$("#sourcePanelAction .modal-body .iPath").val(path);
			$("#sourcePanelAction").modal("show");
		}
		if ($(this).hasClass("btnDel")) {
			if (name!==".") {
				if (obj.isFolder!==true) {path+=name;}
				$("#sourcePanelAction .modal-dialog").removeClass("modal-lg");
				$("#sourcePanelAction .modal-content").addClass("alert alert-danger");
				$("#sourcePanelAction .modal-title").html($("#sourceActions .btnDel").attr("title"));
				$("#sourcePanelAction .modal-body").html($("#sourceActions .btnDel").html());
				$("#sourcePanelAction .modal-footer").html("");
				$("#sourcePanelAction .modal-body button").appendTo($("#sourcePanelAction .modal-footer"));
				$("#sourcePanelAction .modal-body .iName").html(path);
				$("#sourcePanelAction").modal("show");
			}
		}
		
		if ($(this).hasClass("btnRen")) {
			if (name!==".") {
				path=obj.hrefTarget;
				$("#sourcePanelAction .modal-dialog").removeClass("modal-lg");
				$("#sourcePanelAction .modal-content").removeClass("alert alert-danger");
				$("#sourcePanelAction .modal-title").html($("#sourceActions .btnRen").attr("title"));
				$("#sourcePanelAction .modal-body").html($("#sourceActions .btnRen").html());
				$("#sourcePanelAction .modal-footer").html("");
				$("#sourcePanelAction .modal-body button").appendTo($("#sourcePanelAction .modal-footer"));
				$("#sourcePanelAction .modal-body .iPath").val(path);
				$("#sourcePanelAction .modal-body .iName").val(name);
				$("#sourcePanelAction .modal-body .iOld").val(name);
				$("#sourcePanelAction").modal("show");
			}
		}

		if ($(this).hasClass("btnCopy")) {
			if (name!==".") {
				path=obj.href;
				$("#sourcePanelAction .modal-dialog").removeClass("modal-lg");
				$("#sourcePanelAction .modal-content").removeClass("alert alert-danger");
				$("#sourcePanelAction .modal-title").html($("#sourceActions .btnCopy").attr("title"));
				$("#sourcePanelAction .modal-body").html($("#sourceActions .btnCopy").html());
				$("#sourcePanelAction .modal-footer").html("");
				$("#sourcePanelAction .modal-body button").appendTo($("#sourcePanelAction .modal-footer"));
				$("#sourcePanelAction .modal-body .iPath").val(path);
				$("#sourcePanelAction .modal-body .iName").val(_path+name);
				$("#sourcePanelAction .modal-body .iOld").val(name);
				$("#sourcePanelAction").modal("show");
			}
		}
		
		if ($(this).hasClass("btnFile")) {
			$("#sourcePanelAction .modal-dialog").removeClass("modal-lg");
			$("#sourcePanelAction .modal-content").removeClass("alert alert-danger");
			$("#sourcePanelAction .modal-title").html($("#sourceActions .btnFile").attr("title"));
			$("#sourcePanelAction .modal-body").html($("#sourceActions .btnFile").html());
			$("#sourcePanelAction .modal-footer").html("");
			$("#sourcePanelAction .modal-body button").appendTo($("#sourcePanelAction .modal-footer"));
			$("#sourcePanelAction .modal-body .iPath").val(path);
			$("#sourcePanelAction").modal("show");
		}
	});
	
	$(document).undelegate("#sourcePanelAction button","click");
	$(document).delegate("#sourcePanelAction button","click",function(){
		// ################################ Создание каталога
		if ($(this).hasClass("btnDir")) {
			var path=$("#sourcePanelAction .modal-body .iPath").val();
			var name=$("#sourcePanelAction .modal-body .iName").val();
			if (name>"") {
				$.get("/engine/ajax.php?mode=createFolder&path="+path+"&name="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnDir");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}
		// ################################ Создание файла
		if ($(this).hasClass("btnFile")) {
			var path=$("#sourcePanelAction .modal-body .iPath").val();
			var name=$("#sourcePanelAction .modal-body .iName").val();
			if (name>"") {
				$.get("/engine/ajax.php?mode=createFile&path="+path+"&name="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnFile");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}	
		// ################################ Удаление
		if ($(this).hasClass("btnDel")) {
			var name=$("#sourcePanelAction .modal-body .iName").text();
			if (name>"") {
				$.get("/engine/ajax.php?mode=deletePath&path="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnDel");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}
		// ################################ Переименование
		if ($(this).hasClass("btnRen")) {
			var old=$("#sourcePanelAction .modal-body .iOld").val();
			var name=$("#sourcePanelAction .modal-body .iName").val();
			var path=$("#sourcePanelAction .modal-body .iPath").val();
			if (name>"") {
				$.get("/engine/ajax.php?mode=renameFile&path="+path+"&old="+old+"&new="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnRen");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}
		// ################################ Копирование
		if ($(this).hasClass("btnCopy")) {
			var name=$("#sourcePanelAction .modal-body .iName").val();
			var path=$("#sourcePanelAction .modal-body .iPath").val();
			if (name>"") {
				$.get("/engine/ajax.php?mode=copyFile&old="+path+"&new="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnCopy");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}
		// ################################ Перемещение
		if ($(this).hasClass("btnMove")) {
			var name=$("#sourcePanelAction .modal-body .iName").val();
			var path=$("#sourcePanelAction .modal-body .iPath").val();
			if (name>"" && path!==name) {
				$.get("/engine/ajax.php?mode=moveFile&old="+path+"&new="+name,function(data){
					var data=$.parseJSON(data);
					if (data!==false && data.error==true) {sourceUpdateTree(path,name,"btnCopy");}
					
				});
				$("#sourcePanelAction").modal("hide");
			}
		}		
	});
}

function sourceUpdateTree(path,name,mode) {
	var tree=$("#sourceList .sourcePanels .panel.active .sourceTree");
	var node=$(tree).data("node");

	if (path=="/") {var targetId=""; var href=path+name;} else {var targetId=node; var href=path+"/"+name;}
	href=str_replace("//","/",href);
	if (mode=="btnDir") {
			var sourceNode = {};
			sourceNode.text = name;
			sourceNode.isFolder = true;
			sourceNode.href=href;
			sourceNode.hrefTarget=path;
			sourceNode.uiIcon="fa fa-folder-o";
			sourceNode.isExpanded=false;
			sourceNode.isLazy=true;
			leftTree.addNode(sourceNode, targetId);
			leftTree.rebuildTree();
	}
	if (mode=="btnFile") {
			var sourceNode = {};
			sourceNode.text = name;
			sourceNode.isFolder = false;
			sourceNode.href=href;
			sourceNode.hrefTarget=path+"/";
			sourceNode.uiIcon="fa fa-file-o";
			leftTree.addNode(sourceNode, targetId);
			leftTree.rebuildTree();
	}
	if (mode=="btnRen") {
			var sourceNode = leftTree.getNode(node);
			var parent=$("#sourceList .sourceTree li span[id="+node+"]").parents("li").parents("li").children("span[id]").attr("id");
			if (parent!==undefined) {targetId=parent;}
			sourceNode.text = name;
			sourceNode.href=href;
			sourceNode.hrefTarget=path;
			leftTree.removeNode(sourceNode.id);
			leftTree.addNode(sourceNode, targetId);	
			leftTree.rebuildTree();	
	}
	if (mode=="btnDel") {
			leftTree.removeNode(node);
			leftTree.rebuildTree();
	}
	
	sourceNodeClick();
	$("#sourceList .sourceTree li span[id="+node+"]").trigger("click");
}
	
function sourceEditorSaveNode(saveNode) {
	$("#sourceModal .tab-content #"+saveNode).data("editor",editor.getValue());
	$("#sourceModal .tab-content #"+saveNode).data("editorUndo",editor.getSession().getUndoManager());
}

function sourceEditorLoadNode(loadNode) {
	editor.setValue($("#sourceModal .tab-content #"+loadNode).data("editor"));
	editor.getSession().setUndoManager($("#sourceModal .tab-content #"+loadNode).data("editorUndo"));
	$("#sourceEditorToolbar").attr("data-id",loadNode);
	$("#sourceEditorToolbar").data("sourceFile",$('#sourceModal .tab-content #'+loadNode).data("sourceFile"));
	$("#sourceModal .modal-title").html($('#sourceModal .tab-content #'+loadNode).data("sourceFile"));
}
	

	$("#sourceEditorToolbar button").click(function(){
		var theme=getcookie("sourceEditorTheme");
		var fsize=getcookie("sourceEditorFsize");
		if (theme==undefined || theme=="") {var theme="ace/theme/chrome";	setcookie("sourceEditorTheme",theme);}
		if (fsize==undefined || fsize=="") {var fsize=12; 					setcookie("sourceEditorFsize",fsize);}

		//if ($(this).hasClass("btnCopy")) 		{$(document).data("sourceFile",editor.getCopyText());}
		//if ($(this).hasClass("btnPaste")) 		{editor.insert($(document).data("sourceFile"));}
		if ($(this).hasClass("btnCopy")) 		{$(document).data("sourceClipboard",editor.getCopyText());}
		if ($(this).hasClass("btnPaste")) 		{editor.insert($(document).data("sourceClipboard"));}
		if ($(this).hasClass("btnUndo")) 		{editor.execCommand("undo");}
		if ($(this).hasClass("btnRedo")) 		{editor.execCommand("redo");}
		if ($(this).hasClass("btnFind")) 		{editor.execCommand("find");}
		if ($(this).hasClass("btnReplace")) 	{editor.execCommand("replace");}
		if ($(this).hasClass("btnLight")) 		{editor.setTheme("ace/theme/chrome"); setcookie("sourceEditorTheme","ace/theme/chrome");}
		if ($(this).hasClass("btnDark")) 		{editor.setTheme("ace/theme/monokai");  setcookie("sourceEditorTheme","ace/theme/monokai");}
		if ($(this).hasClass("btnClose")) 	{
			editor.setValue("");
			$(document).data("sourceFile",null);
			$("#sourceEditorToolbar .btnSave").removeClass("btn-danger");
		}
		if ($(this).hasClass("btnFontDn")) 	{
			if (fsize>8) {fsize=fsize*1-1;}
			editor.setFontSize(fsize); setcookie("sourceEditorFsize",fsize);
		}
		if ($(this).hasClass("btnFontUp")) 	{
			if (fsize<20) {fsize=fsize*1+1;}
			editor.setFontSize(fsize); setcookie("sourceEditorFsize",fsize);
		}
		if ($(this).hasClass("btnSave") && $("#sourceEditorToolbar").data("sourceFile")!==null) {
			var send={"file":$("#sourceEditorToolbar").data("sourceFile"),"value":editor.getValue()};
			$.post("/engine/forms/source/source.php?mode=ajax&action=setfile",send,function(data){
				if (data>"") {$("#sourceEditorToolbar .btnSave").removeClass("btn-danger");}
			});
		}


//		editor.commands.on("afterExec", function(e){
//        if (e.command.name == "insertstring"&&/^[\<.]$/.test(e.args)) {
//			editor.execCommand("startAutocomplete")
//			}
//	  });
	});
});
</script>
