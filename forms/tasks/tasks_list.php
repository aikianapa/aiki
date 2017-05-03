<style>
	#page-content-sidebar { top: 20px!important; min-height:400px!important;}

	.task-list li > a {position: absolute; opacity: 0; -webkit-transition: opacity .15s ease-out; transition: opacity .15s ease-out; }
	.task-list li:hover > a {opacity: 1;}
	.task-list li > .task-edit {top: 11px; right: 30px;}
	.task-list li > .task-menu {top: 11px; right: 50px;}
	
	.task-list [contenteditable] {cursor:text;}
	.task-list .checkbox-inline {padding-right: 20px;}
	
	.task-list .bg- {background:#fff;}
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
											<textarea name="task" rows="5" class="form-control"></textarea>
										</div>
										<div class="form-group">
											<label>Время</label>
											<input type="datetimepicker" name="time" class="form-control">
										</div>
										<div class="form-group">
											<label for="side-profile-password">Категория</label>
											<select name="category" class="form-control" data-role="foreach" data-form="users" item="{{_SESS[user_id]}}" field="tasks_categories">
												<option value="{{id}}">{{category}}</option>
											</select>
											<option prepend="select[name=category]" value="unsorted">Неразобранные</option>
										</div>
										<div class="form-group status">
											<label>Статус</label>
											<input type="hidden" name="status" class="form-control">
											<div class="form-control">
											<button class="btn btn-xs btn-default" data="default">&nbsp;&nbsp;&nbsp;</button>
											<button class="btn btn-xs btn-primary" data="primary">&nbsp;&nbsp;&nbsp;</button>
											<button class="btn btn-xs btn-success" data="success">&nbsp;&nbsp;&nbsp;</button>
											<button class="btn btn-xs btn-info" data="info">&nbsp;&nbsp;&nbsp;</button>
											<button class="btn btn-xs btn-warning" data="warning">&nbsp;&nbsp;&nbsp;</button>
											<button class="btn btn-xs btn-danger" data="danger">&nbsp;&nbsp;&nbsp;</button>
											</div>
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
									<a href="javascript:void(0)" class="btn btn-xs btn-default pull-right">
										<i class="fa fa-plus"></i>
									</a>
                                    Категории
                                </h4>
                                <div class="block-section">
									<form id="add-category-form" class="push">
										<input name="category" class="form-control" id="add-category">
									</form>
                                    <ul class="nav nav-pills nav-stacked" id="tasksCatalog" 
										data-role="foreach" form="users" item="{{_SESS[user_id]}}" field="tasks_categories">
                                        <li data-id="{{id}}" item="{{id}}">
                                            <a href="javascript:void(0)">
                                                <span class="badge pull-right">16</span>
                                                <i class="fa fa-briefcase fa-fw icon-push"></i> <strong>{{category}}</strong>
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
                            <div class="col-md-10">
                                <form id="add-task-form" class="push">
                                    <input type="text" id="add-task" name="add-task" class="form-control input-lg" placeholder="Введите задачу..">
                                </form>
                                <ul class="task-list" data-role="foreach" form="tasks" data-sort="time:d">
									<meta data-role="variable" var="class" value="task-done" where='done<>""' data-hide="*">
                                    <li item="{{id}}" data-id="{{id}}" class="{{class}} btn-{{status}}" 
										data-category="{{category}}"
										data-status="{{status}}"
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
                    
                    
					<script src="/engine/forms/tasks/js/tasks.js?{{_SESS[_new]}}"></script>
					<script>$(function(){ CompTodo.init(); });</script>

