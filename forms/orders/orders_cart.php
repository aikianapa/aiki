<div class="table-responsive"  data-role="cart">
	<table class="cart-table table table-striped" style="width:100%;">
		<thead>
			<tr>
				<th>Фото</th>
				<th>Наименование</th>
				<th class="text-right">Цена</th>
				<th class="text-center">Кол-во</th>
				<th class="text-right">Сумма</th>
			</tr>
		</thead>
		<tbody>
			<div data-role="foreach" from="items">
			<tr class="cart-item" data-role="formdata" form="prod" item="{{id}}">
				<td>
					<a href="/prod/show/{{id}}.htm">
						<img alt="" data-role="thumbnail" size="90px;90px;src" class="img-responsive" src="0">
					</a>
				</td>
				<td>
					<a href="/prod/show/{{id}}.htm">{{name}}</a>
				</td>
				<td align="center">
					<div class="cart-item-price">{{%price}}</div>
				</td>
				<td align="center">
						<input type="text" name="quant" class="cart-item-quant" placeholder="{{%quant}}">
						<p>
							<br>
							<a class="btn btn-success cart-item-plus"><i class="glyphicon glyphicon-plus"></i></a>
							<a class="btn btn-primary cart-item-minus"><i class="glyphicon glyphicon-minus"></i></a>
							<a class="btn btn-danger cart-item-remove"><i class="glyphicon glyphicon-trash"></i></a>
						</p>
				</td>
				<td class="text-center">
					<div class="cart-item-total"></div>
				</td>
			</tr>
			</div>
			<tr>
				<td colspan="4">ИТОГО:</td>
				<td class="cart-total"></td>
			</tr>
			<tr>
			<td colspan="5" class="actions">
				<a href="/" class="btn btn-primary">Продолжить покупки</a>&nbsp;
				<a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalOrder">Оформить заказ</a>&nbsp;
				<a href="#" class="btn btn-danger cart-clear">Опустошить корзину</a>
			</td>
			</tr>

		</tbody>
	</table>
	<div class="cart-success alert alert-info" style="display:none;">
		<i class="fa fa-info-circle fa-2x pull-right"></i>
		Ваш заказ успешно отправлен менеджеру. В ближайшее время с вами обязательно свяжутся по телефону, указанному в заказе.
	</div>
	
</div>

<div data-role="include" src="modal" data-id="modalOrder" data-formsave="#orderForm" data-header="Оформление заказа" data-hide="*">
	<div data-role="include" src="/engine/forms/orders/order.inc.php" data-hide="data-role,src" append=".modal-body"></div>
</div>

