<div id="{{form}}List" class="col-sm-12">
		<div class="col-sm-3">
			<h2 class="sub-header">Категории</h2>
			
			<ul id="{{form}}Catalog" data-role="tree" from="{{form}}_division" data-build-tree="true" data-add="true">
				<li>
					<a data-ajax="mode=list&form=prod&division={{id}}" data-html="#prodList .list"><i class="fa fa-angle-right"></i> {{name}}</a>
				</li>
			</ul>
			
			<a href="#" data-ajax="mode=edit&amp;form=tree&amp;id={{form}}_division" class="btn btn-primary btn-sm"
				data-toggle="modal" data-target="#treeEdit" data-html="#treeEdit .modal-body">
				<span class="glyphicon glyphicon-edit"></span> Изменить</a>
			<div data-role="include" src="modal" data-id="treeEdit" data-formsave="#treeEditForm" data-add="false" data-header="Категории"></div>
		</div>
		<div class="col-sm-9" class="list">
	
			  <h2 class="sub-header">Список продукции</h2>
			  <div class="table-responsive">
				<table class="table table-striped formlist">
				  <thead>
					<tr>
					  <th>&nbsp;</th>
					  <th data-sort="name">Наименование</th>
					  <th data-sort="price">Цена</th>
					</tr>
				  </thead>
				  <tbody  data-role="foreach" from="result" data-add="true" data-sort="name" data-size="15">
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
					  <td>{{name}}</td>
					  <td align="right">{{price}}</td>
					</tr>
				  </tbody>
				</table>
			  </div>
		</div>
</div>
<div data-role="include" src="/engine/forms/form_comModal.php"></div>

<style>
	#{{form}}List ul {padding-left: 0px;}
	#{{form}}List ul li > ul {padding-left: 20px;}
	#{{form}}List ul li a {cursor:pointer;}
	#{{form}}List ul li {font-weight:normal;}
	#{{form}}List ul li.parent {font-weight:bold;}
	#{{form}}List ul {list-style-type:none;}
</style>
<script>
	
	$(document).on("tree_after_formsave",function(event,name,item,form,res){
		template_set_data("#{{form}}Catalog");
	});
</script>
