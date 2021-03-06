          <h2 class="sub-header">Список пользователей</h2>
          <div class="table-responsive">
            <table class="table table-striped formlist userlist">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th data-sort="name">Имя</th>
                  <th data-sort="role">Роль</th>
                  <th>Телефон</th>
                  <th data-sort="email">Эл.почта</th>
                </tr>
              </thead>
              <tbody  data-role="foreach" from="result" data-add="true" data-size="10">
                <tr item="{{id}}" class="active-{{active}}">
                  <td>
					<div class="dropdown">
					  <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#" data-ajax="mode=edit&form={{form}}&id={{id}}" data-toggle="modal" data-target="#{{form}}Edit" data-html="#{{form}}Edit .modal-body"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
						<li><a href="#" data-ajax="mode=delete&form=admin&formname={{form}}&itemname={{id}}" data-toggle="modal" data-target="DeleteConfirm"><span class="glyphicon glyphicon-remove"></span> Удалить</a></li>
					  </ul>
					</div>
                  </td>
                  <td>{{name}}</td>
                  <td>{{role}}</td>
                  <td>{{phone}}</td>
                  <td>{{email}}</td>
                </tr>
              </tbody>
            </table>
          </div>

<div data-role="include" src="/engine/forms/form_comModal.php"></div>
