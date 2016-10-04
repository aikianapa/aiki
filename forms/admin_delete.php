<div class="modal fade" id="formDelete" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="alert alert-danger fade in" id="DeleteConfirm" tabindex="-1" >
<form class="col-sm-offset-1" role="form" >
<input type="hidden" name="item">
<input type="hidden" name="form">
	<p class="lead">Вы уверены что хотите удалить? <br /> {{form}} {{item}}</p>
	<label class="checkbox"><input type="checkbox" name="uploads" checked> удалить загрузки</label>
        <button type="button" class="btn btn-default" data-dismiss="alert">Отменить</button>
        <button type="button" class="btn btn-danger">Удалить</button>
</form>
</div>
</div>	
</div>
