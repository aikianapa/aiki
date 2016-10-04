<form id="dictEditForm" name="dict"  item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#{{form}}Descr" data-toggle="tab">Справочник</a></li>
	<li><a href="#{{form}}Data" data-toggle="tab" >Данные</a></li>
</ul>

<div class="tab-content">
	<div id="{{form}}Descr" class="tab-pane active">
	<br />
	<div class="form-group">
	  <label class="col-sm-2 control-label">Наименование</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя справочника" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Описание</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="descr" placeholder="Описание"></div>
	</div>

		<div data-role="multiinput" name="fields" class="ui-sortable">
			<input type="text" name="fldname" placeholder="Имя поля">
			<input type="text" name="fldlabel" placeholder="Текстовая метка">
			<select type="text" name="fldtype" placeholder="Тип поля">
			<option value="text">text</option>
			<option value="number">number</option>
			<option disabled>--== Плагины ==--</option>
			<option value="phone">phone</option>
			<option value="datepicker">datepicker</option>
			<option value="datetimepicker">datetimepicker</option>
			<option disabled>--== Другие ==--</option>
			<option value="date">date</option>
			<option value="datetime">datetime</option>
			<option value="month">month</option>
			<option value="week">week</option>
			<option value="time">time</option>
			<option value="email">email</option>
			<option value="tel">tel</option>
			<option value="url">url</option>
			<option value="search">search</option>
			<option value="color">color</option>
			</select>
			<input type="text" name="flddescr" placeholder="Комментарий" >

		</div>
		<div class="data-cache hidden">{{data}}</div>
	</div>
	<div id="{{form}}Data" class="tab-pane">
		<div name="data" data-role="multiinput">

		</div>
	</div>

</div>

</form>
<script>
	$(".nav-tabs li.set_active a").trigger("click");
</script>
