<div id="{{form}}List" class="row">
		<div class="col-sm-3">
				<div class="themed-background-dark text-light">
					<b class="">Категории
					<a href="#" data-ajax="mode=edit&amp;form=tree&amp;id={{form}}_division" class="text-light pull-right"
					data-toggle="modal" data-target="#treeEdit" data-html="#treeEdit .modal-body">
					<span class="fa fa-gear"></span></a>
					</b>
					
				<ul id="{{form}}Catalog" data-role="tree" from="{{form}}_division" data-build-tree="true" data-add="true" class="sidebar-nav">
					<li>
						<a data-ajax="mode=list&form=prod&division={{id}}" title="{{name}}" data-html="#prodList .list">{{name}}</a>
					</li>
				</ul>
				</div>
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
	#{{form}}List .themed-background-dark {padding:10px;}
	#{{form}}List #{{form}}Catalog {padding-left: 0px;}
	#{{form}}List #{{form}}Catalog a {cursor:pointer;}
	#{{form}}List #{{form}}Catalog ul li {font-weight:normal;width: 100%;line-height: 11px;}
	#{{form}}List #{{form}}Catalog a {display: inline-block; width: 95%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
	#{{form}}List #{{form}}Catalog > li > a {width:100%;}
		
</style>
<script>

	$(document).on("tree_after_formsave",function(event,name,item,form,res){
		template_set_data("#{{form}}Catalog");
	});
</script>
