<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>{{_SETT[header]}}</title>

        <meta name="description" content="AiKi engine admin panel">
        <meta name="author" content="digiport">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="/engine/appUI/img/favicon.png">
        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
    </head>
    <body style="margin-top:50px;">
        <!-- Page Wrapper -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
        <div id="page-wrapper" class="page-loading">
            <!-- Preloader -->
            <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
            <!-- Used only if page preloader enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
            <div class="preloader">
                <div class="inner">
                    <div class="preloader-spinner themed-background hidden-lt-ie10"></div>
                    <h3 class="text-primary visible-lt-ie10"><strong>Загрузка..</strong></h3>
                </div>
            </div>
            <!-- END Preloader -->

            <!-- Page Container -->
            <!-- In the PHP version you can set the following options from inc/config file -->
            <!--
                Available #page-container classes:

                'sidebar-light'                                 for a light main sidebar (You can add it along with any other class)

                'sidebar-visible-lg-mini'                       main sidebar condensed - Mini Navigation (> 991px)
                'sidebar-visible-lg-full'                       main sidebar full - Full Navigation (> 991px)

                'sidebar-alt-visible-lg'                        alternative sidebar visible by default (> 991px) (You can add it along with any other class)

                'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                'fixed-width'                                   for a fixed width layout (can only be used with a static header/main sidebar layout)

                'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links (You can add it along with any other class)
            -->
            <div id="page-container" class="enable-cookies header-fixed-top sidebar-visible-lg-full">

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
                                    <a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-rocket sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Формы</span></a>
                                    <ul class="formlist">
                                    </ul>
                                </li>
                                <li>
									<a href="#" class="sidebar-nav-menu"><span class="sidebar-nav-ripple animate"></span><i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-gear sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Утилиты</span></a>
                                    <ul class="utilites">
										<li><a href="#" data-ajax="mode=master&amp;form=form" data-html="div.main">Мастер форм
											<!--span class="pull-right glyphicon glyphicon-plus-sign add-item" data-ajax="mode=add&amp;form=masterform&amp;id=_new" data-toggle="modal" data-target="#prodEdit" data-html="#prodEdit .modal-body"> </span--></a>
										</li>
										<li><a href="#" data-ajax="mode=list&amp;form=source" data-html="div.main">Исходники
											</a>
										</li>
                                    </ul>
								</li>
                            </ul>
                            <!-- END Sidebar Navigation -->

                            <!-- Color Themes -->
                            <!-- Preview a theme on a page functionality can be found in js/app.js - colorThemePreview() -->
                            <div class="sidebar-section sidebar-nav-mini-hide">
                                <div class="sidebar-separator push">
                                    <i class="fa fa-ellipsis-h"></i>
                                </div>
								<div data-role="include" src="/engine/tpl/themes.php" ></div>
                            </div>
                            <!-- END Color Themes -->
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
                    <header class="navbar navbar-inverse navbar-fixed-top">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                            </li>
                            <!-- END Main Sidebar Toggle Button -->
                        </ul>
                        <!-- END Left Header Navigation -->

                        <!-- Right Header Navigation -->
                        <ul class="nav navbar-nav-custom pull-right">
                            <!-- Search Form -->
                            <li>
                                <form action="page_ready_search_results.html" method="post" class="navbar-form-custom">
                                    <input type="text" id="top-search" name="top-search" data-role="search" from="table.formlist tbody tr" class="form-control" placeholder="Поиск..">
                                </form>
                            </li>
                            <!-- END Search Form -->

                            <!-- Alternative Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-ajax="mode=settings&form=admin" data-html="div.main">
                                    <i class="gi gi-settings"></i>
                                </a>
                            </li>
                            <!-- END Alternative Sidebar Toggle Button -->

                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="/engine/appUI/img/placeholders/avatars/avatar9.jpg" alt="avatar">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-header">
                                        <strong>{{_SESS[user]}}</strong>
                                    </li>
                                    <li>
                                        <a href="#" data-ajax="mode=settings&form=admin" data-html="div.main">
                                            <i class="gi gi-settings fa-fw pull-right"></i>
                                            Настройки
                                        </a>
                                    </li>
                                    <li class="divider"><li>
                                    <li>
                                        <a href="/logout.htm">
                                            <i class="fa fa-power-off fa-fw pull-right"></i>
                                            Выход
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END User Dropdown -->
                        </ul>
                        <!-- END Right Header Navigation -->
                    </header>
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content" class="row">
                        <div class="row" class="main"></div>
					</div>
                    <!-- END Page Content -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->

        <!-- jQuery, Bootstrap, jQuery plugins and Custom JS code -->
    </body>
</html>
