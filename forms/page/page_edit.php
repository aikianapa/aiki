<form id="pageEditForm" name="page" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#pageDescr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#pageText" class="call-editor" data-toggle="tab" >Контент</a></li>
	<li><a href="#pageSource" class="call-source" data-toggle="tab" >Исходный код</a></li>
	<li><a href="#pageImages" class="call-imgloader" data-toggle="tab">Изображения</a></li>
</ul>

<div class="tab-content">
<br />
<div id="pageDescr" class="tab-pane active">
	<div class="form-group">
	  <label class="col-sm-2 control-label">Имя записи</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя записи" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Заголовок</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="Заголовок"></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Подвал</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="footer" placeholder="Подвал"></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Шаблон</label>
	   <div class="col-sm-10">
		   <select class="form-control" name="template" placeholder="Шаблон" data-role="foreach" from="tpllist">
				<option value="{{0}}">{{0}}</option>
		   </select>
		</div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-description</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="meta_description" placeholder="Описание"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Meta-keywords</label>
	   <div class="col-sm-10"><input type="text" class="form-control input-tags" name="meta_keywords" placeholder="Ключевые слова"></div>
	</div>

</div>

<div id="pageText" class="tab-pane" data-role="include" src="editor" data-name="text"></div>
<div id="pageSource" class="tab-pane" data-role="include" src="source" data-name="text"></div>
<div id="pageImages" class="tab-pane" data-role="imageloader" data-ext="jpg png gif zip pdf doc"></div></div>
</form>
