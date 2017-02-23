          <h2 class="sub-header">Список записей</h2>
          <div class="table-responsive">
            <table class="table table-striped formlist">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th data-sort="id">Имя</th>
                  <th data-sort="header">Заголовок</th>
                </tr>
              </thead>
              <tbody  data-role="foreach" form="{{_form_}}" data-add="true" data-sort="id" data-size="15">
                <tr item="{{id}}">
                  <td>
					<div class="dropdown">
					  <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#" data-ajax="mode=edit&form={{_form_}}&id={{id}}" data-toggle="modal" data-target="#{{_form_}}Edit" data-html="#{{_form_}}Edit .modal-body"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
						<li><a href="#" data-ajax="mode=delete&form=admin&formname={{_form_}}&itemname={{id}}" data-toggle="modal" data-target="DeleteConfirm"><span class="glyphicon glyphicon-remove"></span> Удалить</a></li>
					  </ul>
					</div>
                  </td>
                  <td>{{id}}</td>
                  <td>{{header}}</td>
                </tr>
              </tbody>
            </table>
          </div>

<div data-role="include" src="/engine/forms/form_comModal.php"></div>
