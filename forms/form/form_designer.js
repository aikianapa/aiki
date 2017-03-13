$(document).ready(function(){
	App.init(); // инициализация backEnd
	$("#formDesignerEditor #formDesignerToolBtn").hide();

	$.get("/engine/ajax.php?mode=snippets&form=form",function(snippets) {
		formDesigner_selectForm();
		formDesigner_saveForms();
		formDesigner_ToolBtn_click();
		formDesigner_unsetElement();
		formDesigner_formList();
		formDesigner_selectSnippet(snippets);
		formDesigner_createForm();
		formDesigner_Nav();
		formDesigner_resize();
		formDesigner_Events();
		formDesigner_Editor();
		formDesigner_Tree();
	});
});	

	function formDesigner_Editor() {
		$("#formDesigner #sourceEditorToolbar").parent(".panel").css("margin-bottom","0px").removeClass("panel");
		$("#formDesigner #sourceEditorToolbar .btn").each(function(){
			$(this).removeClass("btn-default");
		});
		
		$("#formDesigner #sourceEditorToolbar .btnFullScr").remove();
		$("#formDesigner #sourceEditorToolbar .btnSave").remove();
		$("#formDesigner #sourceEditorToolbar .btn").css({"padding":"1px","height":"23px","width":"23px","font-size":"12px","line-height":"16px","border-radius":"100%"});
		$("#formDesigner #sourceEditorToolbar").removeClass("panel-heading");
	}

	function formDesigner_Color() {
			var colorBody=$("body").css("color");
			var navbarInv=$(".navbar-inverse").css("background-color");
			$("#formDesigner .formDesignerEditor").css("border-color",colorBody);
	}
	
	function formDesigner_Events() {
		$("#formDesignerEditor").undelegate("*","mouseover");
		$("#formDesignerEditor").delegate("*","mouseover",function(event){
			$("#formDesignerEditor [data-hovered]").removeAttr("data-hovered");
			if (!$(event.target).is("#formDesignerToolBtn") && !$(event.target).parents("#formDesignerToolBtn").length) {
				$(event.target).attr("data-hovered",true);
			}
		});
		
		$(".formDesignerNav #formAdd").on("click",function(){
			$("#formCreator .modal-header").html('<h4 class="modal-title">Создать новую форму</h4>');
			$("#formCreator").modal("show");
		});	

		$("#formDesigner #sourceEditor [data-formsave]").on("click",function(){
			$(".formDesignerEditor.active [data-current]").replaceWith($(editor.getValue()).attr("data-current","true"));
			$("#formDesigner #sourceEditor").modal("hide");
		});
		


		$("#formDesigner").undelegate("[data-hovered]","mouseleave");
		$("#formDesigner").delegate("[data-hovered]","mouseleave",function(event){
			$(event.target).removeAttr("data-hovered");
		});
		
		$("#formDesigner").undelegate("[contenteditable]","keyup");
		$("#formDesigner").delegate("[contenteditable]","keyup",function(event){
			var content=$(".formDesignerEditor.active [data-current]").clone();
			content.removeAttr("data-current data-hovered contenteditable");
			editor.setValue(content.prop('outerHTML'));
			editor.getSession().setUndoManager(new ace.UndoManager());
			editor.getSession().setMode("ace/mode/autohotkey");
			editor.gotoLine(0,0);
			$(document).trigger("window-resize");			
		});
		
		$("#formDesignerEditor").undelegate(".formDesignerEditor.active *","click");
		$("#formDesignerEditor").delegate(".formDesignerEditor.active *","click",function(event){
			formDesigner_clickElement(event.target);
		});

		
		$("#formDesigner").undelegate(".latin-only","keyup");
		$("#formDesigner").delegate(".latin-only","keyup",function(){
			$(this).val($(this).val().replace(/[^a-z0-9]/i, ""));
		});
		

		$("#formDesignerBlock .panel").scroll(function(){
			formDesigner_ToolBtn();
		});
	}

	function formDesigner_ToolBtn() {
		if ($("#formDesignerEditor .formDesignerEditor.active [data-current]").length) {
			var that=$("#formDesignerEditor .formDesignerEditor.active [data-current]");
			var x=$(that).offset().left;
			var y=$(that).offset().top-24;
			var tool=$("#formDesignerEditor #formDesignerToolBtn");
			$(tool).css("left",x+"px").css("top",y+"px");
			return tool;		
		}
	}
	
	function formDesigner_Tree() {
		$("#formDesignerHeader").undelegate(".currentInfo","click");
		$("#formDesignerHeader").delegate(".currentInfo","click",function(){
			$("#sidebar-tree #tagsTree").html(
				formDesigner_TreeBranch($("#formDesignerEditor .formDesignerEditor.active"))
			);
			
			//tagsTree=$("#sidebar-tree #tagsTree").easytree();
			//$("#sidebar-tree #tagsTree").find(".easytree-exp-c > .easytree-icon, .easytree-exp-cl > .easytree-icon").removeClass("easytree-icon").addClass("fa fa-tags");
			//$("#sidebar-tree #tagsTree").find(".easytree-icon").removeClass("easytree-icon").addClass("fa fa-tag");
			//$("#sidebar-tree [title]").tooltip();
		});
	}
	
	function formDesigner_TreeBranch(from) {
		var list=""; 
		$(from).find(">").each(function(){
				var tagName=this.tagName;
				if ($(this).attr("id")>"") {tagName+="#"+$(this).attr("id");}
				if ($(this).attr("data-role")>"") {tagName+="[data-role="+$(this).attr("data-role")+"]";}
				if ($(this).attr("name")>"") {tagName+="[name="+$(this).attr("name")+"]";}
				var className=this.className;
				className=trim(str_replace(" ",".",className));
				if (className>"") {className="."+className;}
				if ($(this).children().length) {
					var child=formDesigner_TreeBranch(this);
				} else {child="";}
				var name=tagName;
				list+='<li data-tag="'+tagName+'"><a title="'+className+'" href="#">'+name+child+'</a></li>';
		});
		if (list>"") {list="<ul>"+list+"</ul>";}
		return list;
	}
	
	function formDesigner_ToolBtn_click() {
		$("#formDesignerEditor #formDesignerToolBtn").undelegate(".btn","click");
		$("#formDesignerEditor #formDesignerToolBtn").delegate(".btn","click",function(){
			formDesigner_removePopovers();
			var btn=$(this).find(".fa");
			if ($(btn).hasClass("fa-trash")) {
				var that=$(".formDesignerEditor.active [data-current]");
				var parent=that.parent();
				$(".formDesignerEditor.active #formDesignerToolBtn").hide();
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
	}
	
	function formDesigner_resize() {
		$(document).on('window-resize', function () {
			var dPanel=$("#formDesignerBlock > .panel:eq(0)");
			var sPanel=$("#formDesignerBlock > .panel:eq(1)");
			var height=$(window).height()-50;
			$("#page-content").css("min-height",height+50+"px");
			dPanel.height(height/50*90);
			sPanel.width(dPanel.width());
			$(".ace_editor").css("height",450);
			$("#sidebar .slimScrollDiv").css("height",height);
			$("#sidebar #sidebar-scroll").css("height",height);
			$("#sidebar #sidebar-scroll-alt").css("height",height);
			$("#designerSourceEditor").width($("#page-content .main").width()+30);
			formDesigner_ToolBtn();
		});
		$(document).trigger("window-resize");		
	}
	
	function formDesigner_AddMode() {
		var form=$(".formDesignerNav #formName").val();
		var path=$(".formDesignerNav #formName option:selected").attr("data-path");
		$("#sidebar #formList .btn").before('<li><a data-toggle="tab" href="#formEditorAddMode"><input class="latin-only"></a></li>');
		$("#sidebar #formList a.active").removeClass("active");
		$("#sidebar #formList li:last").addClass("active");
		$("#sidebar #formList li:last input").focus();
		$("#sidebar #formList li:last .fa-times").on("click",function(){
			$("#sidebar #formList li:last").remove();
			return false;
		});
		$("#sidebar #formList li:last input").on("focusout",function(){
			if ($(this).val()=="") {$("#sidebar #formList li:last").remove();}
		});
		$("#sidebar #formList li:last input").on("change",function(event){
			var mode=$(this).val();
			if (mode=="") {var error=true;} else {var error=false;}
			$("#sidebar #formList li:not(':last')").each(function(){
				if ($(this).text()==mode) {error=true; $(this).find("a").trigger("click");}
			});
			if (error==true) {$("#sidebar #formList li:last").remove();} else {
				$("#sidebar #formList li:last a").html(mode+'<i class="fa fa-remove"></i>');
				$("#sidebar #formList li:last a").attr("href","#"+form+"_"+mode);
				$("#formDesignerEditor .formDesignerEditor").removeClass("active");
				$("#formDesignerEditor").append('<div class="tab-pane formDesignerEditor active"></div>')
				$("#formDesignerEditor .formDesignerEditor:last").attr("id",form+"_"+mode);
				$("#formDesignerEditor .formDesignerEditor:last").attr("data-path",path+"/"+form+"_"+mode+".php");
			}
		});

	}

	function formDesigner_clickElement(that) {
		$("#formDesignerEditor .formDesignerEditor.active [data-current]").removeAttr("data-current");
		$("#formDesignerEditor #formDesignerToolBtn").hide();
		$(".formDesignerNav").hide();
		formDesigner_removePopovers();
		console.log($(that).prop('outerHTML'));
		if (!$(that).is("#formDesignerToolBtn") && !$(that).parents("#formDesignerToolBtn").length) {
			$(".formDesignerEditor.active").find("[contenteditable]").removeAttr("contenteditable");
			$(that).attr("data-current","true");
			$(that).attr("contenteditable","true");
			var tool=formDesigner_ToolBtn();
			var tagName=$(that)[0].tagName;
			if ($(that).attr("id")>"") {tagName+="#"+$(that).attr("id");}
			var className=$(that)[0].className;
				className=trim(str_replace(" ",".",className));
			if (className>"") {className="."+className;}
			$(".navbar .currentInfo strong").html(tagName+className);
			$(".formDesignerNav").show();
			$("#designerSourceEditor").show();
			$(tool).show();
			var content=$(".formDesignerEditor.active [data-current]").clone();
			content.removeAttr("data-current data-hovered contenteditable");
			editor.setValue(content.prop('outerHTML'));
			editor.getSession().setUndoManager(new ace.UndoManager());
			editor.getSession().setMode("ace/mode/autohotkey");
			editor.gotoLine(0,0);
			$(document).trigger("window-resize");
			editor.on('blur', function() {
				$(".formDesignerEditor.active [data-current]").replaceWith(editor.getValue()).attr("data-current","true");
			});
		} 
	}
	
	function formDesigner_selectSnippet(snippets) {
		var snippets=snippets;
		
		$(".formDesignerSnippets a[href=#snippet]").on("mouseenter",function(){
			var snippet=$(this).attr("data");
			var form=$(".formDesignerNav #formName").val();
			var that=this;
			var zoom=$(this).attr("data-zoom");
			if ($(this).is("[aria-describedby]")) {
				$(this).popover("show");
			} else {
				var data=$(snippets).find("branch[data-id="+snippet+"] data code").html();
					var content='<div class="preview row">'+data+'</div>';
					$(that).popover({
						container:"#page-content",
						placement:"right",
						html:true,
						content: content
					});
					$(that).popover("show");
					if (zoom>"") {$(".popover .row.preview").css("zoom",zoom);}
			}
		});
		
		$(".formDesignerSnippets a[href=#snippet]").on("mouseleave",function(){
			$(this).popover("hide");
		});
		

		$(".formDesignerSnippets a[href=#snippet]").on("click",function(){
			var snippet=$(this).attr("data");
			var load=$("<meta>");
			var form=$(".formDesignerNav #formName").val();
			var target=".formDesignerEditor.active";
			if ($("#formDesignerEditor .formDesignerEditor.active [data-current]").length) {target="#formDesignerEditor .formDesignerEditor.active [data-current]";}
			formDesigner_removePopovers();
			$(target).popover({
				container:"#page-content",
				trigger:"click",
				placement:"auto",
				html:true,
				content:$("#formDesignerSnippetsPrompt").html()
				
			});
			$(target).popover("show");
			var popover=$(target).attr("aria-describedby");
			$("#"+popover+" a").on("click",function(){
				var that=this;
				var data=$(snippets).find("branch[data-id="+snippet+"] data code").html();
				if ($(target).html()=="&nbsp;") {$(target).html("");}
				data=str_replace("data-strip-role","data-role",data);
				var data=$("<div>"+data+"</div>");
				data.find("meta[name=formDesigner]").remove();
				if ($(that).hasClass("sAppend")) {$(target).append(data.html());}
				if ($(that).hasClass("sPrepend")) {$(target).prepend(data.html());}
				if ($(that).hasClass("sAfter")) {$(target).after(data.html());}
				if ($(that).hasClass("sBefore")) {$(target).before(data);}
				if (!$(target).length) {$(data).trigger("click");}
				$(target).popover("destroy");
			});
			

		});
	}
	
	function formDesigner_selectForm() {
		$(document).undelegate(".formDesignerNav #formName","change");
		$(document).delegate(".formDesignerNav #formName","change",function(){
			editor.setValue("");
			$("#formDesignerEditor #formDesignerToolBtn").hide();
			$("#designerSourceEditor").hide();
			$("#formDesignerEditor .formDesignerEditor [data-current]").removeAttr("data-current");
			var that=this;
			$("#formDesignerEditor .formDesignerEditor[data-path]").remove();
			if ($(this).val()=="") {
				$("#sidebar #formList").html("");
			} else {
				$.get("/engine/ajax.php?mode=listModes&form=form&name="+$(this).val(),function(data){
					var data=$.parseJSON(data);
					var forms=data.app;
					var name=$(that).val();
					var i=0;
					$("#sidebar #formList").html("");
					$("#formDesigner #formDesignerEditor .formDesignerEditor").html("");
					$.each(forms,function(i,form){
						if (form.form==name && form.ext=="php" && form.mode>"") {
							var formId=form.form+'_'+form.mode;
							var formHref="#"+formId;
							$("#sidebar #formList").append(
								'<li><a data-toggle="tab">'+
								form.mode+
								'<!--i class="fa fa-remove"></i--></a></li>');
								$("#sidebar #formList > li:last a").attr("href",formHref);
							$("#formDesignerEditor").append('<div class="tab-pane formDesignerEditor"></div>');
							$("#formDesignerEditor > .formDesignerEditor:last").attr("id",formId);
							$("#formDesignerEditor > .formDesignerEditor:last").attr("data-path",form.uri);
							$.get("/engine/ajax.php?mode=getform&form=form&path="+form.uri,function(data){
								data=str_replace("data-strip-role","data-role",data);
								$("#formDesignerEditor #"+formId).html(data);
							});
						}
					});
					$("#sidebar #formList").append('<a class="btn btn-primary" href="javascript:formDesigner_AddMode();"><i class="fa fa-plus"></i></a>');
					$("#sidebar #formList").prev("a").addClass("open");
					$("#sidebar #formList > li:first a").addClass("active");
					$("#formDesignerEditor > .formDesignerEditor:first").addClass("active");
					formDesigner_Color();	
				});
			}
		});		
	}

	function formDesigner_Nav() {
		// TO PREV
		$(".formDesignerNav .toPrev").undelegate("a[href=#prev]","click");
		$(".formDesignerNav").delegate("a[href=#prev]","click",function(event){
			$("#formDesignerEditor #formDesignerToolBtn").hide();
			if ($(".formDesignerEditor.active [data-current]").parent().hasClass("formDesignerEditor")) {
				$(".formDesignerEditor.active [data-current]").removeAttr("data-current");
				$(".formDesignerNav .currentInfo strong").html('<i class="fa fa-home"></i> ROOT');			
				editor.setValue("");
			} else {
				if ($(".formDesignerEditor.active [data-current]").prev().length) {
					$(".formDesignerEditor.active [data-current]").prev().trigger("click");
				} else {
					$(".formDesignerEditor.active [data-current]").parent().trigger("click");
				}
			} 
			event.preventDefault(); return false;
		});
		// TO NEXT		
		$(".formDesignerNav .toNext").undelegate("a[href=#next]","click");
		$(".formDesignerNav").delegate("a[href=#next]","click",function(event){
			if ($(".formDesignerEditor.active [data-current]").children().length) {
				$(".formDesignerEditor.active [data-current]").children(":first").trigger("click");
			} else {
				if ($(".formDesignerEditor.active [data-current]").next().length) {
					$(".formDesignerEditor.active [data-current]").next().trigger("click");
				} else {
					if ($(".formDesignerNav .currentInfo .fa-home").length) {
						
						
					} else {
						var next=formDesigner_FindNext($(".formDesignerEditor.active [data-current]"));
						if (next.is(":visible")) {next.trigger("click");}
					}
				}
			}
			event.preventDefault(); return false;
		});
		$(".formDesignerNav").undelegate(".currentInfo","click");
		$(".formDesignerNav").delegate(" .currentInfo","click",function(event){
			if ($("#sidebar-tree").hasClass("visible")) {
				$("#sidebar-tree").removeClass("visible");
			} else {
				$("#sidebar-tree").addClass("visible");
			}
		});
			
	}
	
	function formDesigner_FindNext(that) {
		if (that.parent().next().length) {
			return that.parent().next();
		} else {
			return formDesigner_FindNext(that.parent());
		}
		
	}
	
	function formDesigner_formList() {
		$("#sidebar").undelegate("#formList li a");
		$("#sidebar").delegate("#formList li a","click",function(){
			var context=$("#formDesignerEditor .formDesignerEditor"+$(this).attr("href"));
			var tree=childs(context);
			setTimeout(function(){$("#sidebar #formList li").removeClass("active")},30);
			$("#sidebar #formList li a").removeClass("active");
			$(this).addClass("active");
			$("#props").html(tree);
			
			function childs(that) {
				var out=$("<ul></ul>");
				$(that).find(">").each(function(){
					$(out).append('<li>'+this.tagName+'</li>');
					if ($(this).attr("id")>"") {
						$(out).find("li:last").append("#"+$(this).attr("id"));
					}
					if ($(this).find(">").length) {
						$(out).find("li:last").append(childs($(this)));
					}
				});
				if ($(out).html()>"") {return out.prop('outerHTML');}
				return "";
			}
		});
	}

	function formDesigner_unsetElement() {
		$(document).undelegate("*","click");
		$(document).delegate("*","click",function(event){
			// здесь порылась собака с кнопкой back 
			if ((	!$(event.target).parents(".formDesignerEditor").length
				&&	!$(event.target).parents("#designerSourceEditor").length
				&&	!$(event.target).parents(".formDesignerNav").length
				&&	!$(event.target).parents("#sidebar").length
				&&	!$(event.target).parents(".popover").length
				) || (
					$(event.target).parents("#sidebar #formList").length
				)
			) {
				formDesigner_removePopovers();
				$(".formDesignerEditor.active [data-current]").replaceWith(editor.getValue()).attr("data-current","true");
				editor.setValue("");
				$("#formDesignerEditor #formDesignerToolBtn").hide();
				$("#designerSourceEditor").hide();
				$("#formDesignerEditor .formDesignerEditor [data-current]").removeAttr("contenteditable").removeAttr("data-current");
			}
			return formDesigner_TreeEvents(event);
		});
	}
	
	function formDesigner_TreeEvents(event) {
		if (	!$(event.target).parents("#sidebar-tree").length 
			&& 	!$(event.target).parents(".currentInfo").length
			) {
			$("#sidebar-tree").removeClass("visible");
		} else {
			if ($(event.target).is("a")) {
				$("#sidebar-tree").data("path","");
				var path=".formDesignerEditor.active > "+formDesigner_TreePath(event.target);
				formDesigner_clickElement(path);
				return false;
			}
			
		}
	}
	
	function formDesigner_TreePath(that) {
		if ($("#sidebar-tree").data("path")!==undefined) {
			var path=$("#sidebar-tree").data("path");
			var curr=$(that).parent("li").attr("data-tag");
			if (!$(that).parent("li").parent("ul").parent("ul#tagsTree").length) {
				var idx=$(that).parent("li").index();
				if(idx>0) {curr+=":nth-child("+idx+")";}
				if (path!=="") {$("#sidebar-tree").data("path",curr+" > "+path);} else {$("#sidebar-tree").data("path",curr);}
				formDesigner_TreePath($(that).parent("li").parent("ul").parent("li").find("a"));
			} else {
				if (path!=="") {
					$("#sidebar-tree").data("path",curr+" > "+path);
				} else {
					$("#sidebar-tree").data("path",curr);
				}
			}
		}
		return $("#sidebar-tree").data("path");
	}
	
	function formDesigner_removePopovers() {
		$(".popover").each(function(){
			$(document).find("[aria-describedby="+$(this).attr("id")+"]").popover("destroy");
		});
		
	}

	function formDesigner_saveForms() {
		$("#formDesignerHeader").undelegate("#formSave","click");
		$("#formDesignerHeader").delegate("#formSave","click",function(){
			$("#formDesignerEditor .formDesignerEditor[data-path]").each(function(){
				var uri=$(this).attr("data-path");
				var name=$(this).attr("id");
				var data={content:$(this).html()};
				$.ajax({
					async: 		true,
					url: "/engine/ajax.php?mode=putform&form=form&path="+uri,
					method: "post",
					data: data,
					success: function(data){
						growlMsg("Форма "+name+" сохранена");
					},
					error: function(){
						growlMsg("Ошибка сохранения формы!","warning");
						return false;
					}
				});

			});
			return false;
		});
	};

	function formDesigner_createForm() {
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
							$(".formDesignerNav #formName").append('<option>'+name+'</option>');
							$(".formDesignerNav #formName option:last").attr("value",name);
						} else {var type="warning";}
						growlMsg(data.status,type);
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
	}
