          <h2 class="sub-header">Список отзывов</h2>
          <div class="table-responsive">
            <table class="table table-striped formlist">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>Дата</th>
                  <th>Имя</th>
                  <th>Эл.почта</th>
                  <th>Рейтинг</th>
                  <th>Форма</th>
                  <th>Запись</th>
                </tr>
              </thead>
              <tbody  id="comments_list" data-role="foreach" form="comments" data-sort="date:d" data-pagination="15" data-add="true">
				<meta data-role="variable" where=' visible =  "on" ' var="class" value="">
				<meta data-role="variable" where=' visible <> "on" ' var="class" value="text-danger">
                <tr item="{{id}}" class="{{class}}">
                  <td>
					<div class="dropdown">
					  <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#" data-ajax="mode=edit&form=comments&id={{id}}" data-toggle="modal" data-target="#commentsEdit" data-html="#commentsEdit .modal-body"><span class="glyphicon glyphicon-edit"></span> Изменить</a></li>
						<li><a href="#" data-ajax="mode=delete&form=admin&formname={{form}}&itemname={{id}}" data-toggle="modal" data-target="DeleteConfirm"><span class="glyphicon glyphicon-remove"></span> Удалить</a></li>
					  </ul>
					</div>
                  </td>
                  <td>{{date}}</td>
                  <td>{{name}}</td>
                  <td>{{email}}</td>
                  <td>{{rating}}</td>
                  <td>{{target_form}}</td>
                  <td>{{target_id}}</td>
                </tr>
              </tbody>
            </table>
          </div>


<div data-role="include" src="/engine/forms/form_comModal.php"></div>

