	<div class="form-group" data-role="where" data=' "{{_SETT[elogin]}}" = "login" '>
		<label class="col-sm-2 control-label">Логин</label>
		<div class="col-sm-4"><input type="text" name="login" class="form-control" placeholder="Логин" data-enabled="admin"></div>
		<a class="btn btn-info btn-change-pass" data-allow="admin">Изменить пароль</a>
	</div>
	<div class="form-group" data-role="where" data=' "{{_SETT[elogin]}}" = "email" '>
		<label class="col-sm-2 control-label">Эл.почта</label>
		<div class="col-sm-4"><input type="email" class="form-control" name="email" placeholder="Электронная почта" data-enabled="admin" required ></div>
		<a class="btn btn-info btn-change-pass" data-allow="admin">Изменить пароль</a>
	</div>
	<div class="form-group hidden change-pass" data-allow="admin">
		<input type="hidden" name="password" disabled>
		<label class="col-sm-2 control-label">Пароль</label>
		<div class="col-sm-3"><input type="password" class="form-control password-enter" placeholder="Пароль" disabled ></div>
		<label class="col-sm-2 control-label">Повторите</label>
		<div class="col-sm-3"><input type="password" class="form-control password-check" placeholder="Проверка пароля" disabled ></div>
		<div class="col-sm-2"><a class="btn btn-default change-ok"><i class="glyphicon glyphicon-remove"></i> изменить</a></div>
	</div>

<script language="javascript" data-allow="admin">
$(document).ready(function(){
	$("#{{form}}EditForm .btn-change-pass").unbind("click");
	$("#{{form}}EditForm .btn-change-pass").on("click",function(){
		$("#{{form}}EditForm .change-pass").removeClass("hidden");
		$("#{{form}}EditForm .change-pass input[type=password]").removeAttr("disabled");
	});
	$("#{{form}}EditForm .password-enter").unbind("change");
	$("#{{form}}EditForm .password-enter").on("change",function(){
		$("#{{form}}EditForm .btn.change-pass").removeClass("btn-success").removeClass("btn-danger").addClass("btn-default");
		$("#{{form}}EditForm .password-check").attr("data","");
		var md5 = CryptoJS.MD5($(this).val()).toString();
		$(this).attr("data",md5);
	});
	$("#{{form}}EditForm .password-check").unbind("keyup");
	$("#{{form}}EditForm .password-check").on("keyup",function(){
		var pass=$("#{{form}}EditForm .password-enter").attr("data");
		var md5 = CryptoJS.MD5($(this).val()).toString();
		if (pass==md5) {
			pass_true();
		} else  {
			pass_false();
		}
	});

	$("#{{form}}EditForm .password-enter").unbind("keyup");
	$("#{{form}}EditForm .password-enter").on("keyup",function(){
		var pass=$("#{{form}}EditForm .password-check").attr("data");
		var md5 = CryptoJS.MD5($(this).val()).toString();
		if (pass==md5) {
			pass_true();
		} else  {
			pass_false();
		}
	});

	$("#{{form}}EditForm a.btn.change-ok").unbind("click");
	$("#{{form}}EditForm a.btn.change-ok").on("click",function(){
		if ($(this).hasClass("btn-success")) {
			$("#{{form}}EditForm .change-pass").addClass("hidden");
			$("#{{form}}EditForm .change-pass input[type=password]").attr("disabled",true);
			$("#{{form}}EditForm .change-pass input[name=password]").removeAttr("disabled");
			$("#{{form}}EditForm [name=password]").val($("#{{form}}EditForm .password-enter").attr("data"));
			console.log($("#{{form}}EditForm .change-pass input[name=password]").val());
			$("#{{form}}EditForm").find(".change-pass").addClass("hidden");
		} else {
			$("#{{form}}EditForm .change-pass").addClass("hidden");
			$("#{{form}}EditForm .change-pass input[type=password]").attr("disabled",true);
		}
	});

	function pass_true(){
		$("#{{form}}EditForm .btn.change-ok").removeClass("btn-default").removeClass("btn-danger").addClass("btn-success");
		$("#{{form}}EditForm .btn.change-ok i").removeAttr("class").
			addClass("glyphicon glyphicon-ok");
	};

	function pass_false(){
		$("#{{form}}EditForm .btn.change-ok").removeClass("btn-default").removeClass("btn-success").addClass("btn-danger");
		$("#{{form}}EditForm .btn.change-ok i").removeAttr("class").
			addClass("glyphicon glyphicon-remove");
		$("#{{form}}EditForm .change-pass [name=password]").attr("disabled",true);
	};
});

</script>
