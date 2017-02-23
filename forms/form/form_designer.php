                <!-- Main Sidebar -->
                <div id="sidebar">
                    <!-- Sidebar Brand -->
                    <div id="sidebar-brand" class="themed-background">
                        <a href="/admin.htm" class="sidebar-title">
                            <i class="fa fa-cube"></i> <span class="sidebar-nav-mini-hide">Администратор</span>
                        </a>
                    </div>
                    <!-- END Sidebar Brand -->

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Sidebar Navigation -->
                            <ul class="sidebar-nav">
								<li>
									<div class="form-horizontal">
										<a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-vcard sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Формы (mode)</span></a>
										<ul id="formList"></ul>
									</div>
								</li>
								<li>
                                    <a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-rocket sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Шаблоны</span></a>
										<ul id="formDesignerSnippets">
											<li><a href="#snippet" data="container">container</a></li>
											<li><a href="#snippet" data="row">row</a></li>
											<li><a href="#snippet" data="col">col</a></li>
											<li><a href="#snippet" data="inputgroup1">formgroup input (1)</a></li>
											<li><a href="#snippet" data="inputgroup2">formgroup input (2)</a></li>
											<li><a href="#snippet" data="selectgroup1">formgroup select (1)</a></li>
											<li><a href="#snippet" data="selectgroup2">formgroup select (2)</a></li>
											<li><a href="#snippet" data="editform">edit form</a></li>
											<li><a href="#snippet" data="listitems">listitems</a></li>
											<li><a href="#snippet" data="button">button</a></li>
											<li><a href="#snippet" data="panel">панель</a></li>
										</ul>
										<div id="formDesignerSnippetsPrompt" style="display:none;">
											<div>Вставить шаблон</div>
											<a href="#" class="round btn btn-sm btn-default btn-primary sBefore" title="перед элементом"><i class="fa fa-arrow-left"></i></a>
											<a href="#" class="round btn btn-sm btn-default btn-warning sPrepend"  title="перед контентом"><i class="fa fa-chevron-left"></i></a>
											<a href="#" class="round btn btn-sm btn-default btn-warning sAppend" title="после контента"><i class="fa fa-chevron-right"></i></a>
											<a href="#" class="round btn btn-sm btn-default btn-primary sAfter" title="после элемента"><i class="fa fa-arrow-right"></i></a>
										</div>
                                </li>
                                <li>
									<a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-gear sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Свойства</span></a>
                                    <ul>
										<li><a href="#" >
											null
											<!--span class="pull-right glyphicon glyphicon-plus-sign add-item" data-ajax="mode=add&amp;form=masterform&amp;id=_new" data-toggle="modal" data-target="#prodEdit" data-html="#prodEdit .modal-body"> </span--></a>
										</li>

                                    </ul>
								</li>
                            </ul>
                            <!-- END Sidebar Navigation -->


                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->

                    <!-- Sidebar Extra Info -->
                    <div id="sidebar-extra-info" class="sidebar-content sidebar-nav-mini-hide">
                        <div class="text-center">
                            <small><span id="year-copy"></span> &copy; <a href="http://www.digiport.ru" target="_blank">AiKi Engine</a></small>
                        </div>
                    </div>
                    <!-- END Sidebar Extra Info -->
                </div>
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    <!-- Header -->
                    <!-- In the PHP version you can set the following options from inc/config file -->
                    <!--
                        Available header.navbar classes:

                        'navbar-default'            for the default light header
                        'navbar-inverse'            for an alternative dark header

                        'navbar-fixed-top'          for a top fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                            'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

                        'navbar-fixed-bottom'       for a bottom fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                            'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                    -->
                    <header class="navbar navbar-inverse navbar-fixed-top" id="formDesignerHeader">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                            </li>
                        </ul>
                        <!-- Right Header Navigation -->
                        

                        <ul class="nav navbar-nav-custom" id="formDesignerNav">
							<li><a href="#" data-ajax="mode=designer&amp;form=form" data-html="#page-container"><i class="fa fa-refresh"></i></a></li>
							
							<li><a>
									<select class="form-control" id="formName" placeholder="Форма" data-role="foreach" from="forms">
											<option value="{{form}}" data-path="{{dir}}">{{form}}</option>
									</select>
							</a></li>
							<li><a id="formAdd" ><i class="fa fa-plus"></i></a></li>
							<li><a id="formSave" ><i class="fa fa-save"></i></a></li>
							
							<li class="toPrev"><a href="#prev"><i class="fa fa-arrow-left"></i></a></li>
                            <li class="currentInfo"><a href="#"><strong></strong></a></li>
                            <li class="toNext"><a href="#next"><i class="fa fa-arrow-right"></i></a></li>
						</ul>
                    </header>
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content" class="row">
                        <div class="row" class="main">
                        

<div id="formDesigner">


		
		<div data-role="include" src="modal" data-id="formCreator" data-role-hide="true"></div>
		<form id="formMasterForm" name="form" item="master"  class="form-horizontal" role="form" append="#formCreator .modal-body">
			<div class="form-group">
			  <label class="col-xs-6 col-sm-4 control-label">ID формы</label>
			   <div class="col-xs-6 col-sm-8"><input type="text" class="form-control" name="name" value="" placeholder="ID формы" required ></div>
			</div>

			<div class="form-group">
			  <label class="col-xs-6 col-sm-4 control-label">Имя формы</label>
			   <div class="col-xs-6 col-sm-8"><input type="text" class="form-control" name="descr" value="" placeholder="Имя формы" required ></div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label">Добавить в список форм</label>
				<div class="col-xs-4"><label class="switch switch-primary"><input type="checkbox" name="tolist" value="on" checked="checked"><span></span></label></div>
			</div>
		</form>


			
	



<div id="formDesignerBlock">
	<div class="panel viewer col-xs-9">

		<div id="formDesignerEditor" class="tab-content form-horizontal" >
			<div id="formDesignerToolBtn">
				<a class="btn btn-sm btn-primary"><i class="fa fa-gear"></i></a>
				<a class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></a>
				<a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
			</div>
		</div>
	</div>
	<div data-role="include" src="source" data-id="designerSourceEditor" data-role-hide="true" data-class="col-xs-9 sourceModal"></div>

</div>
	
</div>

                        
                        
                        </div>
					</div>
                    <!-- END Page Content -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>

<script language="javascript" src="/engine/forms/form/form_designer.js?{{_new}}"></script></script>

<style type="text/css" media="screen">
body {overflow:hidden;}
#formDesigner .formDesignerEditor {min-height:100px; background:#fff; border:3px #eee dashed; padding:5px;}
#formDesignerEditor {padding:5px 0px;}
#formDesignerEditor [data-current] {border: 1px #aaa dashed!important; background: rgba(217, 255, 228, 0.3)!important; cursor:move!important;}
#formDesignerEditor [data-hovered] { background: rgba(98, 122, 173, 0.25)!important; transition-duration:0.3s;}
#formDesignerToolBtn {position:fixed; display:inline-block; width:auto; z-index:100;}
#formDesignerNav .currentInfo {width:200px; height:50px; overflow:hidden; text-align:center;}
#formDesignerEditor .formDesignerEditor .row {background: rgba(92, 205, 222, 0.05);}
#formDesignerEditor .formDesignerEditor .row > [class*="col-"]:nth-child(odd) {background: rgba(92, 105, 122, 0.05);}

#formDesignerToolBtn .btn {border-radius: 100%;padding: 1px;height: 20px;width: 20px;font-size: 12px;line-height: 16px;}

#formDesignerEditor .formDesignerEditor :empty {min-height:20px;}
#formDesignerEditor .formDesignerEditor [data-role=include],
#formDesignerEditor .formDesignerEditor [data-role=imageloader],
#formDesignerEditor .formDesignerEditor [data-role=source],
#formDesignerEditor .formDesignerEditor textarea.editor
	{min-height: 100px; width: 100%; border: 1px #eee dotted; background: rgba(238, 238, 238, 0.3); background:url(/engine/tpl/images/diagonals.png);}
#formDesignerEditor .formDesignerEditor [data-role=include]::before {content:'Динамическая вставка';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=imageloader]::before {content:'Динамическая вставка: Загрузчик изображений';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=source]::before {content:'Динамическая вставка: Редактор исходного кода';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=modal]::before {content:'Динамическая вставка: Модальное окно';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=editor]::before {content:'Динамическая вставка: Текстовый редактор';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=uploader]::before {content:'Динамическая вставка: Загрузчик файлов';color:#aaa;}
#formDesignerEditor .formDesignerEditor [data-role=include][src=comments]::before {content:'Динамическая вставка: Модуль коментариев';color:#aaa;}
#formDesigner #formList input {border:0;padding:0;margin:0;width:70px;background:transparent;}
#formDesigner #formList .fa-remove {margin-left:8px; color:#555;}
#formDesigner #formList li.active a {background: ghostwhite;}

#formDesigner #formDesignerBlock .panel {padding: 5px 0px; margin: 0px 10px;}

#formDesigner #sidebar-alt .nav-tabs a {padding:5px;}

#sourceEditor.fullscr, #sourceEditor.fullscr .modal-dialog {width:100% !important; padding:0 !important; margin:0 !important;}
#sourceEditor.fullscr .modal-body {padding:0;}
#sourceEditor.fullscr .modal-header, #sourceEditor.fullscr .modal-footer {display:none;}
#sourceEditor.fullscr .nav {display:none;}
    
#sourceEditor .modal-header, #sourceEditor .modal-footer {padding:5px;}
#sourceEditor .modal-body {padding:0px;}
    
#sourceEditor .ace_editor {margin:0;}

#formDesignerBlock .panel {height:40%; overflow-y:auto; overflow-x:hidden;}
#formDesigner #designerSourceEditor {z-index:110;border:0;position:absolute;transition-duration:0.3s;overflow:hidden; top:calc(80%);height:calc(100%); display:none;}
#formDesigner #designerSourceEditor:hover {top:calc(100%/2.5)!important;transition-duration:0.3s;}
.round.btn { border-radius: 100%; height: 25px;  width: 25px; line-height: 24px; padding: 0; margin: 0;}

.popover .row.preview {display:block;padding-right:40px; width:900px; zoom:30%;}

.popover .row.preview > .row:only-of-type   {border:1px #777 solid; background:#AAA; height:100px; width:100%;}
.popover .row.preview > .container:only-of-type  {border:1px #777 solid; background:#AAA; height:100px; width:100%;}
.popover1 .row.preview > .row [class*=col-]:only-of-type {border:1px #fff solid; background:#AAA;  height:90px;}

style="display:block;padding-right:40px; width:900px; zoom:30%;
</style>
