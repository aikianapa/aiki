<form id="commentsEditForm" name="comments" item="{{id}}"  class="form-horizontal" role="form">
	<input type="hidden" name="date">
	<input type="hidden" name="user_id" value="{{_SESS[user_id]}}">
	<input type="hidden" name="target_form" value="{{_GET[form]}}">
	<input type="hidden" name="target_id" value="{{_GET[id]}}">
	<div class="form-group">
	  <label class="col-sm-2 control-label">Ваше имя</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="name" placeholder="Ваше имя" required value="{{_COOK[person_name]}}"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Эл.почта</label>
	   <div class="col-sm-10"><input type="email" class="form-control" name="email" placeholder="Электронная почта" required value="{{_COOK[person_email]}}"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Телефон</label>
	   <div class="col-sm-10"><input type="phone" class="form-control" name="phone" placeholder="Контактный телефон" value="{{_COOK[person_phone]}}"></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">Ваш отзыв</label>
		<div class="col-sm-10">
		   <textarea name="text" class="form-control" rows="5" required placeholder="Ваш отзыв"></textarea>
		</div>
	</div>

	<div class="form-group comments-rating">
	  <label class="col-sm-2 control-label">Рейтинг</label>
		<div class="col-sm-10">
		   <input type="hidden" name="rating" data-fractions="2" >
		</div>
	</div>

	<div class="form-group" data-allow="admin moder">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="checkbox">
				<label><input type="checkbox" name="visible"> опубликовать</label>
			</div>
		</div>
	</div>
	<div class="form-group"  data-allow="admin moder">
	  <label class="col-sm-2 control-label">Ответ</label>
		<div class="col-sm-10">
		   <textarea name="reply" class="form-control" rows="3" placeholder="Ответ на отзыв"></textarea>
		</div>
	</div>

	<div class="form-group" data-disallow="admin">
		<label class="col-sm-2 control-label">Я не робот</label>
		<div class="col-sm-1 col-md-1 col-lg-1 norobot">
			<input type="checkbox" name="norobot" class="form-control" >
		</div>
		<div class="col-sm-3 sendbutton hidden">
		   <a class="btn btn-primary btn-block" data-formsave="#commentsEditForm">Отправить отзыв</a>
		</div>
	</div>

</form>

<style>
.comments-rating .rating-symbol {font-size:25px; color:#FFA500;}
</style>
