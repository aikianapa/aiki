<title>Заказ № {{id}} от {{date}} ({{_SESS[HTTP_HOST]}})</title>
<div id="order" data-role="cart">
	<table class="cart-detail">
	<tr><td>Имя</td><td>{{person[name]}}</td></tr>
	<tr><td>Телефон</td><td>{{person[phone]}}</td></tr>
	<tr><td>Эл.почта</td><td>{{person[email]}}</td></tr>
	<tr><td>Город</td><td>{{person[city]}}</td></tr>
	<tr><td>Адрес</td><td>{{person[address]}}</td></tr>
	<tr><td>Комментарий</td><td>{{person[comment]}}</td></tr>
	</table>
	
	
	<table border=1 class="cart-table table table-striped">
		<thead>
			<tr>
				<th>Наименование</th>
				<th class="text-center">Цена</th>
				<th class="text-center">Кол-во</th>
				<th class="text-center">Сумма</th>
			</tr>
		</thead>
		<tbody data-role="foreach" from="items" data-total="summ" data-group="*">
			<tr class="cart-item" data-role="formdata" form="prod" item="{{id}}">
				<td>
					<a href="http://{{_SESS[HTTP_HOST]}}/prod/show/{{id}}.htm">{{name}}</a>
				</td>
				<td align="center" data-fld="price">
					<div class="cart-item-price">{{%price}}</div>
				</td>
				<td align="center" data-fld="quant">{{%quant}}</td>
				<td align="center" data-fld="summ" data-eval="true">
					{{%price}}*{{%quant}}
				</td>
			</tr>
		</tbody>
	</table>
</div>
<style>
	#order table th {text-align:center;}
	#order .cart-table {width:100%;}
	#order .cart-detail tr td:first-child {font-weight:bold;}
</style>
