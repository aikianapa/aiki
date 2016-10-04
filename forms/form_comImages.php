<input type="hidden" name="images" data-role="imagestore">

	<div class="form-group">
	  <label class="col-sm-2 control-label">Галерея</label>
	   <div class="col-sm-3">
			<select name="images_position" class="form-control">
			<option value="">Нет</option>
			<option value="top">Сверху</option>
			<option value="bottom">Снизу</option>
			</select>
	   </div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">В текст</label>
		<div class="col-sm-3">
			<select name="intext_position[pos]" class="form-control">
			<option value="">Нет</option>
			<option value="left">Слева</option>
			<option value="right">Справа</option>
			</select>
		</div>
		<label class="col-sm-2 control-label">Размер (Ш/В)</label>
		<div class="col-sm-2"><input type="number" class="form-control" placeholder="200" name="intext_position[width]"></div>
		<div class="col-sm-2"><input type="number" class="form-control" placeholder="160" name="intext_position[height]"></div>
	</div>

<div id="comImagesUpl" data-role="tabpanel">

<div id="filelist" class="list-group">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />
<div id="uploader">
    <a id="pickfiles" class="btn btn-default hidden" href="javascript:;">Выбрать</a>
    <a id="uploadfiles" class="btn btn-default pull-left" href="javascript:;" style="z-index:100;">Загрузить</a>
    <p>Перетащите мышкой файлы в этот прямоугольник<br>
    или кликните по нему, чтобы найти файлы</p>
</div>
<br />
<pre id="console"></pre>
<div id="comImagesAll">
<li class="imagesAttr col-md-12">
	<div class="header">
		<button type="button" class="close" aria-hidden="true">&times;</button>
		<h4 class="title">Атрибуты изображения</h4>
	</div>

	<div class="form-group"><label class="col-sm-3 control-label">Ссылка</label>
	<div class="col-sm-9"><input type="text" class="form-control attr-link" readonly></div>
	</div>

	<div class="form-group"><label class="col-sm-3 control-label">Заголовок</label>
	<div class="col-sm-9"><input type="text" class="form-control attr-title" placeholder="Заголовок"></div>
	</div>

	<div class="form-group"><label class="col-sm-3 control-label">Описание</label>
	<div class="col-sm-9"><textarea type="text" class="form-control attr-alt" placeholder="Описание"></textarea></div>
	</div>

</li>
<ul class="gallery"></ul>
</div>
