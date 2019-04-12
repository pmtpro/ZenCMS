<!DOCTYPE html>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <base href="<?php echo REAL_HOME ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta name="robots" content="noindex"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <link rel="shortcut icon" href="<?php echo REAL_HOME ?>/files/systems/images/favicon.ico"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- BEGIN THEME STYLES -->
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <?php ZenView::get_head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN HEADER -->
<div class="page-header navbar">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo REAL_HOME ?>/admin">
                <img src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png" alt="logo" class="logo-default"/>
            </a>
        </div>
        <!-- END LOGO -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->

<div class="clearfix"></div>

<div class="logo"></div>

<div class="content">
<!-- BEGIN LOGIN FORM -->
<form class="login-form" method="POST">
    <h3 class="form-title">Đăng nhập tài khoản</h3>
    <?php ZenView::display_message() ?>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Tên đăng nhập</label>
        <div class="input-icon">
            <i class="fa fa-user"></i>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Tên đăng nhập" name="username">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Mật khẩu</label>
        <div class="input-icon">
            <i class="fa fa-lock"></i>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Mật khẩu" name="password">
        </div>
    </div>
    <?php if (ZenView::$D['limit_login']): ?>
        <div class="form-group captcha_code">
            <div class="col-md-6"><img src="<?php echo $captcha_src ?>" id="zen-login-captcha" title="Nhập captcha"/></div>
            <div class="col-md-6">
                <input type="text" id="captcha_code" name="captcha_code" class="form-control"/>
            </div>
            <div class="help-block text-center">Nhập mã xác nhận</div>
        </div>
    <?php endif ?>
    <div class="form-actions">
        <label style="margin-top: 8px"><input type="checkbox" name="remember_me" value="1"> Remember me </label>
        <input type="hidden" name="token_login" value="<?php echo $token_login ?>"/>
        <button type="submit" name="submit-login" class="btn green pull-right">
            Đăng nhập <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>

    <div class="forget-password">
        <h4>Quên mật khẩu?</h4>
        <p>
            Không sao, bấm vào <a href="<?php echo HOME ?>/account/forgot_password" id="forget-password">đây</a> để lấy lại mật khẩu.
        </p>
    </div>
</form>
<!-- END LOGIN FORM -->
</div>
<div class="copyright">
    2014 © <a href="http://zencms.vn" target="_blank">ZenCMS</a>
</div>
</body>
<!-- END BODY -->
</html>