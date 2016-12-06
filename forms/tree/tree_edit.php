	<form id="{{form}}EditForm" name="{{form}}"  item="{{id}}"  class="form-horizontal" role="form">
		<input type="hidden" name="tree">
		<div class="form-group">
		   <label class="col-sm-2 control-label" data-lang="input.id">Наименование</label>
		   <div class="col-sm-10"><input type="text" data-lang="input.id" class="form-control" name="id" placeholder="Имя каталога" required ></div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label" data-lang="input.descr">Описание</label>
			<div class="col-sm-10"><input type="text" data-lang="input.descr" class="form-control" name="descr" placeholder="Описание"></div>
		</div>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#{{form}}Descr" data-toggle="tab" data-lang="a.descr">Каталог</a></li>
		<li><a href="#{{form}}Prop" data-toggle="tab" data-lang="a.prop">Свойства</a></li>
		<li class="hidden"><a href="#{{form}}Data" data-toggle="tab" data-lang="a.data">Данные</a></li>
		<li class="hidden"><a href="#{{form}}Flds" data-toggle="tab" data-lang="a.flds">Поля ввода</a></li>
		<li><a href="#{{form}}Images" data-toggle="tab" data-lang="a.images">Изображения</a></li>
		<a class="dd3active hidden" href="#dd3active">dd3active</a>
	</ul>

	<div class="tab-content">
		<div id="{{form}}Descr" class="tab-pane active">
			<br />
			<div class="dd">
				<ol class="dd-list"></ol>
			</div>
		</div>

		<div id="{{form}}Prop" class="tab-pane">
			<br />
			<div data-role="multiinput" name="fields" class="ui-sortable">
				<input type="text" name="fldname" placeholder="Имя поля">
				<input type="text" name="fldlabel" placeholder="Текстовая метка">
				<select type="text" name="fldtype" placeholder="Тип поля">
				<option value="string">string</option>
				<option value="text">text</option>
				<option value="number">number</option>
				<option disabled>--== Плагины ==--</option>
				<option value="editor">editor</option>
				<option value="image">image</option>
				<option value="multiinput">multiinput</option>
				<option value="call">call</option>
				<option value="enum">enum</option>
				<option value="dict">dict</option>
				<option value="tree">tree</option>
				<option value="phone">phone</option>
				<option value="mask">mask</option>
				<option value="datepicker">datepicker</option>
				<option value="datetimepicker">datetimepicker</option>
				<option disabled>--== Другие ==--</option>
				<option value="date">date</option>
				<option value="week">week</option>
				<option value="month">month</option>
				<option value="year">year</option>
				<option value="time">time</option>
				<option value="color">color</option>
				</select>
				<input type="text" name="flddescr" placeholder="Комментарий" >

			</div>
			<div class="data-cache hidden">{{data}}</div>
		</div>

		<div id="{{form}}Data" class="tab-pane">
			<form id="{{form}}EditFormData" class="form-horizontal" role="form">
				<br>
				<div class="form-group">
				  <label class="col-sm-3 control-label">Ключ</label>
				   <div class="col-sm-9"><input type="text" class="form-control" data-tree="id" placeholder="Идентификатор" required ></div>
				  <label class="col-sm-3 control-label">Текст</label>
				   <div class="col-sm-9"><input type="text" class="form-control" data-tree="name" placeholder="Наименование" required ></div>
				</div>
				<div class="form-group" name="data"></div>
			</form>
		</div>
		<div id="{{form}}Flds" class="tab-pane">
				<br/>
				<ul class="fieldSet">форма</ul>
				
		</div>
		
		
		<div id="{{form}}Images" class="tab-pane" data-role="imageloader" data-prop="false"></div>

	</div>
	</form>


<script language="javascript">
$(function(){
	com_tree_init();
			var btn='<div class="dd-btn">'+
					'<a href="#" class="btn btn-xs btn-success dd-edit"><span class="fa fa-edit"></span></a>&nbsp;'+
					'<a data-toggle="dropdown" href="#" aria-expanded="true" class="btn btn-xs btn-primary dd-menu"><span class="fa fa-list-ul"></span></a>'+
					'<ul class="dropdown-menu" role="menu">'+
					'<li><a href="#" class="dd-add"><span class="fa fa-plus"></span>&nbsp;&nbsp;Добавить</a></a></li>'+
					'<li><a href="#" class="dd-copy"><span class="fa fa-copy"></span>&nbsp;&nbsp;Копировать</a></a></li>'+
					'<li><a href="#" class="dd-flds"><span class="fa fa-address-card-o"></span>&nbsp;&nbsp;Поля ввода</a></a></li>'+
					'<li><a href="#" class="dd-edit"><span class="fa fa-edit"></span>&nbsp;&nbsp;Редактировать</a></a></li>'+
					'<li><a href="#" class="dd-del"><span class="fa fa-trash"></span>&nbsp;&nbsp;Удалить</a></li>'+
					'</ul></div>';

			var line='<li class="dd-item dd3-item dd-new" data-id="">'+
					  '<div class="dd-handle dd3-handle"></div><div class="dd3-content">Новая запись</div>'+
					  '</li>';
	$(document).ready(function(){
		dd_setTree();
		$("#treeEditForm .dd").nestable({maxDepth:10});
		$("#treeEditForm .dd .dd-collapsed [data-action=collapse]").css("display","none");
		$("#treeEditForm .dd .dd-collapsed [data-action=expand]").css("display","block");
		$("#treeEditForm .dd").data("dd-handle-color",$(".dd").find(".dd3-item > .dd3-handle").css("background"));
		$("#treeEditForm .dd").data("dd-handle-active",$(document).find(".btn-primary").css("background"));

		$("#treeEditForm .nav a[href^=#{{form}}]").on("click",function(e){
			if ($(this).attr("href")!=="#{{form}}Data") {$("#{{form}}EditForm a[href=#treeData]").parent("li").addClass("hidden");}
			if ($(this).attr("href")!=="#{{form}}Flds") {$("#{{form}}EditForm a[href=#treeFlds]").parent("li").addClass("hidden");}
			if ($(this).attr("href")=="#{{form}}Descr" && $("#treeEditForm #dd3active").length) {
				setTimeout(function(){$("#treeEditForm .nav a.dd3active.hidden").trigger("click");},10);
			}
		});

		$("#treeEditForm .nav a.dd3active").on("click",function(e){
			var target = $(this);
			var offset=$("#treeEditForm #dd3active").data("offset");
			if (offset==undefined || offset=="") {offset=100;}
			$("#treeEdit.modal").stop().animate({
			scrollTop: $(target.attr('href')).offset().top - offset
			}, 100);
			e.preventDefault();
		});

		$("#treeEditForm").undelegate(".dd .dd-add","click");
		$("#treeEditForm").delegate(".dd .dd-add","click",function(){
			if ($(this).parents(".dd-item.active").find(".dd-list").length) {
				if ($(this).parents(".dd-item.active").find("[data-action=collapse]").is(":visible")) {
					$(this).parents(".dd-item.active").find(".dd-list:first").prepend(line);
				} else {
					$(this).parents(".dd-item.active").after(line);
				}
			} else {
				$(this).parents(".dd-item.active").after(line);
			}
			var newline=$(".dd").find(".dd-new");
			var newid=$.parseJSON(ajax_getid());
			newline.attr("data-id",newid).removeClass("dd-new");
			$(".dd").nestable();
			dd_setData();
		});

		$("#treeEditForm").undelegate(".dd .dd-copy","click");
		$("#treeEditForm").delegate(".dd .dd-copy","click",function(){
			var copy=$(this).parents(".dd-item.active").clone();
			var newid=$.parseJSON(ajax_getid());
			copy.attr("data-id",newid);
			copy.removeAttr("id").removeClass("active").find(".dd-btn").remove();
			copy.find(".dd-handle").removeAttr("style");
			copy.find(".dd3-content:first").append(" (копия)");
			copy.find(".dd-item").each(function(){
				var newid=$.parseJSON(ajax_getid());
				$(this).attr("data-id",newid);
			});
			$(this).parents(".dd-item.active").after(copy);

		});

		$("#treeEditForm").undelegate(".dd .dd-edit","click");
		$("#treeEditForm").delegate(".dd .dd-edit","click",function(){
			$("#{{form}}EditForm #dd3active").data("offset",$("#treeEditForm #dd3active").offset().top);
			$("#{{form}}EditForm a[href=#treeData]").parent("li").removeClass("hidden");
			$("#{{form}}EditForm a[href=#treeData]").trigger("click");
		});

		$("#treeEditForm").undelegate(".dd .dd-flds","click");
		$("#treeEditForm").delegate(".dd .dd-flds","click",function(){
			$("#{{form}}EditForm #dd3active").data("offset",$("#treeEditForm #dd3active").offset().top);
			$("#{{form}}EditForm a[href=#treeFlds]").parent("li").removeClass("hidden");
			$("#{{form}}EditForm a[href=#treeFlds]").trigger("click");
			$("#{{form}}EditForm #treeFlds .fieldSet").html("");
			var self=$.parseJSON($("#{{form}}EditForm .dd-item.active").attr("data-fldself"));
			var child=$.parseJSON($("#{{form}}EditForm .dd-item.active").attr("data-fldchild"));
			var fldname, fldlabel, fldtype;
			$("#{{form}}EditForm #treeProp .row").each(function(){
					fldname=$(this).find("[data-name=fldname]").val();
					fldlabel=$(this).find("[data-name=fldlabel]").val();
					fldtype=$(this).find("[data-name=fldtype]").val();
					$("#{{form}}EditForm #treeFlds .fieldSet").append(''
					+'<div class="row"><div class="col-sm-2">' +fldname+ '</div>'
					+'<div class="col-sm-2">(' +fldtype+ ')</div>'
					+'<div class="col-sm-2">' +fldlabel+ '</div>'
					+'<div class="col-sm-1 text-center"><label class="switch switch-primary" title="Текущий уровень">'
							+'<input type="checkbox" data-name="self" value=""><span></span></label></div>'
					+'<div class="col-sm-1 text-center"><label class="switch switch-primary" title="Вложенные уровни">'
							+'<input type="checkbox" data-name="child" value=""><span></span></label></div>'
					+'</div>');
					$("#{{form}}EditForm #treeFlds .fieldSet .row:last input[type=checkbox]").attr("data-fld",fldname);
					if (in_array(fldname,self)) {$("#{{form}}EditForm #treeFlds .fieldSet .row:last input[type=checkbox][data-name=self]").trigger("click");}
					if (in_array(fldname,child)) {$("#{{form}}EditForm #treeFlds .fieldSet .row:last input[type=checkbox][data-name=child]").trigger("click");}
			});
			
			
			$("#{{form}}EditForm .fieldSet [title]").tooltip();
			$("#{{form}}EditForm #treeFlds [type=checkbox]").on("change",function(){
				var self=[];
				var child=[];
				var line=$("#{{form}}EditForm .dd-item.active");
				$("#{{form}}EditForm #treeFlds [type=checkbox]").each(function(){
						if ($(this).prop("checked")==true) {
							if ($(this).attr("data-name")=="self") {self.push($(this).attr("data-fld"));}
							if ($(this).attr("data-name")=="child") {child.push($(this).attr("data-fld"));}
						}
					
				});
				$("#{{form}}EditForm .dd-item.active").attr("data-fldself",JSON.stringify(self));
				$("#{{form}}EditForm .dd-item.active").attr("data-fldchild",JSON.stringify(child));
				dd_setData();
			});
		});


		$("#treeEditForm").undelegate(".dd .dd-del","click");
		$("#treeEditForm").delegate(".dd .dd-del","click",function(){
			if ($(this).parents(".dd-item.active").parent(".dd-list").find(".dd-item").length==1) {
				$(this).parents(".dd-item.active").parent(".dd-list").parent(".dd-item").find("button").remove();
				$(this).parents(".dd-item.active").parent(".dd-list").remove();
			} else {
				$(this).parents(".dd-item.active").remove();
			}
			dd_setData();
		});

		$("#treeEditForm").undelegate(".dd3-content","click");
		$("#treeEditForm").delegate(".dd3-content","click",function(){
			if (!$(this).find("input").length) {
				$(this).parents(".dd").find(".dd3-item.active").removeAttr("id");
				$(this).parents(".dd").find(".dd3-item.active > .dd3-handle").css("background",$(".dd").data("dd-handle-color"));
				$(this).parents(".dd").find(".dd-item").removeClass("active");
				$(this).parents(".dd").find(".dd-btn").remove();
				$(this).parent(".dd-item").addClass("active");
				$(this).parent(".dd-item").append(btn);
				$(".dd").data("dd-active",$(this));
				$(this).parents(".dd").find(".dd3-item.active > .dd3-handle").css("background",$(".dd").data("dd-handle-active"));
				var value=$(this).html();
				$(this).html(`<input value="0" >`);
				$(this).find("input").val(value);
				$(this).find("input").trigger("focus");
				$(window).trigger("resize");
				$(this).parents(".dd").find(".dd3-item.active").attr("id","dd3active");
				return false;
			}
		});

		$(document).undelegate(".dd3-content","focusout");
		$(document).delegate(".dd3-content","focusout",function(){
			if ($(this).find("input").hasClass("dd-id")) {
				$(this).parents(".dd-item.active").attr("data-id",$(this).find("input").val());
				$(this).html($(this).find("input").attr("data-save"));
			} else {
				$(this).html($(this).find("input").val());
			}
			dd_setData();
			return false;
		});

		$('.dd').on('change', function() {
			var line=$(this).find(".dd-item.active");
			$(window).trigger("resize");
			dd_setData();
		});

		$("#treeEdit [data-formsave=#treeEditForm]").on("click",function(){
			dd_setData();
		});

		function dd_setTree(that,tree) {
			if (that==undefined) {that=$("#{{form}}EditForm .dd > .dd-list");} else {
				if (!$(that).is(".dd-list")) {that=$(that).find(".dd-list");}
			}
			if (tree==undefined) {
				var newid=$.parseJSON(ajax_getid());
				var tree=$('#{{form}}EditForm input[name=tree]').val();
				if (tree=="") {var tree={"0":{"id":newid, "name":"Новая запись","data":""}}	} else {var tree=$.parseJSON(tree);}
			}
			for(var key in tree) {
				$(that).append(line);
				var newline=$(that).find(".dd-new");
				newline.attr("data-id",tree[key].id);
				newline.attr("data-data",JSON.stringify(tree[key].data));
				newline.attr("data-fldself",JSON.stringify(tree[key].fldself));
				newline.attr("data-fldchild",JSON.stringify(tree[key].fldchild));
				newline.find(".dd3-content").html(tree[key].name);
				newline.removeClass("dd-new");
				if (tree[key].children!==undefined) {
					if (tree[key]["open"]==undefined || tree[key]["open"]=="0") {newline.addClass("dd-collapsed");}
					newline.append('<ol class="dd-list"></ol>');
					newline.removeClass("dd-new");
					dd_setTree(newline.children(".dd-list"),tree[key].children[0]);
				}
				newline.removeClass("dd-new");
			}

		}


		function dd_setData() {
			var data=com_tree_serialize();
			$("#{{form}}EditForm input[name=tree]").val(JSON.stringify(data));
		}

	});

});
</script>
    <style type="text/css">
@media only screen and (min-width: 700px) {
	#{{form}}EditForm     .dd {width: 100%; }
	#{{form}}EditForm     .dd + .dd { margin-left: 2%; }
}

#{{form}}EditForm .dd-hover > .dd-handle { background: #2ea8e5 !important; }
#{{form}}EditForm .dd input.dd-id {color: #D43F3A;}

#{{form}}EditForm .dd3-content { display: block; height: 30px; margin: 3px 0; padding: 3px 10px 3px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
#{{form}}EditForm .dd3-content:hover { color: #2ea8e5; background: #fff; }
#{{form}}EditForm .dd3-content input {border:none; background:transparent; padding:0; margin:0; display:inline;}
#{{form}}EditForm .dd-dragel > .dd3-item > .dd3-content { margin: 0; }

#{{form}}EditForm .dd3-item > button { margin-left: 30px; }

#{{form}}EditForm .dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
#{{form}}EditForm .dd3-handle:before {font-family:FontAwesome;  content: '\f07d'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 14px; font-weight: normal; }
#{{form}}EditForm .dd3-handle:hover { background: #ddd; }
#{{form}}EditForm .dd-btn {position:absolute; display:inline-flex; top:0; margin:4px;}
#{{form}}EditForm #treeFlds .fieldSet .switch span {height:16px; width:32px;}
#{{form}}EditForm #treeFlds .fieldSet .switch input:checked + span:after {width:12px; left:18px;}
#{{form}}EditForm #treeFlds .fieldSet .switch span:after {width:12px;}
    </style>
