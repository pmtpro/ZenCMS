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
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/pages/css/lock.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <?php ZenView::get_head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
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
<div class="page-lock">
    <div class="page-logo"></div>
    <div class="page-body">
        <img src="<?php echo $_client['full_avatar'] ?>" class="page-lock-img" alt="avatar"/>
        <div class="page-lock-info">
            <h1><?php echo $_client['nickname'] ?></h1>
            <span class="email"><?php echo $_client['email'] ?></span>
            <span class="locked">Trang này đã bị khóa</span>
            <form class="form-inline" method="POST">
                <div class="input-group input-medium">
                    <input type="password" name="zen_verity_access" class="form-control" placeholder="Nhập mật khẩu cấp 2"/>
                        <span class="input-group-btn">
                        <button type="submit" name="submit_verify" class="btn blue icn-only"><i class="m-icon-swapright m-icon-white"></i></button>
                        </span>
                </div>
                <!-- /input-group -->
                <div class="relogin">
                    <a href="<?php echo HOME ?>/admin/logout">Không phải <?php echo $_client['nickname'] ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="copyright">
        2014 © <a href="http://zencms.vn" target="_blank">ZenCMS</a>
    </div>
</div>
</body>
<!-- END BODY -->
</html>