<section id="content">
	<meta data-role="variable" var="header" value="Обратная связь">
	<h3 data-role="where" data='"{{header}}">""'>{{header}}</h3>
	<p>Вы можете задать вопрос или оставить свой отзыв.</p>
	<hr>
	<form id="contactForm" name="comments"  class="form-horizontal" item="_new">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<input class="form-control input-lg" name="name" type="text" placeholder="Введите ваше имя" required>
						<small>Ваше имя</small>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<input class="form-control input-lg" name="email" type="text" placeholder="Введите ваш Email" required>
						<small>Ваш адрес электронной почты</small>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<textarea rows="10" class="form-control input-lg" name="text" data-not-exclude type="text" placeholder="Ваш отзыв или комментарий" required>{{text}}</textarea>
						<small>Текст сообщения</small>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="form-group">
				<label class="col-xs-4 col-sm-2 norobot control-label">Я не робот</label>
			<div class="col-xs-4 col-sm-1 col-md-1 col-lg-1 norobot">
				<input type="checkbox" name="norobot" class="form-control" required>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 sendbutton">
				<a data-formsave="#contactForm" disabled class="btn btn-icon btn-primary"><i class="fa fa-inbox"></i> Отправить</a>
			</div>
			</div>
		</div>
	</form>
	<div class="alert alert-success hidden">Ваш отзыв успешно отправлен Администратору!</div>
	<div class="alert alert-danger hidden">Ваш отзыв не получилось отправить. Попробуйте позже!</div>
</section>
