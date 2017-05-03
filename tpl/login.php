	<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>AiKi :: 合気 - вход  систему</title>

        <meta name="description" content="AiKi :: 合気 login page">
        <meta name="author" content="digiport">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="/engine/appUI/img/favicon.png">

        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="/engine/appUI/css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="/engine/appUI/css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="/engine/appUI/css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="/engine/appUI/css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
		<script src="/engine/js/jquery.min.js"></script>
        <script src="/engine/appUI/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!-- Full Background -->
        <!-- For best results use an image with a resolution of 1280x1280 pixels (prefer a blurred image for smaller file size) -->
        <img src="/engine/appUI/img/placeholders/layout/login2_full_bg.jpg" alt="Full Background" class="full-bg animation-pulseSlow">
        <!-- END Full Background -->

        <!-- Login Container -->
        <div id="login-container">
            <!-- Login Header -->
            <h1 class="h2 text-light text-center push-top-bottom animation-pullDown">
                <img src="/engine/tpl/images/aiki-logo-white.png">
            </h1>
            <!-- END Login Header -->

            <!-- Login Block -->
            <div class="block animation-fadeInQuick login">
                <!-- Login Title -->
                <div class="block-title">
                    <h2>Вход пользователя</h2>
					<div class="block-options pull-right">
                        <a href="#reminder" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="" style="overflow: hidden; position: relative;" data-original-title="Забыли пароль?"><i class="fa fa-exclamation-circle"></i></a>
                        <a href="#register" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="" style="overflow: hidden; position: relative;" data-original-title="Создать учётную запись"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <!-- END Login Title -->

                <!-- Login Form -->
                <form id="form-login" method="post" class="form-horizontal">
					<input type="hidden" name="mode" value="login" />
                    <div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" id="login-email" name="login" class="form-control" placeholder="Ваш логин...">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" id="login-password" name="pass" class="form-control" placeholder="Ваш пароль..">
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-8">
                            <label class="csscheckbox csscheckbox-primary">
                                <input type="checkbox" id="login-remember-me" name="login-remember-me"><span></span> Запомнить?
                            </label>
                        </div>
                        <div class="col-xs-4 text-right">
                            <button type="submit" class="btn btn-effect-ripple btn-success">Войти <i class="hi hi-log_in"></i></button>
                        </div>
                    </div>
                </form>
                <!-- END Login Form -->
            </div>

            <div class="block animation-fadeInQuick reminder hidden">
                <!-- Reminder Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="#login" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="" style="overflow: hidden; position: relative;" data-original-title="Вход в систему"><i class="fa fa-user"></i></a>
                    </div>
                    <h2>Восстановление пароля</h2>
                </div>
                <!-- END Reminder Title -->

                <!-- Reminder Form -->
                <form id="form-reminder" class="form-horizontal">
					<input type="hidden" id="password" name="reminder-pass" >
                    <div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input type="email" name="reminder-email" class="reminder-email form-control" placeholder="Ваша эл.почта...">
                    </div>
                    <div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						<input type="password" class="password-enter form-control" placeholder="Новый пароль...">
                    </div>
                    <div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						<input type="password" class="password-check form-control" placeholder="Ещё раз...">
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-effect-ripple btn-sm btn-default" style="overflow: hidden; position: relative;">
							<i class="fa fa-check"></i> Восстановить</button>
                        </div>
                    </div>
                </form>
                <!-- END Reminder Form -->
            </div>

            <div class="block animation-fadeInQuick reminder-success hidden">
                <!-- Reminder Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="#login" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="" style="overflow: hidden; position: relative;" data-original-title="Вход в систему"><i class="fa fa-user"></i></a>
                    </div>
                    <h2>Восстановление пароля</h2>
                </div>
				<p>Для завершения процедуры восстановления пароля перейдите в свой почтовый ящик,
				откройте письмо присланое системой и кликните по ссылке в теле сообщения. После этого вы сможете войти в систему с новым паролем.
				</p>
            </div>

            <div class="block animation-fadeInQuick reminder-error hidden">
                <!-- Reminder Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="#login" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="" style="overflow: hidden; position: relative;" data-original-title="Вход в систему"><i class="fa fa-user"></i></a>
                    </div>
                    <h2>Восстановление пароля</h2>
                </div>
				<p class="text-danger">В процедуре смены пароля произошла ошибка! Попробуйте позже.
				</p>
            </div>


            <div class="block animation-fadeInQuick create hidden"></div>
            <!-- END Login Block -->

            <!-- Footer -->
            <footer class="text-muted text-center animation-pullUp">
                <small><span id="year-copy"></span> &copy; <a href="http://www.digiport.ru" target="_blank">AiKi Engine</a></small>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Login Container -->

        <!-- jQuery, Bootstrap, jQuery plugins and Custom JS code -->
        <script src="/engine/appUI/js/plugins.js"></script>
        <script src="/engine/appUI/js/app.js"></script>

        <!-- Load and execute javascript code used only in this page -->
        <script src="/engine/appUI/js/pages/readyLogin.js"></script>
        <script>$(function(){ ReadyLogin.init(); });</script>
    </body>
</html>
