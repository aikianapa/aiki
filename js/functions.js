//stylesheet("/engine/tpl/css/sidebar.css");
stylesheet("/engine/tpl/css/admin.css");
stylesheet("/engine/tpl/css/gallery.css");
stylesheet("/engine/tpl/css/loader.css");

include("/engine/js/php.js");
include("/engine/js/md5.js");

include("/engine/js/jquery.maskedinput.min.js");
// uploader //
stylesheet("/engine/js/uploader/jquery.plupload.queue.css");

stylesheet("/engine/bootstrap/css/bootstrap-switch.min.css");
include("/engine/bootstrap/js/bootstrap-switch.min.js");

include("/engine/bootstrap/rating/bootstrap-rating.min.js");
stylesheet("/engine/bootstrap/rating/bootstrap-rating.css");


jQuery(document).ready(function(){
	ajax_tag_attr();
	active_navigation();
	active_pagination();
	active_imageviewer();
	active_multiinput();
	active_resize();
	active_cart();
	active_users();
	active_dropdown();
	form_check_id();
	com_dict_init();
	ajax_navigation();
	ajax_formsave();
	ajax_getid();
	ajax_sess_kick();
	engine_editor();
	active_plugins();
	active_login();
	active_hash();
	active_source_buttons();
	active_search();
});

document.onkeydown = function (event){ // Запрещаем BackSpace
	var tag = event.srcElement.tagName;

	if (event.keyCode == 8 && tag.toUpperCase() == "BODY") {event.preventDefault();}

    if(event.keyCode === 27) {
        if ($(".xdsoft_datetimepicker:visible").length) {
			$('.xdsoft_datetimepicker:visible').remove();
			event.preventDefault();
		}
	}
};

	$(document).on("add-to-cart-done",function(event,item){
		$.bootstrapGrowl("<center>Товар добавлен<br><a href='/cart.htm' class='btn btn-default btn-sm'><i class='fa fa-cart-plus'></i> Корзина покупок</a></center>", {
			ele: 'body',
			type: 'success',
			offset: {from: 'top', amount: 20},
			align: 'right',
			width: "auto",
			delay: 4000,
			allow_dismiss: true,
			stackup_spacing: 10 
		});
	});
	$(document).on("orders_after_formsave",function(event,name,item,form,res){
		var form=$.unserialize(form);
		setcookie("person_name",form.personname[0]);
		setcookie("person_phone",form.personphone[0]);
		setcookie("person_email",form.personemail[0]);
		setcookie("person_city",form.personcity[0]);
		setcookie("person_address",form.personaddress[0]);
		$("#modalOrder").modal("hide");
		$("[data-role=cart] .cart-table").hide();
		$("[data-role=cart] .cart-success").show();
		$(document).trigger("cart-clear");
	});

function include(url){
	document.write('<script src="'+ url + '" type="text/javascript" ></script>');
}

function include_aiki(url){
	document.write('<div data-role="include" src="'+ url + '" ></div>');
}

function stylesheet(url){
	document.write('<link rel="stylesheet" href="'+ url + '" type="text/css" media="all" />');
}

function ajax_readitem(form,item) {
	$.ajax({
		url: "/engine/ajax.php?mode=readitem&form="+form+"&id="+id,
		method: "get",
		success: function(data){
			return data;
		},
		error: function(){return false;}
	});
}

function form_check_id() {
	$(document).delegate("input[name=id]","change",function(){
		if ($(this).parents("form[item]").length) {
			var oldi=$(this).parents("form[item]").attr("item");
			$(this).parents("form[item]").attr("item",$(this).val());
			var form=$(this).parents("form[item]").attr("name");
			var item=$(this).parents("form[item]").attr("item");
			if (oldi>"" && $(this).parents("form[item]").attr("item-old")==undefined) {
				$(this).parents("form[item]").attr("item-old",oldi);
			}
		}
	});
}

function active_login() {
	if ($("#login-container").length) {
		$("#login-container a[href=#reminder]").click(function(){
			$("#login-container .login").hide();
			$("#login-container .reminder-error").hide();
			$("#login-container .reminder-success").hide();
			$("#login-container .reminder").hide().removeClass("hidden");
			$("#login-container .reminder").show();
			return false;
		});
		$("#login-container a[href=#login]").click(function(){
			$("#login-container .reminder-error").hide();
			$("#login-container .reminder-success").hide();
			$("#login-container .reminder").hide();
			$("#login-container .login").hide().removeClass("hidden");
			$("#login-container .login").show();
			return false;
		});

		$("#login-container .reminder .reminder-email").unbind("keyup");
		$("#login-container .reminder .reminder-email").on("keyup",function(){
			if (check_email($(this).val())) {
				$("#login-container .reminder .reminder-email").prev(".input-group-addon").addClass("btn-success").removeClass("btn-danger");
			} else {
				$("#login-container .reminder .reminder-email").prev(".input-group-addon").removeClass("btn-success").addClass("btn-danger");
			}
			$("#login-container .reminder").trigger("check-success");
		});
		$("#login-container .reminder .reminder-email").on("change",function(){
			$("#login-container .reminder .reminder-email").trigger("keyup");
		});

		$("#login-container .reminder .password-enter").unbind("keyup");
		$("#login-container .reminder .password-enter").on("keyup",function(){
			$("#login-container .reminder .password-check").attr("data","");
			if ($(this).val().length>5) {
					$("#login-container .reminder .password-enter").prev(".input-group-addon").addClass("btn-success").removeClass("btn-danger");
			} else {$("#login-container .reminder .password-enter").prev(".input-group-addon").addClass("btn-danger").removeClass("btn-success");}
			var md5 = CryptoJS.MD5($(this).val()).toString();
			$(this).attr("data",md5);
			$("#login-container .reminder").trigger("check-success");
		});
		$("#login-container .reminder .password-check").unbind("keyup");
		$("#login-container .reminder .password-check").on("keyup",function(){
			var pass=$("#login-container .reminder .password-enter").attr("data");
			var md5 = CryptoJS.MD5($(this).val()).toString();
			if (pass==md5) {
				$("#login-container .reminder #password").val(pass);
				$("#login-container .reminder .password-check").prev(".input-group-addon").addClass("btn-success").removeClass("btn-danger");
			} else  {
				$("#login-container .reminder .password-check").prev(".input-group-addon").addClass("btn-danger").removeClass("btn-success");
			}
			$("#login-container .reminder").trigger("check-success");
		});

		$("#login-container .reminder").on("check-success",function(){
			if ($("#login-container .reminder .input-group-addon.btn-success").length==3) {
				$("#login-container .reminder button").removeClass("btn-default").addClass("btn-success");
				$("#login-container .reminder button.btn-success").unbind("click");
				$("#login-container .reminder button.btn-success").on("click",function(){
					var formdata=$("#login-container #form-reminder").serialize();
					$.post("/engine/ajax.php?form=users&mode=pwdchange",formdata,function(data){
						if (data==true) {
							$("#login-container .reminder").hide();
							$("#login-container .reminder-success").hide().removeClass("hidden").show();
						} else {
							$("#login-container .reminder").hide();
							$("#login-container .reminder-error").hide().removeClass("hidden").show();
						}
					});
				});
			} else {
				$("#login-container .reminder button.btn-success").unbind("click");
				$("#login-container .reminder button").addClass("btn-default").removeClass("btn-success");
			}
			return false;
		});
	}
}

function active_plugins() {
	$("script").each(function(){
		if ($(this).attr("src")=="/engine/appUI/js/plugins.js") {
			$('input[type=datetimepicker],input[type=datepicker],.datepicker').datetimepicker("remove");
			$('input[type=datepicker],.datepicker').datetimepicker({
				lang:  'ru',
				format: 'd.m.Y',
				timepicker:false,
				mask:true
			});
			$('input[type=datetimepicker],.datetimepicker').datetimepicker({
				lang:  'ru',
				format: 'd.m.Y H:i',
				mask:true
			});			
		}
		if ($(this).attr("src")=="/engine/js/jquery.maskedinput.min.js") {
			if ($("input[type=phone]").length) {$("input[type=phone]").mask("+7 (999) 999-99-99");}
			if ($("input[type=tel]").length) {$("input[type=tel]").mask("+7 (999) 999-99-99");}
			if ($("input[data-mask]").length) {
				$("input[data-mask]").each(function(){
					$(this).attr("type","text");
					$(this).mask($(this).attr("data-mask"));
				});
			}			
		}
		if ($(this).attr("src")=="/engine/bootstrap/rating/bootstrap-rating.min.js" && $("input[name=rating]").length) {
			$("input[name=rating]").rating();
		}
		if ($(this).attr("src")=="/engine/bootstrap/js/bootstrap-switch.min.js" && $(".bs-switch").length) {
			$(".bs-switch").bootstrapSwitch();
		}
	});
	
	if ($('.input-tags').length) {
		$('.input-tags').tagsInput({ width: 'auto', height: 'auto',  'defaultText':'новый'});
	}
}

function active_dropdown() {
	$(document).delegate("[data-role=foreach] [idx]","contextmenu",function(){
		if ($(this).find("[data-toggle=dropdown]:first").length) {
			$(this).find("[data-toggle=dropdown]:first").trigger("click");
			return false;
		}
	});

	$(document).delegate(".main [item]","dblclick",function(){
		if ($(this).find(".dropdown-menu li a[data-target$=Edit]").length) {
			$(this).find(".dropdown-menu li a[data-target$=Edit]:first").trigger("click");
			return false;
		}
	});
}

function active_hash_pagination() {
	var hash=window.location.hash;
	var hash=explode("-",hash);
	var p=$(".pagination[data-idx="+hash[1]+"]");
	//if (hash[0]=="#page" && p.attr("data-cache") == "" ) {
	if (hash[0]=="#page") {
		p.find("a[data$=-"+hash[2]+"]" ).trigger("click");
	}		
}


function active_hash() {
	$(window).bind( 'hashchange', function(e) {
		active_hash_pagination();
	});
	active_hash_pagination();
}


function active_users() {
	$(document).on("data-ajax-done",function(event,target,src){
		if (src="mode=reg&form=users") {
			$(document).find(".user-reg-submit").parents(".modal-dialog").find(".modal-title").html("Регистрация");
		}
	});

	$(document).unbind("user-reg-submit");
	$(".user-reg-submit").unbind("click");
	$(document).delegate(".user-reg-submit","click",function(){
		$(document).trigger("user-reg-submit",[$(this).parents("form")]);
	});

	$(document).on("user-reg-submit",function(event,form) {
		if (check_required(form)) {
			$(this).find(".user-reg-submit").attr("disabled",true);
			var formdata=$(form).serialize();
			var ajax="/engine/ajax.php?mode=reg&form=users&action=submit";
			$.post(ajax,formdata,function(data){
				$(document).trigger("user-reg-submit-done",[form,data]);
			});
		}
		return false;
	});
}

	$(document).on("user-reg-submit-done",function(event,form,data){
		$(form).html(data);
	});

	$(".password-check").unbind("change");
	$(document).delegate(".password-check","change",function(){
		var pass1=$(this).parents("form").find(".password-enter").val();
		var pass2=$(this).val();
		if (pass1!=pass2) {
			var that=this;
			setTimeout(function(){$(that).val("");},100);
			$(this).trigger("check_required",[this,"Не совпадает с паролем!"]);
		}
	});


function check_email(email) {
		if (email.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i)) {
			return true; } else {return false;}
}

function check_required(form) {
	var res=true;
	var idx=0;
	form.find("input,select,textarea").each(function(i){
	if ($(this).is("[required]:not([disabled],[type=checkbox]):visible")) {
			if ($(this).val()=="") {res=false; idx++; $(this).data("idx",idx); $(document).trigger("check_required_false",[this]);}
			else {
				if ($(this).attr("type")=="email" && !check_email($(this).val())) {
					res=false; idx++; 
					$(this).data("idx",idx); 
					$(this).data("error","Введите корректный email");
					$(document).trigger("check_required_false",[this]);
				} else {$(document).trigger("check_required_true",[this]);}
			}
		}
	});
	if (res==true) {$(document).trigger("check_required_success",[form]);}
	if (res==false) {$(document).trigger("check_required_danger",[form]);}
	return res;
}

$(document).unbind("check_required_false");
$(document).on("check_required_false",function(event,that,text) {
	var delay=(4000+$(that).data("idx")*250)*1;
	var text=$(that).data("error");
	if (!text>"") {
		text="Заполните поле: "+$(that).attr("name");
		if ($(that).parents(".form-group").find("label").text()>"") {
			text="Заполните поле: "+$(that).parents(".form-group").find("label").text();
		}
		if ($(that).attr("placeholder")>"") {text="Заполните поле: "+$(that).attr("placeholder");}
	}

	$.bootstrapGrowl(text, {
		ele: 'body',
		type: 'warning',
		offset: {from: 'top', amount: 20},
		align: 'right',
		width: "auto",
		delay: delay,
		allow_dismiss: true,
		stackup_spacing: 10
	});
	
});

function active_cart() {
	$(document).unbind("cart-recalc");
	$(document).unbind("cart-clear");
	$(document).unbind("cart-item-recalc");
	$(document).unbind("cart-item-plus");
	$(document).unbind("cart-item-minus");
	$(document).unbind("cart-item-remove");
	$(document).unbind("cart-total-recalc");
	$("[data-role=cart]").find("input,select,textarea").unbind("change");
	$("[data-role=cart] .cart-item").find("*").unbind("click");
	$("[data-role=cart] .cart-clear").unbind("click");
	$("[data-role=cart] .add-to-art").unbind("click");

	$(document).undelegate("form[data-role=cart] .add-to-cart","click");
	$(document).delegate("form[data-role=cart] .add-to-cart","click",function(){
		$(this).trigger("add-to-cart-click");
		var that=$(this);
		var form=$(this).parents("form[data-role=cart]").serialize();
		var ajax=$(this).parents("form[data-role=cart]").attr("data-ajax");
		if (ajax==undefined || ajax=="") {var ajax="/engine/ajax.php?mode=cart";}
		if ($(this).hasClass("add-to-cart")) {ajax+="&action=add-to-cart";}
		$.get(ajax,form,function(data){
			that.trigger("add-to-cart-done",[getcookie("order_id")]);
			content_set_data("[data-role=cart][data-template]").done(function(){
				$(document).trigger("cart-total-recalc");
			});
		});
		return false;
	});

	$(document).undelegate(".cart-clear","click");
	$(document).delegate(".cart-clear","click",function(){
		$(this).trigger("cart-clear",[this]);
		return false;
	});

	$(document).on("cart-recalc",function(event,flag) {
		$("[data-role=cart] .cart-item").each(function(){
			$(this).trigger("cart-item-recalc",[this,flag]);
		});
		$(document).trigger("cart-total-recalc");
	});
	
	$(document).on("cart-clear",function(event) {
		var ajax="/engine/ajax.php?mode=cart&action=cart-clear";
		$.get(ajax,function(data){
			$(document).trigger("cart-after-clear",[event]);
			$("[data-role=cart] .cart-item").remove();
			$(document).trigger("cart-total-recalc");
			content_set_data("[data-role=cart][data-template]");
		});
	});	
	
	$(document).on("cart-item-recalc",function(event,item,flag) {
		var index=1;
		var idx=$(item).attr("idx");
		var fld=$(this).parents("[data-role=cart]").attr("data-update");
		if (fld==undefined || fld=="") {var fld = new Array ("quant","price");} else {
			var fld=explode(",",fld);
		}
		var form="index="+$(item).attr("idx");
		for (var i in fld) {
			var fldname=(fld[i]).trim();
			var field=$(item).find(".cart-item-"+fldname);
			if (field.is("input") || field.is("select")) {
				var value=field.val()*1;
			} else {
				var value=field.text()*1;
			}
			index=index*value;
			form+="&"+fldname+"="+value;
		};
		var ajax=$(this).parents("[data-role=cart]").attr("data-ajax");
		if (ajax==undefined || ajax=="") {var ajax="/engine/ajax.php?mode=cart";}
		ajax+="&action=cart-item-recalc";
		if (flag!="noajax") {$.get(ajax,form);}
		$("[data-role=cart] .cart-item[idx="+idx+"] .cart-item-total").html(index);
	});

	$(document).on("cart-item-remove",function(event,item,flag) {
		var idx=$(item).attr("idx");
		var ajax=$(this).parents("[data-role=cart]:not(form):first").attr("data-ajax");
		if (ajax==undefined || ajax=="") {var ajax="/engine/ajax.php?mode=cart";}
		form="action=cart-item-remove&index="+idx;
		if (flag!=="noajax") {var diff=$.get(ajax,form);}
		$("[data-role=cart] .cart-item[idx="+idx+"]").remove();
		$("[data-role=cart]").each(function(){
			 $(this).find(".cart-item").each(function(i){
				$(this).attr("idx",i);
			});
		});
		diff.done(function(){
			$(document).trigger("cart-total-recalc");
		});
	});

	$(document).on("cart-item-plus",function(event,item,flag) {
		var idx=$(item).attr("idx");
		var quants=$("[data-role=cart] .cart-item[idx="+idx+"] .cart-item-quant");
		var max=1000;
		var ajax=$(this).parents("[data-role=cart]").attr("data-ajax");
		if (ajax==undefined || ajax=="") {var ajax="/engine/ajax.php?mode=cart";}
		form="action=cart-item-plus&index="+idx;
		//if (flag!="noajax") {$.get(ajax,form);}
		quants.each(function(){
			if ($(this).is("input") || $(this).is("select")) {
				if ($(this).val()<max) {$(this).val($(this).val()*1+1);}
			} else {
				if ($(this).text()*1<max) {$(this).html($(this).text()*1+1);}
			}
		});
		$(document).trigger("cart-item-recalc",item);
		$(document).trigger("cart-total-recalc");
	});

	$(document).on("cart-item-minus",function(event,item,flag) {
		var idx=$(item).attr("idx");
		var quants=$("[data-role=cart] .cart-item[idx="+idx+"] .cart-item-quant");
		var ajax=$(this).parents("[data-role=cart]").attr("data-ajax");
		var min=1;
		if (ajax==undefined || ajax=="") {var ajax="/engine/ajax.php?mode=cart";}
		form="action=cart-item-plus&index="+idx;
		//if (flag!="noajax") {$.get(ajax,form);}
		quants.each(function(){
			if ($(this).is("input") || $(this).is("select")) {
				if ($(this).val()>min) {$(this).val($(this).val()*1-1);}
			} else {
				if ($(this).text()*1>min) {$(this).html($(this).text()*1-1);}
			}
		});
		$(document).trigger("cart-item-recalc",item);
		$(document).trigger("cart-total-recalc");
	});


	$(document).on("cart-total-recalc",function(event,item,flag) {
		var total=0;
		var lines=0;
		$("[data-role=cart]:not(form):first .cart-item").each(function(){
			$(document).trigger("cart-item-recalc",$(this));
			total=total+$(this).find(".cart-item-total").text()*1;
			lines++;
		});
		$("[data-role=cart] .cart-total").text(total);
		$("[data-role=cart] .cart-lines").text(lines);
		$(document).trigger("cart-update-done");
	});

	$("[data-role=cart]").find("input,select,textarea").on("change",function(){
		var item=$(this).parents(".cart-item");
		$(document).trigger("cart-item-recalc",item);
		$(document).trigger("cart-total-recalc");
	});

	$(document).undelegate("[data-role=cart] .cart-item *","click");
	$(document).delegate("[data-role=cart] .cart-item *","click",function(){
		var item=$(this).parents(".cart-item");
		if ($(this).hasClass("cart-item-remove")) {$(document).trigger("cart-item-remove",item);}
		if ($(this).hasClass("cart-item-plus")) {$(document).trigger("cart-item-plus",item);}
		if ($(this).hasClass("cart-item-minus")) {$(document).trigger("cart-item-minus",item);}
	});

	$(document).trigger("cart-recalc",["noajax"]);
};

function aikiCallEditor() {
	if ($("textarea.editor:not(.loaded)").length) {
		$("textarea.editor:not(.loaded)").each(function(){
			
			if ($(this).attr("id")==undefined || $(this).attr("id")=="") {$(this).attr("id",JSON.parse(ajax_getid()));}
			
			var editor = $(this).ckeditor();
			$(this).addClass("loaded");
			   
			CKEDITOR.config.toolbarGroups = [
				{ name: 'document',    groups: [ 'document', 'doctools' ] },
			//    { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
			//	{ name: 'mode' },
				{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
				{ name: 'links' },
				{ name: 'insert' },
				{ name: 'others' },
				'/',
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
				{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
				{ name: 'colors' },
				 { name: 'tools' }
			];
			CKEDITOR.config.skin = 'bootstrapck';
			CKEDITOR.config.allowedContent = true;
			CKEDITOR.config.forceEnterMode = true;
			CKEDITOR.plugins.registered['save']=
			{
			   init : function( editor )
			   {
				  var command = editor.addCommand( 'save',
					 {
						modes : { wysiwyg:1, source:1 },
						exec : function( editor ) {
						   var fo=editor.element.$.form;
						   editor.updateElement();
						   aiki_formsave($(fo));
						}
					 }
				  );
				  editor.ui.addButton( 'Save',{label : 'Сохранить',command : 'save'});
			   }
			}
		});
			CKEDITOR.on('instanceReady', function(){
			   $.each( CKEDITOR.instances, function(instance) {
				CKEDITOR.instances[instance].on("change", function(e) {
					for ( instance in CKEDITOR.instances )
					$("textarea#"+instance).html(CKEDITOR.instances[instance].getData());
					$("textarea#"+instance).trigger("change");
				});
			   });
			});
	}
}

function aikiCallSourceEditor() {
    var editor = ace.edit("sourceEditor");
    editor.setTheme("ace/theme/chrome");
	editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true
	});
	editor.getSession().setUseWrapMode(true);
	editor.getSession().setUseSoftTabs(true);
	editor.setDisplayIndentGuides(true);
	editor.setHighlightActiveLine(false);
	editor.setAutoScrollEditorIntoView(true);
	editor.commands.addCommand({
		name: 'save',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			$("#sourceEditorToolbar .btnSave").trigger("click");
		},
		readOnly: false
	});
	editor.gotoLine(0,0);
	editor.resize(true);
	return editor;
}

function active_resize() {
	$(window).unbind('resize');
	$(window).bind('resize', function () {
		multiinput_resize();
		active_source_resize();
		active_tree_resize();
		console.log("Trigger: window resize");
	});
	$(window).trigger("resize");
}


function active_source_resize() {
		if ($("#sourceList .sourcePanels .panel").length) {
			$("#sourceList .sourcePanels .panel").height($("#page-container").height()-$("#sourceList .sourceButtons").height()-$("#sourceList .sourcePanels .panel").offset().top);
		}	
}

function active_tree_resize() {
		if ($("#treeEditForm .dd-item.active").length) {
			var offset=	$("#treeEditForm .dd-item.active").width()-$("#treeEditForm .dd-btn").width()-4;
			var width=	offset-$("#treeEditForm .dd-item.active .dd-handle").width()-40;

			$("#treeEditForm .dd-item.active .dd-btn").css({"margin-left":offset+"px"});
			$("#treeEditForm .dd-item.active input").css({"width":width+"px"});
		}
}


function active_source_buttons() {
	$(document).undelegate("#sourceEditorToolbar button","click");
	$(document).delegate("#sourceEditorToolbar button","click",function(e){
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
		if ($(this).hasClass("btnFullScr")) 	{
			var div=$(this).parents("#sourceEditorToolbar").parent();
			var offset=div.offset();
			if (!div.hasClass("fullscr")) {
				div.parents(".modal").addClass("fullscr");
				div.addClass("fullscr");
				$(this).parents(".modal").css("overflow-y","hidden");
				div.find("pre.ace_editor").css("height",$(window).height()-$("#sourceEditorToolbar").height()-$("#sourceEditorToolbar").next(".nav").height()-15);
			} else {
				div.removeAttr("style");
				div.find("pre.ace_editor").css("height","500px");
				div.removeClass("fullscr");
				div.parents(".modal").removeClass("fullscr");
				$(this).parents(".modal").css("overflow-y","auto");
			}
			window.dispatchEvent(new Event('resize'));
		}
		if ($(this).hasClass("btnSave")) 	{
			var fo=$(this).parents("#sourceEditorToolbar").parents("form");
			aiki_formsave($(fo));
		}
		e.preventDefault();
	});	
}


function active_search() {

	jQuery.expr[":"].contains = function( elem, i, match, array ) {
		return (elem.textContent || elem.innerText || jQuery.text( elem ) || "").toLowerCase().indexOf(match[3].toLowerCase()) >= 0;
	}
	$(document).undelegate("[data-role=search]","keydown");
	$(document).delegate("[data-role=search]","keydown",function(e) {
		if (e.keyCode == 13) {return false;}		
	});

	$(document).undelegate("[data-role=search]","keyup");
	$(document).delegate("[data-role=search]","keyup",function(e) {
		if (e.keyCode == 13 || e.isTrigger==3) {
			var str=$(this).val();
			var from=$(this).attr("from");

			if ($(this).data("tpl")!==undefined && $("[data-template="+$(this).data("tpl")+"]").length) {var tpl=$(this).data("tpl");} else {
				var tpl=$(document).find(from).parents("[data-template]").attr("data-template");			
				$(this).data("tpl",tpl);
			}
			if ($("#ajax-"+tpl+".pagination").attr("data-cache")>"") {var type="ajax";} else {var type="js";}
			if ($("#ajax-"+tpl+".pagination li a[data^=ajax-]").length) {type="ajax";}
			if ($("[data-template="+tpl+"]").attr("data-find-type")>"") {type=$("[data-template="+tpl+"]").attr("data-find-type");}

			if (type=="js") {
				$(document).find(from).show();
				$(document).find(from).each(function(){
					if ($(this).find("*:visible:contains("+str+")").length) {
						$(this).show();
					} else {$(this).hide();}
				});
				$(document).find("[data-template="+tpl+"]").attr("data-find-type","js");
			} else {
				$("#ajax-"+tpl).data("find",str);
				$("[data-template="+tpl+"]").attr("data-find-type","ajax");
				$("#ajax-"+tpl+".pagination li:first a").trigger("click");
			}
			e.preventDefault();
			return false;
		}
	});
}

function active_multiinput() {
	$("[data-role=multiinput] > .row").unbind("contextmenu");
	$(document).delegate("[data-role=multiinput] > .multi-fld-row","contextmenu",function(e) {
		$(this).parents("[data-role=multiinput]").find(".multi-fld-row .multimenu").remove();
		$(this).trigger("mouseenter");

		$(this).find(".multimenu .dropdown-toggle").trigger("click");
		e.preventDefault();
	});


	$(document).delegate("[data-role=multiinput] > .multi-fld-row","mouseenter",function(e) {
		if (!$(this).parents("[data-role=multiinput]").find(".multi-fld-row .multimenu").hasClass("open")) {
			$(document).find("[data-role=multiinput] .multimenu").remove();
			multiinputFldNum($(this).parent("[data-role=multiinput]"));
			$(this).addClass("bg-warning");
			$(this).append('<div class="multimenu">'+
			//'<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Действия с полем</button>'+
			'<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+
			'<span class="glyphicon glyphicon-list"></span></a>'+
			'<ul class="dropdown-menu" role="menu">'+
			'<li><a href="#add-up">Добавить сверху</a></li>'+
			'<li><a href="#add-dn">Добавить снизу</a></li>'+
			'<li class="divider"></li>'+
			'<li><a href="#clear">Очистить содержимое</a></li>'+
			'<li><a href="#delete">Удалить поле</a></li>'+
			'</ul></div>');
			$(this).find('.multimenu').css("margin-top",$(this).height()+"px");
			$(this).find('.multimenu').css("margin-left","-9px");
			$("[data-role=multiinput]").sortable();
		}
		e.preventDefault();
	});

	$(document).delegate("[data-role=multiinput] input","focusout",function(e) {
		$(this).attr("value",$(this).val());
	});

	$(document).delegate("[data-role=multiinput] > .multi-fld-row","mouseleave",function(e) {
		if (!$(this).parents("[data-role=multiinput]").find(".multi-fld-row .multimenu").hasClass("open")) {
			$(this).removeClass("bg-warning");
			$(this).find('.multimenu').remove();
			multiinputFldNum($(this).parent("[data-role=multiinput]"));
		}
		e.preventDefault();
	});

	$(document).delegate("[data-role=multiinput] > .multi-fld-row .multimenu .dropdown-menu a","click",function(e) {
		var multi=$(this).parents("[data-role=multiinput]");
		var tplid=multi.attr("data-tpl");
		var tpl=urldecode(multi.find(tplid).html());
		if ($(this).attr("href")=="#add-up") { // добавление сверху
			$(this).parents(".multimenu").parents(".multi-fld-row").before(tpl);
		}
		if ($(this).attr("href")=="#add-dn") { // добавление снизу
			$(this).parents(".multimenu").parents(".multi-fld-row").after(tpl);
		}
		if ($(this).attr("href")=="#delete") { // удаление мультиполя
			$(this).parents(".multimenu").parents(".multi-fld-row").remove();
			return false;
		}
		if ($(this).attr("href")=="#clear") { // очистка мультиполя
			$(this).parents(".multimenu").parents(".multi-fld-row").before(tpl);
			$(this).parents(".multimenu:first").parents(".multi-fld-row").remove();
		}
		$(this).parents(".multi-fld-row").find(".dropdown-toggle").trigger("click");
		multiinput_resize();
		return false;
	});
	active_multiinput_flds()
}

function active_multiinput_flds() {
	$("[data-role=multiinput]").each(function(){
		multiinputFldNum($(this));
	});

	$("*[data-role=multiinput] select").each(function(){
		var val=$(this).attr("value");
		$(this).find("option").each(function(){ if ($(this).val()==val) {$(this).prop("selected","selected");} });
	});

}

function multiinputFldNum(multi) {
	var name=multi.attr("name");
	multi.find(".multi-fld-row").each(function(i){
		var idx=i;
		$(this).find("[name]").each(function(){
			var iname=name+"["+idx+"]["+$(this).attr("data-name")+"]";
			if ($(this).is("select[multiple]")) {iname=iname+"[]";}
			$(this).attr("name",iname);
		});
	});
}

function multiinput_resize() {
	active_plugins();
	$(document).find("[data-role=multiinput]").each(function(){
		$(this).find(".form-group").each(function(){
			if ($(this).css("display")=="block") {$(this).removeClass("multi-compact");} else {
				$(this).addClass("multi-compact");}
		});
	});
}

function active_imageviewer() {
	if ($("script[src*=photoswipe]").length && $("a[href$='.jpeg'],a[href$='.jpg'],a[href$='.png'],a[href$='.gif']").length) {
	var myPhotoSwipe = $("a[href$='.jpeg'],a[href$='.jpg'],a[href$='.png'],a[href$='.gif']").photoSwipe({
		allowUserZoom: true,
		captionAndToolbarFlipPosition: true,
		captionAndToolbarAutoHideDelay: 0,
		backButtonHideEnabled: false,
		jQueryMobile: false
		});
	}
}

function engine_editor() {
	$(document).delegate('.call-editor',"click", function () {
		//var editor = $("textarea.editor").ckeditor();
		aikiCallEditor();
/*
		$(".engine_editor").raptor({
		autoEnable: true,
		enableUi: true,
		unloadWarning: false,
		unsavedEditWarning: false,
        docked: true,
        dockToElement: true,
        plugins: {
			logo: false,
			save: false,
			cancel: false,
			statistics: false,
			fontFamilyMenu: false,
			languageMenu: false
		}
		});
*/
	});

	$(document).delegate('.call-imgloader',"click", function () {
		var form=$(this).parents("form").attr("name");
		var item=$(this).parents("form").attr("item");
		//$(this).parents("form").find("[data-role=imageloader]").attr("path","/uploads/"+form+"/"+item);
		commonImageUpload();
	});
}


function active_navigation() {
	$(document).delegate(".nav li a","click",function(){
			$(this).parents(".nav").find("li").removeClass("active");
			$(this).parent("li").addClass("active");
			$(this).removeClass(":focus");

	});
}


function ajax_tag_attr() {
	$.each($(document).find("[data-role=ajax]"),function(){
		if (!$(this).parents("textarea").length) {
			var src=$(this).attr("src");
			var that=$(this);
			that.html('<h3>Подождите, идёт загрузка...</h3><div class="progress progress-striped"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">Загрузка...</span></div></div>');
			setInterval(function(){
				if (that.find(".progress-bar").length) {
					var value=that.find(".progress-bar").attr("aria-valuenow");
					if (value==100) {value=-10;}
					value=value*1+10;
					that.find(".progress-bar").css("width",value + "%");
					that.find(".progress-bar").attr("aria-valuenow",value);
				}
			},500);
			$.get(src,function(data){
				that.html(data);
				that.html(that.children("div").html());
				active_navigation();
				active_pagination();
				active_imageviewer();
				active_resize();
			});
		}
	});
}


function active_pagination(pid) {
	if (pid==undefined) {var slr=".pagination";} else {var slr=".pagination[id="+pid+"]";}
	$.each($(document).find(slr),function(idx){
		var id=$(this).attr("id");
		if ($(this).is(":not([data-idx])")) {$(this).attr("data-idx",idx);}
		$("thead[data='"+id+"']").attr("data-idx",idx);
		$("thead[data='"+id+"'] th[data-sort]").each(function(){
			var desc=$(this).data("desc");
			if (desc==undefined || desc=="") {$(this).prepend("<i class='aiki-sort fa fa-arrows-v pull-left'></i>");}
			if (desc==undefined || desc=="" || desc=="false") {$(this).attr("data-desc","false");} else {$(this).attr("data-desc","true");}
			$(this).data("desc",$(this).attr("data-desc"));
		});
		
		$("[data-page^="+id+"]").hide().removeClass("hidden");
		$("[data-page="+id+"-1]").show();
		$(document).undelegate(".pagination[id="+id+"] li a, thead[data="+id+"] th[data-sort]","click");
		$(document).delegate(".pagination[id="+id+"] li a, thead[data="+id+"] th[data-sort]","click",function(event){
			if (!$(this).is("a") || !$(this).parent().hasClass("active")) { // отсекает дубль вызова ajax, но не работает trigger в поиске
			console.log("active_pagination(): Click");
			var that=$(this);
			if ($(this).is("th[data-sort]")) {
				var $source=$(this).parents("thead");
				var page=$source.attr("data")+"-"+$(".pagination[id="+id+"] .active").attr("data-page");
				var sort=$(this).attr("data-sort");
				var desc=$(this).attr("data-desc");
				$(this).parents("thead").find("th[data-desc] .aiki-sort").remove();
				$(this).parents("thead").find("th[data-desc]").each(function(){
					$(this).data("desc","");
					$(this).removeAttr("data-desc");
				});
				if (desc=="true") {
					$(this).prepend("<i class='aiki-sort fa fa-long-arrow-up pull-left'></i>");
					$(this).data("desc","false");
				} else {
					$(this).prepend("<i class='aiki-sort fa fa-long-arrow-down pull-left'></i>");
					$(this).data("desc","true");
				}
			} else {
				var $source=$(this).parents(".pagination");
				var page=$(this).attr("data");
				var sort=null;	
				var desc=null;
			}
			if (substr(page,0,4)=="page") {
				// js пагинация
				$("[data-page^="+id+"]").hide();
				$("[data-page="+page+"]").show();
			} else {
				var cache=$source.attr("data-cache");
				var size=$source.attr("data-size");
				var idx=$source.attr("data-idx");
				var arr=explode("-",page);
				var tpl=$("#"+arr[1]).html();
				var foreach=$('<div>').append($("[data-template="+arr[1]+"]").clone());
				$(foreach).find("[data-template="+arr[1]+"]").html("");
				$(foreach).find("[data-template="+arr[1]+"]").attr("data-sort",sort);
				$(foreach).find("[data-template="+arr[1]+"]").attr("data-desc",desc);
				var foreach=$(foreach).html();
				var param={tpl:tpl,tplid:arr[1],idx:idx,page:arr[2],size:size,cache:cache,foreach:foreach};
				var url="/engine/ajax.php?mode=pagination";
				if ($("#"+id).data("find")!==undefined) {var find=$("#"+id).data("find");} else {
					var find=$source.attr("data-find");		
				}
				if (find>"") {find=urldecode(find);}
				param.find=find;
				param.sort=sort;
				param.desc=desc;
				$("[data-template="+arr[1]+"]").html(ajax_loader());
				$("body").addClass("cursor-wait");
				$.ajax({
					async: 		true,
					type:		'POST',
					data:		param,
					url: 		url,
					success: 	function(data){
									var data=JSON.parse(data);
									$("[data-template="+arr[1]+"]").html(data.data);
									if (data.pages>"1") {
										$(".pagination[id=ajax-"+pid+"]").show();
										var pid=$(data.pagr).attr("id");
										$(document).undelegate(".pagination[id="+pid+"] li a","click");
										$("#"+pid).after(data.pagr);
										$("#"+pid+":first").remove();
									} else {
										$(".pagination[id=ajax-"+arr[1]+"]").hide();
									}
									window.location.hash="page-"+idx+"-"+arr[2];
									active_plugins();
									active_pagination();
									console.log("active_pagination(): trigger:after-pagination-done");
									$(document).trigger("after-pagination-done",[id,page,arr[2]]);
									$("body").removeClass("cursor-wait");
								},
					error:		function(data){$("body").removeClass("cursor-wait");}
				});
			}
				$(this).parents("ul").find("li").removeClass("active");
				$(this).parent("li").addClass("active");

				var scrollTop=$("[data-template="+arr[1]+"]").offset().top-100;
				if (scrollTop<0) {scrollTop=0;}
				$('body,html').animate({scrollTop: scrollTop}, 1000);
				
				//$(document).trigger("after_pagination_click",[id,page,arr[2]]);
				event.preventDefault();
		}
		});
	});
}

function template_set_data(selector,data,ret) {
	var tpl_id=$(selector).attr("data-template");
	if (tpl_id!==undefined) {var html= urldecode($("#"+tpl_id).html());}
	if (data==undefined) {var data={};}
	if (selector==undefined) {var selector="body";}
		var param={html:html,data:data};
		var url="/engine/ajax.php?mode=content_set_data";
		$.ajax({
			async: 		false,
			type:		'POST',
			data:		param,
			url: 		url,
			success: 	function(data){
				if (ret==undefined) {
					$(selector).after(data).remove();
				} else {return data;}
			}
		});	
}

function content_set_data(selector,data,ret) {
	var tpl_id=$(selector).attr("data-template");
	$(selector).removeClass("loaded");
	if (tpl_id!==undefined) {$(selector).html(urldecode($("#"+tpl_id).html()));}
	$(selector).wrap("<div>");
	var html=$(selector).parent().html();
	if (data==undefined) {var data={};}
	if (selector==undefined) {var selector="body";}
		var param={html:html,data:data};
		var url="/engine/ajax.php?mode=content_set_data";
		var diff=$.ajax({
			async: 		false,
			type:		'POST',
			data:		param,
			url: 		url,
			success: 	function(data){
				if (ret==undefined) {
					$(selector).after(data).remove();
				} else {return data;}
			}
		});
		return diff;
}

function com_tree_init() {
	$("#treeEditForm #treeData").undelegate("input[data-tree]","change");
	$("#treeEditForm #treeData").delegate("input[data-tree]","change",function(){
		var did=$("#treeEditForm #treeData input[data-tree=id]").val();
		var txt=$("#treeEditForm #treeData input[data-tree=name]").val();
		$("#treeEditForm .dd-list .dd-item.active").attr("data-id",did);
		$("#treeEditForm .dd-list .dd-item.active .dd3-content").html(txt);
		var data=com_tree_serialize();
		$("#treeEditForm input[name=tree]").val(JSON.stringify(data));
	});

	$("#treeEditForm div[name=data]").undelegate("input","change");
	$("#treeEditForm div[name=data]").delegate("input","change",function(){
		var did=$("#treeEditForm .dd-list .dd-item.active").attr("data-id");
		if (did!==undefined) {
			var data=com_tree_data_serialize();
			$("#treeEditForm .dd-item[data-id="+did+"]").attr("data-data",JSON.stringify(data));
			var data=com_tree_serialize();
			$("#treeEditForm input[name=tree]").val(JSON.stringify(data));
		}
	});

	$("#treeEditForm div[name=data]").undelegate("select","change");
	$("#treeEditForm div[name=data]").delegate("select","change",function(){
		var did=$("#treeEditForm .dd-list .dd-item.active").attr("data-id");
		if (did!==undefined) {
			var data=com_tree_data_serialize();
			$("#treeEditForm .dd-item[data-id="+did+"]").attr("data-data",JSON.stringify(data));
			var data=com_tree_serialize();
			$("#treeEditForm input[name=tree]").val(JSON.stringify(data));
		}
	});
	
	
	$("#treeEditForm div[name=data]").undelegate("textarea","change");
	$("#treeEditForm div[name=data]").delegate("textarea","change",function(){
		var did=$("#treeEditForm .dd-list .dd-item.active").attr("data-id");
		if (did!==undefined) {
			var data=com_tree_data_serialize();
			
			if ($(this).hasClass("editor")) {
				var name=$(this).attr("data-name");
				data[name]=str_replace('&quot;','"',data[name]);
			}
			
			$("#treeEditForm .dd-item[data-id="+did+"]").attr("data-data",JSON.stringify(data));
			var data=com_tree_serialize();
			$("#treeEditForm input[name=tree]").val(JSON.stringify(data));
		}
	});

	$(document).undelegate("#treeEditForm a[href=#treeData]","click");
	$(document).delegate("#treeEditForm a[href=#treeData]","click",function(){
		var dict=$("#treeEditForm");
		var data=$("#treeEditForm #treeData div[name=data]");
		var flds=dict.find("div[name=fields]");
		data.html("<div class='form-data'></div>");
		flds.find(".row").each(function(){
			var fldname=$(this).find("input[data-name=fldname]").val();
			var fldlabel=$(this).find("input[data-name=fldlabel]").val();
			var fldtype=$(this).find("select[data-name=fldtype]").val();
			var flddescr=$(this).find("input[data-name=flddescr]").val();
			var fld="";
			if (fldlabel=="") {fldlabel=fldname;}
			if (fldtype=="text") {fld='<textarea data-name="'+fldname+'" rows="3" placeholder="'+fldlabel+'" class="form-control" data-descr="'+flddescr+'"></textarea>';}
			if (fldtype=="editor") {fld='<textarea data-name="'+fldname+'" rows="3" placeholder="'+fldlabel+'" class="form-control editor" data-descr="'+flddescr+'"></textarea>';}
			if (fldtype=="multiinput") {fld=com_multiinp_gen(fldname,fldlabel);}
			if (fldtype=="image") {fld=com_tree_imagesel(fldname,fldlabel);}
			if (fldtype=="" || fldtype=="string") {fldtype="text";}
			if (fld=="") {fld='<input type="'+fldtype+'" data-name="'+fldname+'" placeholder="'+fldlabel+'" class="form-control" data-descr="'+flddescr+'" />';}

			data.find(".form-data:last").append(''+
			'<label class="control-label col-sm-3">'+fldlabel+'</label>'+
			'<div class="col-sm-9">'+fld+'</div>');
		});
		var did=$("#treeEditForm .dd-list .dd-item.active").attr("data-id");
		var txt=$("#treeEditForm .dd-list .dd-item.active .dd3-content").html();
		if (did!==undefined) {
			var items=$.parseJSON($("#treeEditForm input[name=tree]").val());
			$("#treeEditForm #treeData [data-tree=id]").val(did);
			$("#treeEditForm #treeData [data-tree=name]").val(txt);
			items=com_tree_get_data(items,did);
			$("#treeEditForm #treeData div[name=data]").find("input,select,textarea").each(function(i){
				var name=$(this).attr("data-name");
				if (items[name]!==undefined) {$(this).val(items[name]);}
					var mask="";
					if ($(this).attr("type")=="datepicker") {mask="d.m.Y";}
					if ($(this).attr("type")=="datetimepicker") { mask="d.m.Y H:i";}
					if ($(this).attr("date-oconv")>"") {mask=$(this).attr("date-iconv");}
					if (mask>"") {$(this).val(date(mask,strtotime($(this).val())));}
					var list=["call","dict","tree","enum"];
					if (in_array($(this).attr("type"),list)) {
						var parent=$(this).parent();
						var param={};
						param.type=$(this).attr("type");
						param.label=$(this).attr("placeholder");
						param.name=$(this).attr("data-name");
						param.id=did;
						param.descr=$(this).attr("data-descr"); $(this).removeAttr("data-descr"); 
						param.value=$(this).val();
						param.html=$(parent).html();
						$.ajax({
							async: 		false,
							data:		param,
							type:		'POST',
							url: 		"/engine/ajax.php?mode=FieldBuild",
							success:	function(data){ 
								var data=JSON.parse(data); 
								if (data!==false) { parent.html(data.html);}
								},
							error:		function(data){	console.log("error: ajax FieldBuild :: "+data);}
						});
					}
			});
		}
		active_plugins();
		aikiCallEditor();
		return false;
	});

	function com_tree_imagesel(fldname,fldlabel) {
		var id=$("#treeEditForm").attr("item");
		var fld=$("<div><select data-name='"+fldname+"' placeholder='"+fldlabel+"' class='form-control'><option></option></select></div>");
		var images=JSON.parse($("#treeEditForm #treeImages input[name=images]").val());
		$(images).each(function(i,img){
			fld.find("select").append('<option value="'+img.img+'">'+img.img+'</option>');
		});
		return fld.html();
	}


		function com_tree_data_serialize() {
			var data={};
			$("#treeEditForm div[name=data]").find("input,select,textarea").each(function(i){
				if (!$(this).is("textarea.tpl")) {
					var fldname=$(this).attr("data-name");
					var value=$(this).val();
					data[fldname]=value;
				}
			});
			return data;
		}

	function com_tree_set_data(branch,id,data) {
		var res=false;
		if (id>"") {
			$(branch).each(function(i){
				if (branch[i]["id"]==id) {
					branch[i]["data"]=data;
					return true;
				} else {
					if (branch[i]["children"]!==undefined && res==false) {res=com_tree_get_data(branch[i]["children"][0],id);}
				}
			});
		}
		return res;
	}


	function com_tree_get_data(branch,id) {
		var res=false;
		if (id>"") {
			$.each(branch, function(i,data) {
				if (data.id==id) {
					res=data.data;
					return res;
				} else {
					if (data.children!==undefined && res==false) {res=com_tree_get_data(data.children[0],id);}
				}
			});
		}
		return res;
	}
}

		function com_tree_serialize(that) {
			if (that==undefined) {that=$("#treeEditForm .dd > .dd-list");} else {
				if (!$(that).is(".dd-list")) {that=$(that).find(".dd-list");}
			}
			var data={};
			$(that).children(".dd-item").each(function(i){
				var line={};
				line["id"]=$(this).attr("data-id");
				if ($(this).attr("data-data")>"") {line["data"]=$.parseJSON($(this).attr("data-data"));} else {line["data"]="";}
				line["name"]=$(this).children(".dd3-content").text();
				if ($(this).find(".dd-list").length) {
					if ($(this).find("[data-action=collapse]").is(":visible")) {line["open"]=1;} else {line["open"]=0;}
					line["children"]={};
					$(this).children(".dd-list").each(function(j){
						line["children"][j]=com_tree_serialize(this);
					});
				}
				data[i]=line;
			});
			return data;
		}

function com_multiinp_gen(fldname,fldlabel) {
	// генератор мультиполя
	var mi='<div><div data-role="multiinput" name="'+fldname+'" class="ui-sortable"></div></div>';
	var data=$(mi);
	var tplid=$.parseJSON(ajax_getid());
	var fldtype="text";
	data.find("[data-role=multiinput]").attr("data-tpl","#"+tplid);
	data.find("[data-role=multiinput]").append("<div class='row form-inline multi-fld-row'></div>");
	data.find(".row").append('<div class="col-sm-12">'+
		'<div class="form-group">'+
		'<input type="'+fldtype+'" name="'+fldname+'" data-name="'+fldname+'" placeholder="'+fldlabel+'" class="form-control multiinput" />'+
		'</div></div>');
	var template="<div class='row form-inline multi-fld-row'>"+data.find("[data-role=multiinput]").find(":first-child").html()+"</div>";
	data.find("[data-role=multiinput]").prepend("<textarea id='"+tplid+"' class='tpl' style='display:none;'>"+urlencode(template)+"</textarea>");	
	
	return data.html();
}

function com_dict_init() {
	$(document).delegate("form#dictEditForm a[href=#dictData]","click",function(){
	var dict=$(this).parents("form#dictEditForm");
	var data=$("form#dictEditForm div#dictData [name=data]");
	var flds=dict.find("div[name=fields]");
	var tplid=$.parseJSON(ajax_getid());
	data.attr("data-tpl","#"+tplid);
	data.html("");
	data.append("<div class='row form-inline multi-fld-row'></div>");
	if (flds.find(".row").length>6) {
		var width=12;
	} else {
		var width=Math.floor(12/flds.find(".row").length);
	}
	flds.find(".row").each(function(){
		var fldname=$(this).find("input[data-name=fldname]").val();
		var fldlabel=$(this).find("input[data-name=fldlabel]").val();
		var fldtype=$(this).find("select[data-name=fldtype]").val();
		if (fldlabel=="") {fldlabel=fldname;}
		if (fldtype=="") {fldtype="text";}
		var imd=width; var ixs=width; var ism=width;
		data.find(".row:last").append('<div class="col-md-'+imd+' col-sm-'+ism+'">'+
		'<div class="form-group"><label>'+fldlabel+'</label>'+
		'<input type="'+fldtype+'" name="'+fldname+'" data-name="'+fldname+'" placeholder="'+fldlabel+'" class="form-control" />'+
		'</div></div>');
	});
	var template="<div class='row form-inline multi-fld-row'>"+data.find(":first-child").html()+"</div>";
	data.prepend("<textarea id='"+tplid+"' style='display:none;'>"+urlencode(template)+"</textarea>");
	var items=$.parseJSON(dict.find(".data-cache").html());
	$(items).each(function(i){
		var row=i;
		if (!$(data).find(".row:eq("+row+")").length) {
			data.append(template);
		}
		$(data).find(".row:eq("+row+")").find("input").each(function(col){
			$(this).val(items[row][$(this).attr("data-name")]);
			var mask="";
			if ($(this).attr("type")=="datepicker") {mask="d.m.Y";}
			if ($(this).attr("type")=="datetimepicker") { mask="d.m.Y H:i";}
			if ($(this).attr("date-oconv")>"") {mask=$(this).attr("date-iconv");}
			if (mask>"") {$(this).val(date(mask,strtotime($(this).val())));}
		});
	});
	multiinput_resize();
	return false;
	});

	$(document).delegate("form#dictEditForm a[href=#dictDescr]","click",function(){
		var dict=$(this).parents("form#dictEditForm");
		var data=$("form#dictEditForm div#dictData [name=data]");
		var arr=[];
		$(data).find(".row").each(function(i){
			var row={};
			$(data).find(".row:eq("+i+")").find("input").each(function(j){
				row[$(this).attr("data-name")]=$(this).val();
			});
			arr[i]=row;
		});
		dict.find(".data-cache").html(array2json(arr));
	});
}

function com_media_wrapper(media) {
	$("section").delegate("#"+media+" .media","click",function(){
		$(this).parents("section").find(".modal").attr("id",media+"-show");
		var item=$(this).attr("item");
		$.get("/engine/ajax.php?mode=getajax&form="+media+"&view=modal&item="+item,function(data){
			$("#"+media+"-show").find(".modal-body").html(data);
			var header=$("#"+media+"-show").find(".modal-body").find("h1,h2,h3,h4,h5");
			if (header.html()>"") {
				$("#"+media+"-show").find(".modal-title").html(header.html());
				header.remove();
			}
			$("#"+media+"-show").find(".modal-footer").remove();
			$("#"+media+"-show").modal();
			active_imageviewer();
		});
	});
}

function ajax_loader() {
	var loader='<div id="loading"><ul class="bokeh"><li></li><li></li><li></li><li></li></ul></div>';
	return loader;
}



function ajax_navigation() {
	$("[data-ajax][autoload=true]").each(function(){
		if ($(this).parents("li").length) {
			$(this).parents("ul").find("li").removeClass("active");
			$(this).parents("li").addClass("active");
		}
		ajax_load(this);
	});

	$(document).delegate("[data-ajax]:not([autoload=true])","click",function(){
		if ($(this).parents("li").length) {
			$(this).parents("ul").find("li").removeClass("active");
			$(this).parents("li").addClass("active");
		}
		ajax_load(this);
		return false; // иначе срабатывает несколько раз
	});

	$(document).delegate("#formDelete button[data-dismiss=alert]","click",function() {
		$("#formDelete").modal("hide");
	});

	$(document).delegate("#formDelete button.btn-danger","click",function() {
		var form=$("#formDelete").find("input[name=form]").val();
		var item=$("#formDelete").find("input[name=item]").val();
		var upl=$("#formDelete").find("input[name=uploads]").prop("checked");
		var src="/engine/ajax.php?mode=ajax_deleteform&form="+form+"&item="+item+"&upl="+upl;
		$.get(src,function(data){
			var data=JSON.parse(data);
			var error=data.error;
			if (error==0) {
				var line=$(document).data("delete_item");
				$(document).find("[item="+item+"]").remove();
				$("#formDelete").modal("hide");
			}
		});
	});
}

	function ajax_load(that) {
		if ($(that).attr("data-role")=="cart") {return false;}
		if ($(".dropdown.open").length) {$(".dropdown.open > a[data-toggle]").trigger('click');}
		var ajax=$(that).attr("data-ajax");
		var ptpl=$(that).parents("[data-template]").attr("data-template");
		if (ptpl==undefined) {
			ptpl=$($(that).attr("data-target")).parents("div[id]").find("[data-role=foreach]").attr("data-template");
		}
		//var sessid=$(this).attr("data-sessid");
		if (ajax!=undefined) {
			var link=$(that);
			var parse=explode("?",ajax);
			if (parse==ajax && parse[1]==undefined) {
				var src="/engine/ajax.php";
			} else {
				var src=parse[0];
				var ajax=parse[1];
			}
			if ($(that).parents(".nav").attr("data-src")!==undefined) {src=$(that).parents(".nav").attr("data-src");}
			if (link.attr("data-src")!=undefined) {src=link.attr("data-src");}

			var loader=ajax_loader();
				if (link.attr("data-html")!==undefined) {$(link.attr("data-html")).html(loader);};
				if (link.attr("data-after")!==undefined) {$(link.attr("data-after")).after(loader);};
				if (link.attr("data-before")!==undefined) {$(link.attr("data-before")).before(loader);};
				if (link.attr("data-append")!==undefined) {$(link.attr("data-append")).append(loader);};
				if (link.attr("data-prepend")!==undefined) {$(link.attr("data-prepend")).prepend(loader);};
				if (link.attr("autoload")=="true") {link.html(loader);};

			//if (sessid>"") {src=src+"?&sessid="+sessid;}
			$.get(src,ajax,function(data){
				var d="data-html"; if (link.attr(d)!==undefined) {var target=link.attr(d); $(target).html(data); var t=$(target); }
				var d="data-after"; if (link.attr(d)!==undefined) {var target=link.attr(d); $(target).after(data); var t=$(target).next(); }
				var d="data-before"; if (link.attr(d)!==undefined) {var target=link.attr(d); $(target).before(data); var t=$(target).prev();}
				var d="data-append"; if (link.attr(d)!==undefined) {var target=link.attr(d); $(target).append(data);  var t=$(target).find(":last");}
				var d="data-prepend"; if (link.attr(d)!==undefined) {var target=link.attr(d); $(target).prepend(data); var t=$(target).find(":first");}
				if (link.attr("autoload")=="true" && t==undefined) {link.html(data);};
				if (ptpl>"") {	$(t).find("form").attr("parent-template",ptpl); }

				if (link.attr("data-target")=="DeleteConfirm") {
					if ($(document).find("#formDelete").length) {$("#formDelete").remove();}
					$("body").append(data);
					$("#formDelete").modal("show");
				}
				$(link).find("#loading").remove();
				link.trigger("data-ajax-done", [ target , ajax, data ] );
				setTimeout(function(){
					active_navigation();
					active_imageviewer();
					active_multiinput_flds();
					active_pagination();
					active_plugins();
					active_resize();
				}, 100);
			});
		}
		return false;
	};

function ajax_getid() {
	var newid="";
		$.ajax({
			async: 		false,
			type:		'GET',
			url: 		"/engine/ajax.php?mode=getid",
			success:	function(data){
				newid=data;
			},
			error:		function(data){
				newid=$(document).uniqueId();
			}
		});
	return newid;
}

function ajax_formsave() {
// <button data-formsave="#formId" data-src="/path/ajax.php"></button>
// data-formsave	-	JQ идентификатор сохраняемой формы
// data-form		-	переопределяет имя формы, по-умолчанию берётся аттрибут name тэга form
// data-src			-	путь к кастомному ajax обработчику (необязательно)

	$(document).delegate(".modal-dialog:visible input, .modal-dialog:visible select, .modal-dialog:visible textarea","change",function(){
		$(".modal-dialog:visible").find("[data-formsave] span.glyphicon").removeClass("glyphicon-ok").addClass("glyphicon-save");
	});


	$(document).delegate("[data-formsave]:not([data-role=include])","click",function(){
		var formObj=$($(this).attr("data-formsave"));
		if ($(this).attr("data-add")=="false") {$(formObj).attr("data-add","false");}
		$(this).find("span.glyphicon").addClass("loader");
		var save=aiki_formsave(formObj);
		$(this).find("span.glyphicon").removeClass("loader glyphicon-save").addClass("glyphicon-ok");
		if (save) {
			return save;
		} else {
			return {error:1};
		};
		return false;
	});
};

function aiki_formsave(formObj) {
	if (check_required(formObj)) {
		var ptpl=formObj.attr("parent-template");
		var padd=formObj.attr("data-add");
		// обработка switch из appUI (и checkbox вообще кроме bs-switch)
		var ui_switch="";
		formObj.find("input[type=checkbox]:not(.bs-switch)").each(function(){
			var swname=$(this).attr("name");
			if ($(this).prop("checked")==true) {ui_switch+="&"+swname+"=on";} else {ui_switch+="&"+swname+"=";}
		});

		// обработка bootstrap switch
		var bs_switch="";
		formObj.find(".bs-switch").each(function(){
			var bsname=$(this).attr("name");
			if (bsname!=undefined && bsname>"") {
				if ($(this).bootstrapSwitch("state")==true) {bs_switch+="&"+bsname+"=on";} else {bs_switch+="&"+bsname+"=";}
			}
		});

		var ic_date="";
		formObj.find("[name][type^=date]").each(function(){
			var dtname=$(this).attr("name");
			var type=$(this).attr("type");
			var mask="";
			if ($(this).attr("type")=="datepicker") {mask="Y-m-d";}
			if ($(this).attr("type")=="date") { mask="Y-m-d";}
			if ($(this).attr("type")=="datetimepicker") { mask="Y-m-d H:i";}
			if ($(this).attr("type")=="datetime") { mask="Y-m-d H:i";}
			if ($(this).attr("date-iconv")>"") {mask=$(this).attr("date-iconv");}
			ic_date+="&"+dtname+"="+date(mask,strtotime($(this).val()));
		});


		// прячем данные корзины перед сериализацией - нужно для orders_edit.php
		var cart=formObj.find("[data-role=cart]");
		if (cart.length) {
			cart.find("input,select,textarea").each(function(){
				if ($(this).attr("disabled")!=undefined) {$(this).addClass("tmpDisabled");} else {$(this).prop("disabled");}
			});
			var form=formObj.serialize();
			cart.find("input,select,textarea").each(function(){
				if (!$(this).hasClass("tmpDisabled")) {$(this).removeAttr("disabled");}
			});

		} else {
			var form=formObj.serialize();
		}
		form+=ui_switch+bs_switch+ic_date;
		var name=formObj.attr("name");
		var item=formObj.attr("item");
		var oldi=formObj.attr("item-old");

		
		if ($(this).attr("data-form")!==undefined) {name=$(this).attr("data-form");}
		if ($(this).attr("data-src")!==undefined) {
			var src=$(this).attr("data-src");
		} else {
			var src="/engine/ajax.php?mode=save&form="+name+"&item="+item;
		}
		if (oldi!==undefined) {src+="&copy="+oldi;}
		
		if (ptpl==undefined) {
			var ptpl=$(document).find("[data-add=true][data-template]").attr("data-template");
		}
		if ($(this).parents("#engine__setup").length) {var setup=true;} else {setup=false;}
		if (name!==undefined) {
		var data = {mode: "save", form: name } ;
		$.ajax({
			type:		'POST',
			url: 		src,
			data:		form,
			success:	function(data){
				$.bootstrapGrowl("Сохранено!", {
					ele: 'body',
					type: 'success',
					offset: {from: 'top', amount: 20},
					align: 'right',
					width: "auto",
					delay: 4000,
					allow_dismiss: true,
					stackup_spacing: 10 
				});

				if (ptpl!==undefined && padd!=="false") {
					var tpl=$(document).find("textarea#"+ptpl).html();
					var list=$(document).find("[data-template="+ptpl+"]");
					var post={
						tpl: tpl
					};
					
					var ret=false;
					if (list.attr("data-add")+""!=="false") {
					$.post("/engine/ajax.php?mode=ajax_set_data&form="+name+"&item="+item,post,function(ret){
						if (list.find("[item="+item+"]").length) {
							list.find("[item="+item+"]").after(ret);
							list.find("[item="+item+"]:first").remove();
						} else {
							list.prepend(ret);
						}
						list.find("[item="+item+"]").each(function(){
							if ($(this).attr("idx")==undefined) {$(this).attr("idx",$(this).attr("item"));}
						});
					});
					}

				}
				if (setup==true) {document.location.href="/login.htm";}
				$(document).trigger(name+"_after_formsave",[name,item,form,true]);
				return data;
			},
			error:		function(data){
				$(document).trigger(name+"_after_formsave",[name,item,form,false]);
				$.bootstrapGrowl("Ошибка сохранения!", {
					ele: 'body',
					type: 'danger',
					offset: {from: 'top', amount: 20},
					align: 'right',
					width: "auto",
					delay: 4000,
					allow_dismiss: true,
					stackup_spacing: 10 
				});

				return {error:1};
			}
		});
		}
	} else {
				$.bootstrapGrowl("Ошибка сохранения!", {
					ele: 'body',
					type: 'danger',
					offset: {from: 'top', amount: 20},
					align: 'right',
					width: "auto",
					delay: 4000,
					allow_dismiss: true,
					stackup_spacing: 10 
				});
	}
}

function ajax_sess_kick() {
	setInterval(function(){
		$.get("/engine/ajax.php?mode=ajax_sess_kick");
	},120000);
}

// сторонние функции


$(".modal").on("show.bs.modal", function(){
var $bodyWidth = $("body").width();
$("body").css({'overflow-y': "hidden"}).css({'padding-right': ($("body").width()-$bodyWidth)});
});

$(".modal").on("hidden.bs.modal", function(){
$("body").css({'padding-right': "0", 'overflow-y': "auto"});
});


function setcookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure ) {
  var cookie_string = name + "=" + escape ( value );
  if ( exp_y )  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }
  if ( path ) cookie_string += "; path=" + escape ( path );
  if ( domain ) cookie_string += "; domain=" + escape ( domain );
  if ( secure ) cookie_string += "; secure";
   document.cookie = cookie_string;
}

function delete_cookie ( cookie_name ) {
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

function getcookie ( cookie_name ) {
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
  if ( results ) {  return ( unescape ( results[2] ) ); }  else { return null; }
}

function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

function array2json(arr) {
var parts = [];
var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

for(var key in arr) {
var value = arr[key];
if(typeof value == "object") { //Custom handling for arrays
if(is_list) {
parts.push(array2json(value)); // :RECURSION:
} else {
parts[key] = array2json(value); // :RECURSION:
}
} else {
var str = "";
if(!is_list) {
str = '"' + key + '":';
}
// Custom handling for multiple data types
if(typeof value == "number") {
str += value; //Numbers
} else if(value === false) {
str += 'false'; //The booleans
} else if(value === true) {
str += 'true';
} else {
str += '"' + value + '"'; //All other things
}
// todo: Is there any more datatype we should be in the lookout for? (Functions?)
parts.push(str);
}
}
var json = parts.join(",");
if(is_list) {
return '[' + json + ']';//Return numerical JSON
}
return '{' + json + '}';//Return associative JSON
}

function explode(d, s, l) {
    var out=[], tmp, pos;
    if (l) {
        tmp = s;
        pos = s.indexOf(d)
        while(l-1 && pos>=0)
        {
            out.push(tmp.substr(0, pos));
            tmp = tmp.substr(pos+d.length);
            l--;
            pos = tmp.indexOf(d);
        }
        out.push(tmp);
    }
    else
        out = s.split(d);
    return out;
}

function implode( glue, pieces ) {	// Join array elements with a string
	return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
}

$.unserialize = function(str){
                 var items = str.split('&');
                 var ret = "{";
                 var arrays = [];
                 var index = "";
                 for (var i = 0; i < items.length; i++) {
                         var parts = items[i].split(/=/);
                         //console.log(parts[0], parts[0].indexOf("%5B"), parts[0].indexOf("["));
                         if (parts[0].indexOf("%5B") > -1 || parts[0].indexOf("[") > -1){
                    //Array serializado
                    index = (parts[0].indexOf("%5B") > -1) ? parts[0].replace("%5B","").replace("%5D","") : parts[0].replace("[","").replace("]","");
                    if (arrays[index] === undefined){
                    arrays[index] = [];
                    }
                    arrays[index].push( decodeURIComponent(parts[1].replace(/\+/g," ")));

                         } else {
                    if (parts.length > 1){
                    ret += "\""+parts[0] + "\": \"" + decodeURIComponent(parts[1].replace(/\+/g," ")) + "\", ";
                    }
                         }

                 };

                 ret = (ret != "{") ? ret.substr(0,ret.length-2) + "}" : ret + "}";
                 //console.log(ret, arrays);
                 var ret2 = JSON.parse(ret);
                 //proceso los arrays
                 for (arr in arrays){
                         ret2[arr] = arrays[arr];
                 }
                 return ret2;
}

/*! http://mths.be/noselect v1.0.3 by @mathias */
jQuery.fn.noSelect=function(){var a='none';return this.bind('selectstart dragstart mousedown',function(){return false}).css({MozUserSelect:a,msUserSelect:a,webkitUserSelect:a,userSelect:a})};

