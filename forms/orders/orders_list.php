          <h2 class="sub-header">Список заказов</h2>
          <div class="table-responsive">
            <table class="table table-striped formlist">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th data-sort="id">Номер</th>
                  <th data-sort="date">Дата</th>
                  <th data-sort="person_name">Контакт</th>
                  <th data-sort="person_phone">Телефон</th>
                </tr>
              </thead>
              <tbody data-role="foreach" form="orders" data-add="true" data-sort="date:d" data-size="15">
                <tr item="{{id}}">
                  <td>
					<div class="dropdown">
					  <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#" data-ajax="mode=edit&form={{form}}&id={{id}}" data-toggle="modal" data-target="#{{form}}Edit" data-html="#{{form}}Edit .modal-body"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
						<li><a href="#" data-ajax="mode=delete&form=admin&formname={{form}}&itemname={{id}}" data-toggle="modal" data-target="DeleteConfirm"><span class="glyphicon glyphicon-remove"></span> Удалить</a></li>
					  </ul>
					</div>
                  </td>
                  <td>{{id}}</td>
                  <td>{{date}}</td>
                  <td>{{person_name}}</td>
                  <td>{{person_phone}}</td>
                </tr>
              </tbody>
            </table>
          </div>

<div data-role="include" src="/engine/forms/form_comModal.php"></div>
