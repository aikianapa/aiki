
<form id="{{form}}EditForm" name="{{form}}" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#{{form}}Descr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#{{form}}Text" class="call-editor" data-toggle="tab" >Контент</a></li>
	<li><a href="#{{form}}Images" class="call-imgloader" data-toggle="tab">Изображения</a></li>
</ul>

<div class="tab-content">
<div id="{{form}}Descr" class="tab-pane active">
<br />
	<div class="form-group">
	  <label class="col-sm-2 control-label">Имя записи</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя записи" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Заголовок</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="Заголовок"  ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Подвал</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="footer" placeholder="Подвал"  ></div>
	</div>

</div>

<div id="{{form}}Text" class="tab-pane">
<textarea name="text" id="text" class="editor" placeholder="Контент" required >{{text}}</textarea>
</div>
<div id="{{form}}Images" class="tab-pane" data-role="imageloader"></div>
</div>
</form>
