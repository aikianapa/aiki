          <h2 class="sub-header">Список новостей</h2>
          <div class="table-responsive">
            <table class="table table-striped formlist">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th data-sort="datesort:d">Дата</th>
                  <th data-sort="header">Заголовок</th>
                </tr>
              </thead>
              <tbody  data-role="foreach" form="news" data-sort="datesort:d" data-size="15">
                <tr item="{{id}}">
                  <td>
					<div class="dropdown">
					  <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#" data-ajax="mode=edit&form=news&id={{id}}" data-toggle="modal" data-target="#newsEdit" data-html="#newsEdit .modal-body"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
						<li><a href="#" data-ajax="mode=delete&form=admin&formname={{form}}&itemname={{id}}" data-toggle="modal" data-target="DeleteConfirm"><span class="glyphicon glyphicon-remove"></span> Удалить</a></li>
					  </ul>
					</div>
                  </td>
                  <td>{{date}}</td>
                  <td>{{header}}</td>
                </tr>
              </tbody>
            </table>
          </div>


<div data-role="include" src="/engine/forms/form_comModal.php"></div>
