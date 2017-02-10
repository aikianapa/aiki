<h3>Мастер создания формы</h3>

<form id="formMasterForm" name="form" item="master"  class="form-horizontal col-xs-12 col-sm-10 col-md-6" role="form">
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
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Вкладки</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-xs-8 col-sm-5 control-label">Характеристики</label>
				<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="prop" value="on" checked="checked"><span></span></label></div>
			</div>
			<div class="form-group">
				<label class="col-xs-8 col-sm-5 control-label">Контент</label>
				<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="text" value="on" checked="checked"><span></span></label></div>
			</div>
			<div class="form-group">
				<label class="col-xs-8 col-sm-5 control-label">Исходник</label>
				<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="source" value=""><span></span></label></div>
			</div>
			<div class="form-group">
				<label class="col-xs-8 col-sm-5 control-label">Изображения</label>
				<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="images" value="on" checked="checked"><span></span></label></div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-xs-12"><button type="button" class="btn btn-success pull-right">Создать форму</button></div>
	</div>

</form>
<script language="javascript">
$(document).ready(function(){
	
	$("#formMasterForm input[name=name]").on("keyup",function(){
		$(this).val($(this).val().replace(/[^a-z0-9]/i, ""));
	});
	
	$("#formMasterForm button").on("click",function(){
		if (check_required("#formMasterForm")) {
			var data=$("#formMasterForm").serialize();
			$.ajax({
				url: "/engine/ajax.php?mode=create&form=form",
				method: "post",
				data: data,
				success: function(data){
					var data=JSON.parse(data); 
					if (data.error==false) {
						var type="success";
						$(".main").html("");
						$(".sidebar-nav .formlist").append(data.append);
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
});
</script>
