<div id="success">
			<h3 class='text-primary'>Готово!</h3>
			<p>На адрес электронной почты <strong>{{_POST[email]}}</strong> выслано письмо со ссылкой для активации Вашей учётной записи. <br>
			<strong class="text-danger">Внимание!</strong> Войдите в Ваш почтовый ящик, откройте письмо и <u>перейдите по ссылке активации</u>.</p>
			<div class='clearfix'><br></div>
			<div class='clearfix'><a href='#' class='btn btn-success pull-right' data-dismiss='modal'>Закрыть</a></div>
</div>
<div id="error_email">
			<h3 class='text-danger'>Ошибка!</h3>
			<p>Пользователь с адресом электронной почты <strong>{{_POST[email]}}</strong> уже зарегистрирован в системе.</p>
			<div class='clearfix'><br></div>
			<div class='clearfix'><a href='#' class='btn btn-success pull-right' data-dismiss='modal'>Закрыть</a></div>
</div>
<div id="error_login">
			<h3 class='text-danger'>Ошибка!</h3>
			<p>Пользователь с логином <strong>{{_POST[login]}}</strong> уже зарегистрирован в системе.</p>
			<div class='clearfix'><br></div>
			<div class='clearfix'><a href='#' class='btn btn-success pull-right' data-dismiss='modal'>Закрыть</a></div>
</div>
<div id="mail_text">
	<h3>Благодарим за регистрацию!</h3>
	<p>Вы получили данное письмо, так как Ваш адрес электронной почты был использован при регистрации
	на сайте <a href="http://{{_SESS[HTTP_HOST]}}" target="_blank">{{_SESS[HTTP_HOST]}}</a>.</p>
	<p>Для активации Вашей учётной записи, пожалуйста, перейдите по следующей ссылке:
		<a href="{{link}}" target="_blank">{{link}}</a>
	</p>
	<p>После активации Вы сможете войти в свой личный кабинет используя следующие учётные данные:
		<br><strong>Логин</strong>: {{_POST[login]}}
		<br><strong>Пароль</strong>: {{_POST[password]}}
	</p>


	<p>Если Вы не производили регистрацию на нашем сайте, то просто удалите это письмо.</p>
	<p>С уважением, Администрация.</p>
</div>

<div id="mail_pwdchange">
	<h3>Изменение пароля учётной записи!</h3>
	<p>Вы получили данное письмо, так как Ваш адрес электронной был указан с процедуре смены пароля учётной записи
	на сайте <a href="http://{{_SESS[HTTP_HOST]}}" target="_blank">{{_SESS[HTTP_HOST]}}</a>.</p>
	<p>Для подтверждения изменения пароля, пожалуйста, перейдите по следующей ссылке:
		<a href="{{link}}" target="_blank">{{link}}</a>
	</p>

	<p>Если Вы не запрашивали изменение пароля учётной записи, то просто удалите это письмо.</p>
	<p>С уважением, Администрация.</p>
</div>
