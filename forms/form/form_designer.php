<div id="formDesigner" class="container">
	<header class="navbar navbar-inverse navbar-fixed-bottom">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt');this.blur();">
                                    <i class="fa fa-gear fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-gear fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                            </li>
                            <!-- END Main Sidebar Toggle Button -->

                            <!-- Header Link -->
                            <li class="animation-fadeInQuick toParent"></li>
                            <li class="animation-fadeInQuick">
                                <a href=""><strong class="currentInfo"></strong></a>
                            </li>
                            <!-- END Header Link -->
                        </ul>
	</header>

		<div class="modal fade" id="sourceEditor" data-keyboard="false" data-backdrop="true" role="dialog" aria-labelledby="comModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-md">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="comModalLabel">{{header}}</h4>
			  </div>
			  <div class="modal-body">
					<div data-role="include" src="source" data-role-hide="true"></div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-sm btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Отменить</button>
				<button type="button" class="btn btn-sm btn-primary" data-formsave=""><span class="glyphicon glyphicon-ok"></span> Применить</button>
			  </div>
			</div>
		  </div>
		</div>
		
		<div data-role="include" src="modal" data-id="formCreator" data-role-hide="true"></div>
		<form id="formMasterForm" name="form" item="master"  class="form-horizontal" role="form" append="#formCreator .modal-body">
			<div class="form-group">
			  <label class="col-xs-6 col-sm-4 control-label">ID формы</label>
			   <div class="col-xs-6 col-sm-8"><input type="text" class="form-control" name="name" value="" placeholder="ID формы" required ></div>
			</div>

			<div class="form-group">
			  <label class="col-xs-6 col-sm-4 control-label">Имя формы</label>
			   <div class="col-xs-6 col-sm-8"><input type="text" class="form-control" name="descr" value="" placeholder="Имя формы" required ></div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label">Добавить в список форм</label>
				<div class="col-xs-4"><label class="switch switch-primary"><input type="checkbox" name="tolist" value="on" checked="checked"><span></span></label></div>
			</div>
		</form>

			
	



                <div id="sidebar-alt" tabindex="-1" aria-hidden="false">
                    <!-- Toggle Alternative Sidebar Button (visible only in static layout) -->
                    <a href="javascript:void(0)" id="sidebar-alt-close" onclick="App.sidebar('toggle-sidebar-alt');"><i class="fa fa-times"></i></a>

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll-alt">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
							<br>
							<ul class="nav nav-tabs">
								<li class="active"><a  class="themed-background" href="#snippets" data-toggle="tab"><i class="fa fa-code"></i></a></li>
								<li><a  class="themed-background" href="#props" data-toggle="tab"><i class="fa fa-gear"></i></a></li>
								<li><a  class="themed-background" href="#forms" data-toggle="tab"><i class="fa fa-folder-open"></i></a></li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content" style="height:500px; overflow-y:auto;">
								<div class="tab-pane active" id="snippets">
									<div class="sidebar-section">
									<h4 class="text-light">Шаблоны</h4>
										<ul>
											<li><a href="#snippet" data="container">container</a></li>
											<li><a href="#snippet" data="row">row</a></li>
											<li><a href="#snippet" data="col">col</a></li>
											<li><a href="#snippet" data="inputgroup1">formgroup input (1)</a></li>
											<li><a href="#snippet" data="inputgroup2">formgroup input (2)</a></li>
											<li><a href="#snippet" data="selectgroup1">formgroup select (1)</a></li>
											<li><a href="#snippet" data="selectgroup2">formgroup select (2)</a></li>
											<li><a href="#snippet" data="editform">edit form</a></li>
											<li><a href="#snippet" data="listitems">listitems</a></li>
											<li><a href="#snippet" data="button">button</a></li>
											<li><a href="#snippet" data="panel">панель</a></li>
										</ul>
									</div>
									<!-- END Profile -->


								</div>
								<div class="tab-pane" id="props">
									<div class="sidebar-section">
										<h4 class="text-light">Свойства</h4>
									</div>								
								</div>
								<div class="tab-pane" id="forms">
									<div class="sidebar-section">
										<h4 class="text-light">Форма</h4>
									
									</div>								
								</div>
								
							</div>

									<!-- END Settings -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
<div class="row">
	<div class="panel col-xs-8">
	<div class="row form-horizontal">
		
		<div class="form-group row">
			<label class="col-xs-4 control-label">Форма</label>
			<div class="col-xs-4">
				<select class="form-control" id="formName" placeholder="форма" data-role="foreach" from="forms">
						<option value="{{0}}">{{0}}</option>
				</select>
		   </div>
		   <button type="button" id="formAdd" class="btn btn-success"><i class="fa fa-plus"></i></button>
		</div>

		<ul class="nav nav-tabs" id="formList"></ul>
	</div>
		
		
		<div id="formDesignerEditor" class="tab-content form-horizontal" >
			<div id="formDesignerToolBtn">
				<a class="btn btn-sm btn-primary"><i class="fa fa-gear"></i></a>
				<a class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></a>
				<a class="btn btn-sm btn-primary"><i class="fa fa-code"></i></a>
				<a class="btn btn-sm btn-primary"><i class="fa fa-trash"></i></a>
			</div>
		</div>
	</div>
</div>
</div>

<script language="javascript">
	$("#formDesignerEditor #formDesignerToolBtn").hide();
	$("#formDesignerEditor").undelegate("*","mouseover");
	$("#formDesignerEditor").delegate("*","mouseover",function(event){
		$("#formDesignerEditor [data-hovered]").removeAttr("data-hovered");
		if (!$(event.target).is("#formDesignerToolBtn") && !$(event.target).parents("#formDesignerToolBtn").length) {
			$(event.target).attr("data-hovered",true);
		}
	});
	
	$("#formDesigner #formAdd").on("click",function(){
		$("#formCreator .modal-header").html('<h4 class="modal-title">Создать новую форму</h4>');
		$("#formCreator").modal("show");
	});
	
	$(document).undelegate("#formDesigner #formName","change");
	$(document).delegate("#formDesigner #formName","change",function(){
		var that=this;
		$("#formDesignerEditor .formDesignerEditor[data-path]").remove();
		$.get("/engine/ajax.php?mode=listModes&form=form&name="+$(this).val(),function(data){
			var data=$.parseJSON(data);
			var forms=data.app;
			var name=$(that).val();
			var i=0;
			$("#formDesigner #formList").html("");
			$("#formDesigner #formDesignerEditor .formDesignerEditor").html("");
			$.each(forms,function(i,form){
				if (form.form==name && form.ext=="php" && form.mode>"") {
					console.log(form);
					var formId=form.form+'_'+form.mode;
					var formHref="#"+formId;
					$("#formDesigner #formList").append(
						'<li><a data-toggle="tab">'+
						form.mode+
						'</a></li>');
						$("#formDesigner #formList > li:last a").attr("href",formHref);
					$("#formDesignerEditor").append('<div class="tab-pane formDesignerEditor"></div>');
					$("#formDesignerEditor > .formDesignerEditor:last").attr("id",formId);
					$("#formDesignerEditor > .formDesignerEditor:last").attr("data-path",form.uri);
					$.get("/engine/ajax.php?mode=getform&form=form&path="+form.uri,function(data){
						data=str_replace("data-strip-role","data-role",data);
						$("#formDesignerEditor #"+formId).html(data);
					});
				}
			});
			$("#formDesigner #formList").append('<a href="#" class="btn btn-default pull-right formDesignerSave">Сохранить</a>');
			$("#formDesigner #formList > li:first").addClass("active");
			$("#formDesignerEditor > .formDesignerEditor:first").addClass("active");
		});
	});
	
	$("#formDesigner").undelegate(".formDesignerSave","click");
	$("#formDesigner").delegate(".formDesignerSave","click",function(){
		$("#formDesignerEditor .formDesignerEditor[data-path]").each(function(){
			var uri=$(this).attr("data-path");
			var data={content:$(this).html()};
			$.ajax({
				async: 		true,
				url: "/engine/ajax.php?mode=putform&form=form&path="+uri,
				method: "post",
				data: data,
				success: function(data){
					console.log(data);
				},
				error: function(){return false;}
			});

		});
		return false;
	});
	
	
	$("#formDesigner #sourceEditorToolbar").parent(".panel").css("margin-bottom","0px");

	$("#formDesignerEditor #formDesignerToolBtn").undelegate(".btn","click");
	$("#formDesignerEditor #formDesignerToolBtn").delegate(".btn","click",function(){
		var btn=$(this).find(".fa");
		if ($(btn).hasClass("fa-trash")) {
			var that=$(".formDesignerEditor.active [data-current]");
			var parent=that.parent();
			$(".formDesignerEditor.active #formDesignerToolBtn").hide();
			//$("#formDesigner header .toParent").trigger("click");
			$(that).remove();
			if ($(parent).html()=="") {$(parent).html("&nbsp;");}
			parent.trigger("click");
		}
		if ($(btn).hasClass("fa-copy")) {
			var that=$(".formDesignerEditor.active [data-current]");
			var copy=$(that).clone();
			$(that).after(copy);
			copy.trigger("click");
		}
		if ($(btn).hasClass("fa-code")) {
			if ($("#formDesigner #codeEditor").hasClass("visible")) {
					$("#formDesigner #codeEditor").removeClass("visible");
			} else {
				var content=$(".formDesignerEditor.active [data-current]").prop('outerHTML');
				content=str_replace('data-current="true"','',content);
				
					editor.setValue(content);
					editor.getSession().setMode("ace/mode/autohotkey");
					$("#formDesigner #sourceEditor").modal("show");
					$(".ace_editor").css("height",400);
					editor.gotoLine(0,0);
			}
		}
		return false;
	});
	
	$("#formDesigner #sourceEditor [data-formsave]").on("click",function(){
		var content=$("<div>"+editor.getValue()+"</div>");
		$(content).find(":first").attr("data-current",true);
		$(".formDesignerEditor.active [data-current]").replaceWith($(content).html());
		$("#formDesigner #sourceEditor").modal("hide");
	});

	$(".formDesignerEditor.active").undelegate("*","mouseleave");
	$(".formDesignerEditor.active").delegate("*","mouseleave",function(event){
		$(event.target).removeAttr("data-hovered");
	});
	
	
	$("#formDesigner header .toParent").undelegate("a[href=#parent]","click");
	$("#formDesigner header .toParent").delegate("a[href=#parent]","click",function(){
		if ($(".formDesignerEditor.active [data-current]").parent().attr("id")!=="formDesignerEditor") {
			$(".formDesignerEditor.active [data-current]").parent().trigger("click");
		} else {
			$(".formDesignerEditor.active [data-current]").removeAttr("data-current");
			$("#formDesigner header .toParent").html("");
			$("#formDesigner header .currentInfo").html("");
		}
	});
	
	$("#formDesignerEditor").undelegate(".formDesignerEditor.active *","click");
	$("#formDesignerEditor").delegate(".formDesignerEditor.active *","click",function(event){
		formDesigner_clickElement(event.target);
	});

	$(document).undelegate("*","click");
	$(document).delegate("*","click",function(event){
		if (	!$(event.target).parents(".formDesignerEditor").length
			&&	!$(event.target).parents("#sourceEditor").length
			&&	!$(event.target).parents("#sidebar-scroll-alt").length
		) {
			$("#formDesignerEditor #formDesignerToolBtn").hide();
			$("#formDesignerEditor .formDesignerEditor [data-current]").removeAttr("data-current");
		} else {
			if ($(event.target).is("[data-ajax]")) {event.preventDefault(); return false;}
		}
	});
	

	$("#formDesigner a[href=#snippet]").on("click",function(){
		var snippet=$(this).attr("data");
		var load=$("<meta>");
		var form=$("#formDesigner > .row #formName").val();
		var target=".formDesignerEditor.active";
		if ($(".formDesignerEditor.active [data-current]").length) {target=".formDesignerEditor.active [data-current]";}
		$.get("/engine/ajax.php?mode=snippet&form=form&snippet="+snippet+"&formname="+form,function(data){
			if ($(target).html()=="&nbsp;") {$(target).html("");}
			data=str_replace("data-strip-role","data-role",data);
			var data=$(data);
			$(target).append(data);
			if (!$(".formDesignerEditor.active [data-current]").length) {$(data).trigger("click");}
		});
	});
	
	function formDesigner_clickElement(that) {
		$("#formDesignerEditor .formDesignerEditor.active [data-current]").removeAttr("data-current");
		$("#formDesignerEditor #formDesignerToolBtn").hide();
		if (!$(that).is("#formDesignerToolBtn") && !$(that).parents("#formDesignerToolBtn").length) {
			$(that).attr("data-current",true);
			var x=$(that).offset().left;
			var y=$(that).offset().top-24;
			var tool=$("#formDesignerEditor #formDesignerToolBtn");
			$(tool).css("left",x+"px").css("top",y+"px");
			var tagName=that.tagName;
			if ($(that).attr("id")>"") {tagName+="#"+$(that).attr("id");}
			var className=that.className;
				className=trim(str_replace(" ",".",className));
			if (className>"") {className="."+className;}
			var parent="";
			parent='<a href="#parent"><i class="fa fa-arrow-left"></i></a>';
			$("#formDesigner header .toParent").html(parent);
			$("#formDesigner header .currentInfo").html(tagName+className);
			$(tool).show();
		}
	}
	
	$("#formDesigner #formCreator input[name=name]").on("keyup",function(){
		$(this).val($(this).val().replace(/[^a-z0-9]/i, ""));
	});
	
	$("#formDesigner #formCreator [data-formsave]").on("click",function(){
		if (check_required("#formDesigner #formMasterForm")) {
			var name=$("#formDesigner  #formMasterForm [name=name]").val();
			var data=$("#formDesigner  #formMasterForm").serialize();
			$.ajax({
				url: "/engine/ajax.php?mode=create&form=form",
				method: "post",
				data: data,
				success: function(data){
					var data=JSON.parse(data); 
					if (data.error==false) {
						var type="success";
						$("#formDesigner  #formCreator").modal("hide");
						$(".sidebar-nav .formlist").append(data.append);
						$("#formDesigner #formName").append('<option>'+name+'</option>');
						$("#formDesigner #formName option:last").attr("value",name);
					} else {var type="warning";}
					
					if ($.bootstrapGrowl) {
						$.bootstrapGrowl(data.status, {
							ele: 'body',
							type: type,
							offset: {from: 'top', amount: 20},
							align: 'right',
							width: "auto",
							delay: 4000,
							allow_dismiss: true,
							stackup_spacing: 10 
						});
					}
					
					return data;
				},
				error: function(){return false;}
			});
		} else {
			if (document.location.host=="digiport.loc") {
				ajax_load($('<meta data-ajax="mode=designer&form=form" data-html="div.main">'));
			}
		}
	});
	
	
</script>

<style type="text/css" media="screen">
#formDesigner .formDesignerEditor {min-height:100px; background:#fff;}
#formDesignerEditor {padding:5px;}
#formDesignerEditor [data-current] {border: 1px #aaa dashed; background: rgba(217, 255, 228, 0.3); cursor:move;}
#formDesignerEditor [data-hovered] { background: rgba(98, 122, 173, 0.25)!important;}
#formDesignerToolBtn {position:fixed; display:inline-block; width:auto; z-index:100;}
#formDesigner header .currentInfo a {color:#fff;}
#formDesignerEditor .formDesignerEditor .row {background: rgba(92, 205, 222, 0.05);}
#formDesignerEditor .formDesignerEditor .row > [class*="col-"]:nth-child(odd) {background: rgba(92, 105, 122, 0.05);}

#formDesignerToolBtn .btn {border-radius: 100%;padding: 1px;height: 20px;width: 20px;font-size: 12px;line-height: 16px;}

#formDesignerEditor .formDesignerEditor [data-role=include],
#formDesignerEditor .formDesignerEditor [data-role=imageloader],
#formDesignerEditor .formDesignerEditor [data-role=source],
#formDesignerEditor .formDesignerEditor textarea.editor
	{min-height: 100px; width: 100%; border: 1px #eee dotted; background: rgba(238, 238, 238, 0.3);}
#formDesignerEditor .formDesignerEditor [data-role=include]::before {content:'Динамическая вставка';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=imageloader]::before {content:'Загрузчик изображений';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=source]::before {content:'Димамическая вставка: Редактор исходного кода';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=modal]::before {content:'Димамическая вставка: Модальное окно';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=editor]::before {content:'Димамическая вставка: Текстовый редактор';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=uploader]::before {content:'Димамическая вставка: Загрузчик файлов';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=comments]::before {content:'Димамическая вставка: Модуль коментариев';color:#aaa;}


    #sourceEditor.fullscr, #sourceEditor.fullscr .modal-dialog {width:100% !important; padding:0 !important; margin:0 !important;}
    #sourceEditor.fullscr .modal-body {padding:0;}
    #sourceEditor.fullscr .modal-header, #sourceEditor.fullscr .modal-footer {display:none;}
    #sourceEditor.fullscr .nav {display:none;}
    
    #sourceEditor .modal-header, #sourceEditor .modal-footer {padding:5px;}
    #sourceEditor .modal-body {padding:0px;}
    
    #sourceEditor .ace_editor {margin:0;}

</style>
