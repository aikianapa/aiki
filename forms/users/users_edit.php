
<form id="{{form}}EditForm" name="{{form}}" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#{{form}}Descr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#{{form}}Self" data-toggle="tab" >Личные данные</a></li>
	<li><a href="#{{form}}Text" class="call-editor" data-toggle="tab" >Контент</a></li>
</ul>

<div class="tab-content">
<br />
	<div class="form-group">
	  <label class="col-sm-2 control-label">Ф.И.О.</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="name" placeholder="Имя пользователя" required ></div>
	</div>

<div id="{{form}}Descr" class="tab-pane active">
	<div class="form-group user-role">
	  <label class="col-sm-2 control-label">Привилегии</label>
	  <div class="col-sm-10">
		  <select name="role" class="form-control" data-role="dict" from="user_role" data-enabled="admin">
			  <option value="{{code}}">{{name}}</option>
		  </select>
		</div>
	</div>

	<div data-role="include" src="/engine/forms/users/users_login.php" data-hide="*"></div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Активная</label>
		<div class="col-sm-2 "><label class="switch switch-primary"><input type="checkbox" name="active" ><span></span></label></div>
	</div>
</div>

<div id="{{form}}Text" class="tab-pane">
<textarea name="text" id="text" class="editor" placeholder="Контент" required >{{text}}</textarea>
</div>
<div id="{{form}}Self" class="tab-pane">
	<div class="form-group">
	  <label class="col-sm-2 control-label" data-role="where" data=' "{{_SETT[elogin]}}" <> "email" '>Эл.почта</label>
		<div class="col-sm-4" data-role="where" data=' "{{_SETT[elogin]}}" <> "email" '>
		<input type="text" class="form-control" name="email" placeholder="Электронная почта" data-enabled="admin" required ></div>
	  <label class="col-sm-2 control-label">Телефон</label>
	   <div class="col-sm-4"><input type="text" class="form-control" name="phone" placeholder="Телефон" required ></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Индекс</label>
	   <div class="col-sm-2"><input type="text" class="form-control" name="zip" placeholder="Почтовый индекс"  ></div>
	  <label class="col-sm-1 control-label">Город</label>
	   <div class="col-sm-7"><input type="text" class="form-control" name="city" placeholder="Населёный пункт"></div>
	</div>

	<div class="form-group">
	  <label class="col-sm-2 control-label">Адрес</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="address" placeholder="Улица, дом, корпус, кваратира" ></div>
	</div>

</div>
</div>
</form>
