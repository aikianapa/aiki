<form id="orderForm" name="orders" item="{{_SESS[order_id]}}" class="form-horizontal" role="form">
<input type="hidden" name="order" value="on">
<div data-role="include" src="/engine/forms/orders/orders_edit.php">
	<include outer="#commonPerson"></include>
	<center><small>Оформляя заказ вы подтверждаете согласие на обработку персональных данных согласно закону РФ № 152-ФЗ</small></center>
</div>
</form>

