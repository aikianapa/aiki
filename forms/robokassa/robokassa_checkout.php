<div class="panel-body">

<form action="{{url}}" method="POST" data-disallow="noname">
	<input type=hidden name=MrchLogin value="{{login}}">
	<input type=hidden name=OutSum value="{{summ}}">
	<input type=hidden name=InvId value="{{inv_id}}">
	<input type=hidden name=Desc value="{{inv_desc}}">
	<input type=hidden name=SignatureValue value="{{crc}}">
	<input type=hidden name=IncCurrLabel value="{{currency}}">
	<input type=hidden name=Culture value="{{culture}}">
	<input type=hidden name=IsTest value="{{test}}">
	<input type=hidden name=Shp_orderId value="{{Shp_orderId}}">

	<h4>Внимание</h4>
	<p>После нажания кнопки "продолжить" вы будете перенаправлены на сайт платёжной системы
	<a href="http://www.robocassa.com" target="_blank">RoboKassa</a>,
	где сможете выбрать наиболее удобную для Вас форму оплаты заказа и совершить платёж.</p>
	<p>После завершения процедуры оплаты Вы снова будете перенаправлены на наш сайт.</p>
	<p><b>Сумма к оплате: {{summ}} рублей</b></p>
	<p>
		<img src="http://robokassa.ru/ru/Images/logo.png" style="width: 200px;" class="pull-right">
		<input class="btn btn-primary" type="submit" value="Продолжить">
	</p>
</form>

<div data-allow="noname">
	<h4>Внимание</h4>
	<p>Оплата доступна только авторизованым пользователям.</p>
	<p>Если вы уже зарегистрированы в системе,
	пожалуйста, авторизуйтесь, нажав кнопку "Войти", если у вас ещё нету учётной записи,
	пожалуйста, нажмите кнопку "Регистрация".</p>
	<a href="/login.htm" class="btn btn-success"><i class="ti-check"></i> Войти</a>
	<a class="btn btn-raised btn-primary ripple-effect checkout" href="#"
	data-ajax="mode=reg&amp;form=users" data-html="#myModal .panel-default">
	<i class="ti-user"></i> Регистрация</a>
</div>


</div>
