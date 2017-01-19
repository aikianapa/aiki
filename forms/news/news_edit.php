<form id="newsEditForm" name="news" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#newsDescr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#newsText" class="call-editor" data-toggle="tab" >Контент</a></li>
	<li><a href="#newsSource" class="call-source" data-toggle="tab">Исходник</a></li>
	<li><a href="#newsImages" class="call-imgloader" data-toggle="tab">Изображения</a></li>
</ul>

<div class="tab-content">
<div id="newsDescr" class="tab-pane active">
<br />
	<div class="form-group">
	  <label class="col-sm-2 control-label">Дата</label>
	   <div class="col-sm-10"><input type="datetimepicker" class="form-control" name="date" placeholder="Дата" required></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Заголовок</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="header" placeholder="Заголовок" required ></div>
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

<div id="newsText" class="tab-pane">
<textarea name="text" id="text" class="editor" placeholder="Контент" required >{{text}}</textarea>
</div>

<div id="{{form}}Source" class="tab-pane" data-role="include" src="source" data-name="text" data-id="src-{{id}}">
</div>


<div id="newsImages" class="tab-pane" data-role="imageloader"></div>
</div>
</form>
