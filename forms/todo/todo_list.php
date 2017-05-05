<style>
    #add-todo-form {height: 3.5rem; vertical-align: middle; display: table-cell;}
	#add-todo-form .form-control {height:1.8rem;}
	
	.todo-list li > a {position: absolute; opacity: 0; -webkit-transition: opacity .15s ease-out; transition: opacity .15s ease-out; }
	.todo-list li:hover > a {opacity: 1;}
	
	.todo-list [contenteditable] {cursor:text;}
	.todo-list .checkbox-inline {padding-right: 20px;}
	
	.todo-list .bg- {background:#fff;}
</style>



<div id="content" class="app-content box-shadow-z2 pjax-container" role="main">
<div class="app-header hidden-lg-up black lt b-b">
<div class="navbar" data-pjax>
<a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up p-r m-a-0">
<i class="ion-navicon">
</i>
</a>
<div class="navbar-item pull-left h5" id="pageTitle">Project</div>
<ul class="nav navbar-nav pull-right">
<li class="nav-item dropdown pos-stc-xs">
<a class="nav-link" data-toggle="dropdown">
<i class="ion-android-search w-24">
</i>
</a>
<div class="dropdown-menu text-color w-md animated fadeInUp pull-right">
<form class="navbar-form form-inline navbar-item m-a-0 p-x v-m" role="search">
<div class="form-group l-h m-a-0">
<div class="input-group">
<input type="text" class="form-control" placeholder="Search projects..."> <span class="input-group-btn">
<button type="submit" class="btn white b-a no-shadow">
<i class="fa fa-search">
</i>
</button>
</span>
</div>
</div>
</form>
</div>
</li>
<li class="nav-item dropdown pos-stc-xs">
<a class="nav-link clear" data-toggle="dropdown">
<i class="ion-android-notifications-none w-24">
</i> <span class="label up p-a-0 danger">
</span>
</a>
<div class="dropdown-menu pull-right w-xl animated fadeIn no-bg no-border no-shadow">
<div class="scrollable" style="max-height: 220px">
<ul class="list-group list-group-gap m-a-0">
<li class="list-group-item dark-white box-shadow-z0 b">
<span class="pull-left m-r">
<img src="images/a0.jpg" alt="..." class="w-40 img-circle">
</span> <span class="clear block">Use awesome <a href="#" class="text-primary">animate.css</a>
<br>
<small class="text-muted">10 minutes ago</small>
</span>
</li>
<li class="list-group-item dark-white box-shadow-z0 b">
<span class="pull-left m-r">
<img src="images/a1.jpg" alt="..." class="w-40 img-circle">
</span> <span class="clear block">
<a href="#" class="text-primary">Joe</a> Added you as friend<br>
<small class="text-muted">2 hours ago</small>
</span>
</li>
<li class="list-group-item dark-white text-color box-shadow-z0 b">
<span class="pull-left m-r">
<img src="images/a2.jpg" alt="..." class="w-40 img-circle">
</span> <span class="clear block">
<a href="#" class="text-primary">Danie</a> sent you a message<br>
<small class="text-muted">1 day ago</small>
</span>
</li>
</ul>
</div>
</div>
</li>
<li class="nav-item dropdown">
<a class="nav-link clear" data-toggle="dropdown">
<span class="avatar w-32">
<img src="images/a3.jpg" class="w-full rounded" alt="...">
</span>
</a>
<div class="dropdown-menu w dropdown-menu-scale pull-right">
<a class="dropdown-item" href="profile.html">
<span>Profile</span>
</a> <a class="dropdown-item" href="setting.html">
<span>Settings</span>
</a> <a class="dropdown-item" href="app.inbox.html">
<span>Inbox</span>
</a> <a class="dropdown-item" href="app.message.html">
<span>Message</span>
</a>
<div class="dropdown-divider">
</div>
<a class="dropdown-item" href="docs.html">Need help?</a> <a class="dropdown-item" href="signin.html">Sign out</a>
</div>
</li>
</ul>
</div>
</div>
<div class="app-body">
<div class="app-body-inner">
<div class="row-col">
<div class="col-xs-3 w-xl modal fade aside aside-lg" id="subnav">
</div>
<div class="col-xs-4 modal fade aside aside-sm" id="list">
<div class="row-col b-r light lt">
<div class="b-b">
<div class="navbar no-radius">
<a data-toggle="modal" data-target="#subnav" data-ui-modal class="navbar-item pull-left hidden-xl-up hidden-sm-down">
<span class="btn btn-sm btn-icon blue">
<i class="fa fa-th">
</i>
</span>
</a>
<ul class="nav navbar-nav pull-right m-l">
<li class="nav-item dropdown">
<a class="nav-link text-muted" href="#" data-toggle="dropdown">
<i class="fa fa-ellipsis-h">
</i>
</a>
<div class="dropdown-menu pull-right text-color" role="menu">
<a class="dropdown-item">
<i class="fa fa-tag">
</i> Tag item</a> <a class="dropdown-item">
<i class="fa fa-pencil">
</i> Edit item</a> <a class="dropdown-item">
<i class="fa fa-trash">
</i> Delete item</a>
<div class="dropdown-divider">
</div>
<a class="dropdown-item">
<i class="fa fa-ellipsis-h">
</i> More action</a>
</div>
</li>
</ul>
<ul class="nav navbar-nav">
<li class="nav-item">
<span class="navbar-item m-r-0 text-md">Чек-лист</span>
</li>

<li class="nav-item">
<span class="navbar-item m-r-0 text-md">
		<form id="add-todo-form">
			<input type="text" id="add-todo" name="add-todo" class="form-control rounded" placeholder="Добавить задачу..">
		</form>
</span>
</li>

<li class="nav-item">
<a class="nav-link">
<span class="label rounded">55</span>
</a>
</li>
</ul>
</div>
</div>
<div class="row-row">
<div class="row-body scrollable hover">
<div class="row-inner">
<div class="list todo-list" data-ui-list="b-r b-2x b-theme" data-role="foreach" form="todo" data-sort="task time:d" data-loader="loaderTodo"
							data-size="20" where='user = "{{_SESS[user_id]}}" AND category="unsorted"'>
	<meta data-role="variable" var="class" value="task-done" where='done<>""' data-hide="*">
	<div class="list-item row-col {{class}}"  item="{{id}}" data-id="{{id}}" >
	<div class="col-xs">
	<label class="md-check p-r-xs">
	<input type="checkbox"> <i>
	</i>
	</label>
	</div>
	<div class="list-body col-xs">
	<span class="item-title _500">{{task}}</span>
	<div class="text-muted text-xs"><i class="fa fa-clock-o"></i> {{timeview}}</div>
	<div class="dropdown m-t-xs">
	<a href="#" data-toggle="dropdown">
	<span class="label warn rounded dropdown-toggle">In progress</span>
	</a>
	<div class="dropdown-menu">
	<a class="dropdown-item" href="#">
	<i class="fa fa-circle-o text-accent">
	</i>Active</a> <a class="dropdown-item" href="#">
	<i class="fa fa-circle-o text-warn">
	</i>In progress</a> <a class="dropdown-item" href="#">
	<i class="fa fa-circle-o text-success">
	</i>Completed</a> <a class="dropdown-item" href="#">
	<i class="fa fa-circle-o text-muted">
	</i>Archived</a>
	</div>
	</div>
	</div>
	</div>
</div>
</div>
</div>
</div>
<div class="p-a b-t clearfix">
<div class="btn-group pull-right">
<a href="#" class="btn btn-xs white circle">
<i class="fa fa-fw fa-angle-left">
</i>
</a> <a href="#" class="btn btn-xs white circle">
<i class="fa fa-fw fa-angle-right">
</i>
</a>
</div>
<span class="text-sm text-muted">Completed: <strong>10</strong>, In Progress: <strong>5</strong>
</span>
</div>
</div>
</div>
<div class="col-xs w-80" id="sidenav">
<div class="row-col bg">
<div class="row-row">
<div class="row-body scrollable hover">
<div class="row-inner">
<div class="p-y text-center">
<div>
<a href="#" class="inline">
<span class="circle w-40 avatar success">M</span>
</a>
</div>
<div>
<a href="#" class="inline">
<span class="circle w-40 avatar info">RD</span>
</a>
</div>
<div>
<a href="#" class="inline">
<span class="circle w-40 avatar">
<img src="images/a2.jpg" alt=".">
</span>
</a>
</div>
<div>
<a href="#" class="inline">
<span class="circle w-40 avatar">
<img src="images/a3.jpg" alt=".">
</span>
</a>
</div>
<div>
<a href="#" class="inline">
<span class="circle w-40 avatar grey">S</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="p-y text-center">
<a href="#" class="md-btn md-mini md-fab primary">
<i class="fa fa-plus">
</i>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

                    
					<script src="/engine/forms/todo/js/todo.js?{{_SESS[_new]}}"></script>
					<script>
						$(document).ready(function(){
							CompTodo.init();
					
							$(document).on("data-ajax-done", function( event, target , ajax, data ) {
								CompTodo.init(); 
							});	
						});
						function loaderTodo(type) {
							if (type==true) {
								$(".preloader").css("background-color","rgba(255, 255, 255, 0.40)");
								$(".preloader").show();
							} else {
								$(".preloader").hide();
								$(".preloader").css("background-color","rgba(255, 255, 255, 1)");
							}
						}


					</script>

