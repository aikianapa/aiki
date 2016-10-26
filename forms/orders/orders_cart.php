<div data-role="cart">
	 <div class="cart-table">
			<div data-role="foreach" from="items">
				<div class="cart-item row" data-role="formdata" form="prod" item="{{id}}">
					<div class="col-sm-2 col-xs-12">
						<a href="/prod/show/{{id}}.htm">
							<img alt="" data-role="thumbnail" size="300px;300px;src" contain="true" offset="50%;50%" class="img-responsive" src="0">
						</a>
					</div>
					<div class="col-sm-10 col-xs-12">
						<div class="col-sm-5 col-xs-12">
							<a href="/prod/show/{{id}}.htm">{{name}}</a>
						</div>
						<div class="col-sm-2 col-xs-3 text-center cart-item-price">
							{{%price}}
						</div>
						<div class="col-sm-3 col-xs-6 text-center">
								<input type="text" name="quant" class="cart-item-quant" placeholder="{{%quant}}">
								<div>
									<br>
									<a class="btn btn-success cart-item-plus"><i class="glyphicon glyphicon-plus"></i></a>
									<a class="btn btn-primary cart-item-minus"><i class="glyphicon glyphicon-minus"></i></a>
									<a class="btn btn-danger cart-item-remove"><i class="glyphicon glyphicon-trash"></i></a>
								</div>
						</div>
						<div class="col-sm-2 col-xs-3 text-center cart-item-total"></div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 text-right">
				<h5>ИТОГО: <span class="cart-total"></span></h5>
			</div>
			<div class="col-xs-12 actions"> 
				<div class="col-sm-4 col-xs-12 text-center"><a href="/" class="btn btn-primary">Продолжить покупки</a></div>
				<div class="col-sm-4 col-xs-12 text-center"><a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalOrder">Оформить заказ</a></div>
				<div class="col-sm-4 col-xs-12 text-center"><a href="#" class="btn btn-danger cart-clear">Опустошить корзину</a></div>
			</div>
	</div>
	<div class="cart-success alert alert-info row" style="display:none;">
		<div class="col-xs-1">
			<p><i class="fa fa-info-circle fa-2x"></i></p>
		</div>
		<div class="col-xs-11">
			<p>Ваш заказ успешно отправлен менеджеру. В ближайшее время с вами обязательно свяжутся по телефону, указанному в заказе.</p>
		</div>
	</div>
	
</div>

<div data-role="include" src="modal" data-id="modalOrder" data-formsave="#orderForm" data-header="Оформление заказа" data-hide="*">
	<div data-role="include" src="/engine/forms/orders/order.inc.php" data-hide="data-role,src" append=".modal-body"></div>
</div>

