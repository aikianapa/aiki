
<form id="{{form}}EditForm" name="{{form}}" item="{{id}}"  class="form-horizontal" role="form">

	<div class="form-group">
	  <label class="col-sm-2 control-label">Имя записи</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя записи" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Наименование</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="name" placeholder="Наименование" required ></div>
	</div>


<ul class="nav nav-tabs">
	<li class="active"><a href="#{{form}}Descr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#{{form}}Text" class="call-editor" data-toggle="tab" >Описание</a></li>
	<li><a href="#{{form}}Images" class="call-imgloader" data-toggle="tab">Изображения</a></li>
</ul>

<div class="tab-content">
<div id="{{form}}Descr" class="tab-pane active">
<br />
	
	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-description</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="meta_description" placeholder="Описание"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-keywords</label>
	   <div class="col-sm-10"><input type="text" class="form-control input-tags" name="meta_keywords" placeholder="Ключевые слова"></div>
	</div>

	
	<div class="form-group">
		<label class="col-sm-2 control-label">Активная</label>
		<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="active" ><span></span></label></div>
		<label data-allow="admin" class="col-sm-2 control-label">В лучшие</label>
		<div data-allow="admin" class="col-sm-2" title="Добавить в список лучших"><label class="switch switch-primary"><input type="checkbox" name="best" ><span></span></label></div>
	</div>
	
	<div class="form-group">
	  <label class="col-sm-2 control-label">Раздел</label>
	   <div class="col-sm-10">
			<select data-role="tree" from="prod_division" class="form-control"  name="division">
				<option value="{{id}}">{{name}}</option>
			</select>
		</div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Цена</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="price" placeholder="Цена" required ></div>
	</div>

</div>

<div id="{{form}}Text" class="tab-pane" data-role="include" src="editor" data-name="text"></div>
<div id="{{form}}Images" class="tab-pane" data-role="imageloader"></div>
</div>
</form> 
