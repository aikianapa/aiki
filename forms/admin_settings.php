<h2 class="sub-header">Настройки
<button type="button" class="btn btn-success pull-right" id="admin_btn_update">Обновление</button>
</h2>
<form  class="form-horizontal" role="form" method="post" name="admin" item="settings" id="admin_settings" data-allow="admin,moder">
<div class="panel panel-default">
<div class="panel-body">
<ul class="nav nav-tabs">
	<li class="active"><a href="#adminCommon" data-toggle="tab">Общие настройки</a></li>
	<li><a href="#adminForms" data-allow="admin" data-toggle="tab" >Настройки форм</a></li>
	<li><a href="#adminMerchant" data-allow="admin" data-toggle="tab" >Настройки мерчанта</a></li>
	<button type="button" class="btn btn-primary pull-right" data-formsave="#admin_settings">Сохранить настройки</button>
</ul>
<div class="tab-content">
	<br>
	<div id="adminCommon" class="tab-pane active">
			<div class="form-group setup"><label class="col-sm-2 control-label">Заголовок сайта</label>
			<div class="col-sm-10"><input type="text" name="header" class="form-control" placeholder="Заголовок по-умолчанию"></div>
			</div>

			<div class="form-group">
			  <label class="col-sm-2 control-label">Шаблон страниц</label>
			   <div class="col-sm-10">
				   <select class="form-control" name="template" placeholder="Шаблон страниц по-умолчанию" data-role="foreach" from="tpllist">
						<option value="{{0}}">{{0}}</option>
				   </select>
				</div>
			</div>

			<div class="form-group setup"><label class="col-sm-2 control-label">Электронная почта</label>
			<div class="col-sm-10"><input type="email" name="email" class="form-control" placeholder="Электронная почта"></div>
			</div>

			<div class="form-group"><label class="col-sm-2 control-label">Телефон</label>
			<div class="col-sm-10"><input type="phone" name="phone" class="form-control" placeholder="Телефон"></div>
			</div>

			<div class="form-group"><label class="col-sm-2 control-label">Плагин мерчанта</label>
			<div class="col-sm-10">
				<select data-role="foreach" from="checkout_list" name="checkout" class="form-control" placeholder="Плагин мерчанта">
					<option value="{{name}}" data-dir="{{dir}}">{{name}}</option>
				</select>
			</div>
			</div>

			<div data-allow="admin">
				<div class="form-group setup">
					<label class="col-sm-2 control-label">Адм.&nbsp;логин</label>
					<div class="col-sm-4"><input type="text" name="login" class="form-control" required placeholder="Логин администратора"></div>
					<label class="col-sm-2 control-label">Адм.&nbsp;пароль</label>
					<div class="col-sm-4"><input type="password" name="pass" class="form-control" required placeholder="Пароль администратора"></div>
				</div>

				<div class="form-group">
					<hr>
					<h4>&nbsp;Автозагрузка модулей для фронтэнда</h4>
					<label class="col-sm-2 control-label">jQuery</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="jquery"><span></span></label></div>
					<label class="col-sm-2 control-label">jQueryUI</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="jqueryui"><span></span></label></div>
					<label class="col-sm-2 control-label">Bootstrap</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="bootstrap"><span></span></label></div>
					<label class="col-sm-2 control-label">AppUI</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="appui"><span></span></label></div>
					<label class="col-sm-2 control-label">AppUI plugins</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="appuiplugins"><span></span></label></div>
					<label class="col-sm-2 control-label">AiKi</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="aikiload"><span></span></label></div>
					<label class="col-sm-2 control-label">CkEditor</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="editload"><span></span></label></div>
					<label class="col-sm-2 control-label">Uploader</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="upldload"><span></span></label></div>
					<label class="col-sm-2 control-label">ImgViewer</label>
					<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="imgviewer"><span></span></label></div>
				</div>

				<div class="form-group">
					<hr>
					<label class="col-sm-3 control-label" for="soldswitch">Вход пользователей</label>
					<div class="col-sm-7"><input type="checkbox" name="elogin" class="bs-switch" data-on-text="email" data-off-text="login" data-size="small"></div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="soldswitch">Поддомены</label>
					<div class="col-sm-7"><input type="checkbox" name="projects" class="bs-switch" data-on-text="Вкл." data-off-text="Выкл." data-size="small"></div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="soldswitch">Хранение&nbsp;данных</label>
					<div class="col-sm-7"><input type="checkbox" name="store" class="bs-switch" data-on-text="База" data-off-text="Файлы" data-size="small" ></div>
				</div>

				<div class="dbconnect">
				<div class="form-group">
					<label class="col-sm-2 control-label">DB&nbsp;Host</label>
					<div class="col-sm-4"><input type="text" name="dbhost" class="form-control" placeholder="Хост базы данных"></div>
					<label class="col-sm-2 control-label">DB&nbsp;Name</label>
					<div class="col-sm-4"><input type="text" name="dbname" class="form-control" placeholder="Имя базы данных"></div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">DB&nbsp;User</label>
					<div class="col-sm-4"><input type="text" name="dbuser" class="form-control" placeholder="Имя пользователя базы данных"></div>
					<label class="col-sm-2 control-label">DB&nbsp;Password</label>
					<div class="col-sm-4"><input type="text" name="dbpass" class="form-control" placeholder="Пароль пользователя базы данных"></div>
				</div>
				</div>

			</div>

			<div data-role="multiinput" name="variables" class="ui-sortable">
			<input name='engine_variable' type='text' placeholder="Имя переменной" aria-label="...">
			<input name='engine_value' type='text' placeholder="Значение">
			<input name='engine_descr' type='text' placeholder="Комментарий">
			</div>
	</div>
	<div id="adminForms" class="tab-pane" data-allow="admin">
			<div data-role="multiinput" name="forms" class="ui-sortable">
			<select name='name' type='text' placeholder="Имя формы" data-role="foreach" from="formlist">
				<option value="{{0}}">{{0}}</option>
			</select>
			<input name='descr' type='text' placeholder="Описание">
			<input name='allow' type='text' placeholder="Доступ разрешён (data-allow)">
			<input name='disallow' type='text' placeholder="Доступ запрещён (data-disallow)">
			</div>
	</div>
	<div id="adminMerchant" class="tab-pane" data-allow="admin">
	</div>

</div>
</div>
</div>
</form>
<script>
$(document).ready(function(){
	$("#admin_settings [name=store]").on("switchChange.bootstrapSwitch",function(event, state){
		if (state==true) {$("#admin_settings .dbconnect").show();} else {$("#admin_settings .dbconnect").hide();}
	});
	if ($("#admin_settings [name=store]").attr("value")=="on") {$("#admin_settings .dbconnect").show();} else {$("#admin_settings .dbconnect").hide();}
	$("#admin_settings .nav a[href=#adminMerchant]").parents("li").hide();
	$("#admin_settings select[name=checkout]").unbind();
	$("#admin_settings select[name=checkout]").on("change",function(){
		if ($(this).val()>"") {
			$.get("/engine/ajax.php?mode=settings&form="+$(this).val(),function(data){
				$("#admin_settings #adminMerchant").html(data);
			});
			$("#admin_settings .nav a[href=#adminMerchant]").parents("li").show();
		} else {
			$("#admin_settings .nav a[href=#adminMerchant]").parents("li").hide();
			$("#admin_settings #adminMerchant").html("");
		}
	});
	$("#admin_settings select[name=checkout]").trigger("change");
	
	$("#admin_btn_update").on("click",function(){
		aiki_update_process();
	});
	
});

function aiki_update_process(step,count,msg) {
		var param={};
		var start=30;
		if (step==undefined) {var step=0;}
		if (count==undefined) {var count=0;}
		if (msg==undefined) {
			var msg="Инициализация";
			var panel=	'<div class="widget update-process ">'+
						'	<div class="widget-content themed-background-dark text-light-op">Обновление системы</div>'+
						'	<div class="widget-content themed-background-muted text-center">'+
						'		<div class="progress progress-striped active">'+
						'			<div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
										msg+
						'			</div>'+
                        '		</div>'+
                        '		<a href="/admin.htm" class="btn btn-success">Завершить обновление</a>'+
						'	</div>'+
						'</div>';
			$(".main").html(panel);
			$(".main .update-process .btn-success").hide();
			$(".main .update-process .progress-bar-info").css("width",start+"%");
		}
		setTimeout(function(){ 
		$.ajax({
			async: 		true,
			type:		'POST',
			url: 		"/engine/update.php?step="+step,
			success: 	function(data){
							var data=JSON.parse(data);
							if (count>0) {var percent=ceil(start+((100-start)/count*step));} else {var percent=start;}
							if (data.count!==undefined) {
								count=data.count;
								$(".main").data("count",count);
							}
							$(".main .update-process .text-light-op").html(data.next);
							$(".main .update-process .progress-bar-info").css("width",percent+"%");
							$(".main .update-process .progress-bar-info").html(percent+"%");
							if (step<count) {
								step++;
								aiki_update_process(step,count,data.next);
							} else {
								$(".main .update-process .progress").removeClass("progress-striped active");
								$(".main .update-process .btn-success").show("slow");
							}
						}
		});
		},500);
}
</script>
