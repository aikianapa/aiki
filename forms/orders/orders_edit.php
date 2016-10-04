
<form id="{{form}}EditForm" name="{{form}}" item="{{id}}"  class="form-horizontal" role="form">
<ul class="nav nav-tabs">
	<li class="active"><a href="#commonDescr" data-toggle="tab">Характеристики</a></li>
	<li><a href="#commonPerson" class="call-imgloader" data-toggle="tab">Клиент</a></li>
	<li><a href="#commonText" class="call-editor" data-toggle="tab" >Контент</a></li>
</ul>

<div class="tab-content">
<br />
	<div class="form-group">
	  <label class="col-sm-2 control-label">ID заказа</label>
	   <div class="col-sm-10"><input type="text" class="form-control" name="id" placeholder="Имя записи" readonly ></div>
	</div>
<div id="commonDescr" class="tab-pane active" data-role="cart">
	<table class="table table-striped table-hover table-condensed">
              <tbody data-role="foreach" from="items">
                <tr data-role="formdata" form="{{form}}" item="{{id}}" class="cart-item">
                  <td class="details">
                    <div class="clearfix">
						<div>{{name}}</div>
						<div class="pull-left" data-role="foreach" from="prices" index="{{%idx}}">
							  {{descr}}
						</div>

                    </div>
                  </td>
                  <td>
                    <input type="text" value="{{quant}}" name="quant" class="cart-item-quant form-control">
                  </td>
                  <td>
                          <a class="btn btn-success cart-item-plus"><i class="glyphicon glyphicon-plus"></i></a>
                          <a class="btn btn-primary cart-item-minus"><i class="glyphicon glyphicon-minus"></i></a>
                          <a class="btn btn-danger cart-item-remove"><i class="glyphicon glyphicon-trash"></i></a>
                  </td>
                  <td class="cart-item-price">{{price}}</td>
                  <td class="cart-item-total"></td>
                </tr>
              </tbody>
	<tfoot>
		<tr>
		<td colspan="4">Итого:</td>
		<td class="cart-total"></td>
		</tr>	
	</tfoot>
	</table>
</div>

<div id="commonPerson" class="tab-pane">
	<div class="clearfix">
		<div class="form-group">
		  <label class="col-sm-2 control-label">Ваше имя</label>
		   <div class="col-sm-10"><input type="text" class="form-control" name="person[name]" value="{{_COOK[person_name]}}" required placeholder="Ваше имя"></div>
		</div>

		<div class="form-group">
		  <label class="col-sm-2 control-label">Телефон</label>
		   <div class="col-sm-4"><input type="phone" class="form-control" name="person[phone]" value="{{_COOK[person_phone]}}" required placeholder="Телефон" ></div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label">Эл.почта</label>
		   <div class="col-sm-4"><input type="email" class="form-control" name="person[email]" value="{{_COOK[person_email]}}" placeholder="Эл.почта" ></div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label">Город</label>
		   <div class="col-sm-4"><input type="text" class="form-control" name="person[city]" value="{{_COOK[person_city]}}" required placeholder="Город доставки" ></div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label">Адрес</label>
		   <div class="col-sm-10"><input type="text" class="form-control" name="person[address]" value="{{_COOK[person_address]}}" required placeholder="Адрес доставки" ></div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label">Комментарии</label>
		   <div class="col-sm-10"><textarea class="form-control" name="person[comment]" placeholder="Комментарии к заказу" ></textarea></div>
		</div>
		
	</div>
</div>

<div id="commonText" class="tab-pane">
<textarea name="text" id="text" class="editor" placeholder="Контент" required >{{text}}</textarea>
</div>


</div>
</form> 
<script>
active_cart();
</script>
