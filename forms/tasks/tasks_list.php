<style>
	#page-content-sidebar { top: 20px!important; min-height:400px!important;}

	.task-list li > a {position: absolute; opacity: 0; -webkit-transition: opacity .15s ease-out; transition: opacity .15s ease-out; }
	.task-list li:hover > a {opacity: 1;}
	.task-list li > .task-edit {top: 11px; right: 30px;}
	.task-list li > .task-menu {top: 11px; right: 50px;}
	
	.task-list [contenteditable] {cursor:text;}
	.task-list .checkbox-inline {padding-right: 20px;}
</style>

                <div id="sidebar-alt" tabindex="-1" aria-hidden="true">
                    <!-- Toggle Alternative Sidebar Button (visible only in static layout) -->
                    <a href="javascript:void(0)" id="sidebar-alt-close" onclick="App.sidebar('toggle-sidebar-alt');"><i class="fa fa-times"></i></a>

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll-alt">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Profile -->
                            <div class="sidebar-section" id="task-form">
								<script type="template" id="task-tpl" >
									<h2 class="text-light">Задача</h2>
									<form method="post" id="taskForm" name="tasks" class="form-control-borderless" onsubmit="return false;">
										<div class="form-group">
											<textarea name="task" class="form-control"></textarea>
										</div>
										<div class="form-group">
											<label>Время</label>
											<input type="datetimepicker" name="time" class="form-control">
										</div>
										<div class="form-group">
											<label for="side-profile-password">Категория</label>
											<select name="category" class="form-control">
												<option value="id">{{name}}</option>
											</select>
											<option prepend="select[name=category]" value="id">{{name}}</option>
										</div>
										<div class="form-group">
											<label>Статус</label>
											<input type="text" name="status" class="form-control">
										</div>
										
										<div class="form-group">
											<label class="col-xs-7 control-label-fixed">Завершена</label>
											<div class="col-xs-5">
												<label class="switch switch-success"><input type="checkbox" name="done"><span></span></label>
											</div>
										</div>
										
										<div class="form-group remove-margin">
											<button type="button" class="btn btn-effect-ripple btn-primary" 
											data-formsave="#taskForm"
											onclick="App.sidebar('close-sidebar-alt');">Сохранить</button>
										</div>
									</form>
                                </script>
                                <section>
								</section>
                            </div>
                            <!-- END Profile -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
 

                    <div id="page-content" class="inner-sidebar-left">
                        <!-- Inner Sidebar -->
                        <div id="page-content-sidebar">
                            <!-- Collapsible Options -->
                            <a href="javascript:void(0)" class="btn btn-block btn-default visible-xs" data-toggle="collapse" data-target="#todo-options">Опции</a>
                            <div id="todo-options" class="collapse navbar-collapse remove-padding">
                                <!-- Lists -->
                                <h4 class="inner-sidebar-header">
									<a href="#" data-ajax="mode=edit&amp;form=tree&amp;id=tasks" 
												class="btn btn-xs btn-default pull-right" 
												data-toggle="modal" data-target="#treeEdit" data-html="#treeEdit .modal-body">
												<i class="fa fa-gear"></i>
									</a>
                                    Категории
                                </h4>
                                <div class="block-section">
									
                                    <ul class="nav nav-pills nav-stacked" id="tasksCatalog" data-role="tree" from="tasks" data-build-tree="true" data-add="true">
                                        <li data-id="{{id}}">
                                            <a href="javascript:void(0)">
                                                <span class="badge pull-right">16</span>
                                                <i class="fa fa-briefcase fa-fw icon-push"></i> <strong>{{name}}</strong>
                                            </a>
                                        </li>
                                    </ul>
                                    
									<li class="active" prepend="#tasksCatalog" data-id="unsorted">
										<a href="javascript:void(0)">
											<span class="badge pull-right">0</span>
											<i class="fa fa-briefcase fa-fw icon-push"></i> <strong>Неразобранные</strong>
										</a>
									</li>
                                    
                                </div>
                                <!-- END Lists -->
                            </div>
                            <!-- END Collapsible Options -->
                        </div>
                        <!-- END Inner Sidebar -->

                        <!-- Tasks List -->
                        <!-- Add new task functionality (initialized in js/pages/readyTasks.js) -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
                                <form id="add-task-form" class="push">
                                    <input type="text" id="add-task" name="add-task" class="form-control input-lg" placeholder="Введите задачу..">
                                </form>
                                <ul class="task-list" data-role="foreach" form="tasks" data-sort="time:d">
									<meta data-role="variable" var="class" value="task-done" where='done<>""'>
                                    <li item="{{id}}" data-id="{{id}}" class="{{class}}" 
										data-category="{{category}}"
										data-time="{{time}}">
                                        <a href="javascript:void(0)" class="task-menu text-primary"><i class="fa fa-bars"></i></a>
                                        <a href="javascript:void(0)" class="task-edit text-success"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="task-close text-danger"><i class="fa fa-times"></i></a>
                                        <label class="checkbox-inline">
                                            <input type="checkbox"> <span>{{task}}</span><br>
                                            <i class="fa fa-clock-o"></i> <small>{{timeview}}</small>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- END Task List -->
                    </div>
                    
					<div data-role="include" src="modal" data-id="treeEdit" data-formsave="#treeEditForm" data-add="false" data-header="Категории" class="loaded"><div class="modal fade" id="treeEdit" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="comModalLabel" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> <h4 class="modal-title" id="comModalLabel">Категории</h4> </div> <div class="modal-body"> </div> <div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Закрыть</button> <button type="button" class="btn btn-primary" data-formsave="#treeEditForm" data-add="false"><span class="glyphicon glyphicon-ok"></span> Сохранить изменения</button> </div> </div> </div> </div></div>
                    
                    
					<script src="/engine/forms/tasks/js/tasks.js"></script>
					<script>$(function(){ CompTodo.init(); });</script>

