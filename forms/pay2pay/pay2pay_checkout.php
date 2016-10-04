<div class="panel-body">
<form name="PaymentForm" action="{{merchant_url}}" method="POST" style="overflow:hidden; zoom:1;">
	<input type="hidden" name="OrderId" id="OrderId" value="{{order_id}}">
	<input type="hidden" name="Amount" id="Amount" value="{{amount}}">
	<input type="hidden" name="xml" value="{{xml_encoded}}">
	<input type="hidden" name="sign" value="{{sign_encoded}}">
	<h4>Внимание</h4>
	<p>После нажания кнопки "продолжить" вы будете перенаправлены на сайт платёжной системы 
	<a href="http://www.pay2pay.com" target="_blank">Pay2Pay</a>, 
	где сможете выбрать наиболее удобную для Вас форму оплаты заказа и совершить платёж.</p>
	<p>После завершения процедуры оплаты Вы снова будете перенаправлены на наш сайт.</p>
	<p><b>Сумма к оплате: {{amount}} рублей</b></p>
	<p>
		<img src="http://www.pay2pay.com/files/pay2pay_88x31_green.png" width="88" height="31" class="pull-right">
		<input class="btn btn-primary" type="submit" value="Продолжить">
	</p>
</form>
</div>
