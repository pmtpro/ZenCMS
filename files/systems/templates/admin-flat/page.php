<!DOCTYPE html>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <base href="<?php echo REAL_HOME ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="robots" content="noindex"/>
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>"/>
    <meta name="description" content="<?php ZenView::get_desc() ?>"/>
    <meta name="revisit-after" content="1 days"/>
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <link rel="canonical" href="<?php ZenView::get_url() ?>"/>
    <link rel="shortcut icon" href="<?php echo REAL_HOME ?>/files/systems/images/favicon.ico"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <?php ZenView::get_head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-footer-static page-quick-sidebar-over-content page-sidebar-closed">
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">

        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo REAL_HOME ?>/admin">
                <img src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png" alt="logo" class="logo-default"/>
            </a>

            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->

        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li>
                    <a href="<?php echo HOME ?>" target="_blank"><i class="fa fa-home"></i></a>
                </li>
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <img alt="<?php echo $_client['nickname'] ?>" class="img-circle" src="<?php echo
$_client['full_avatar'] ?>"/>
                        <span class="username"><?php echo $_client['nickname'] ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo REAL_HOME ?>/account"><i class="glyphicon glyphicon-user"></i> Trang cá nhân </a>
                        </li>
                        <li>
                            <a href="<?php echo REAL_HOME ?>/admin/members/editor&id=<?php echo
$_client['id'] ?>">
                                <i class="glyphicon glyphicon-pencil"></i> Chỉnh sửa
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo REAL_HOME ?>/admin/logout"><i class="glyphicon glyphicon-log-out"></i> Thoát </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="javascript:;" class="dropdown-toggle" id="global_note_total_notice">
                        <i class="fa fa-globe"></i>
                        <?php echo hook('admin', 'note_total_notice', '', array('end_callback' => function($data) {
                                if ($data) return '<span class="badge badge-default">' . $data . '</span>';
                                else return '';
                            }
                        )) ?>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->

<div class="clearfix"></div>

<!-- BEGIN CONTAINER -->
<div class="page-container">

<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <?php ZenView::load_layout('zen-left-sidebar') ?>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
<!-- END SIDEBAR -->

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="col-md-12">
            <?php ZenView::display_content() ?>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
<!-- END CONTENT -->

<!-- BEGIN QUICK SIDEBAR -->
<a href="javascript:;" class="page-quick-sidebar-toggler">
    <i class="fa fa-sign-out"></i>
</a>
<div class="page-quick-sidebar-wrapper">
    <div class="page-quick-sidebar">
        <div class="nav-justified">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#nav-tab-update" data-toggle="tab" id="note_total_notice">
                        Thông báo <?php echo hook('admin', 'note_total_notice', '', array('end_callback' => function($data) {
                                if ($data) return '<span class="badge badge-success">' . $data . '</span>';
                                else return '';
                            })) ?>
                    </a>
                </li>
                <?php echo hook('admin', 'note_nav_tabs') ?>
                <li>
                    <a href="#nav-tab-setting" data-toggle="tab">
                        Cài đặt
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active page-quick-sidebar-alerts" id="nav-tab-update">
                    <div class="page-quick-sidebar-alerts-list">
                        <h3 class="list-heading">
                            Cập nhật
                            <?php echo hook('admin', 'note_total_notice_update', '', array('end_callback' => function($data) {
                                    if ($data) return '<span class="badge badge-success">' . $data . '</span>';
                                    else return '';
                                })) ?>
                        </h3>
                        <ul class="feeds list-items" id="note_nav_tabs_item">
                            <!--<li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-update"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc">
                                                Bạn có 4 cập nhật mới. <span class="label label-sm label-warning">Cập nhật <i
                                                        class="fa fa-share"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date">Just now</div>
                                </div>
                            </li>-->
                            <?php echo hook('admin', 'note_nav_tabs_item', '', array('end_callback' => function($data) {
                                    if (!$data) return '<li>Không có bản cập nhật nào</li>'; else return $data;
                                })) ?>
                        </ul>
                    </div>
                </div>
                <?php echo hook('admin', 'note_nav_tabs_content') ?>
                <div class="tab-pane active page-quick-sidebar-alerts" id="nav-tab-setting">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END QUICK SIDEBAR -->

</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="col-lg-5">
        <div class="page-footer-inner">
            2014 &copy; <a href="http://zencms.vn" target="_blank">ZenCMS</a>.
        </div>
    </div>
    <div class="col-lg-5">
        <ul class="social-footer list-unstyled list-inline">
            <li><a href="http://facebook.com/zencms"><i class="fa fa-facebook"></i></a></li>
            <li><a href="http://google.com/+zencmsvnpage"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="http://twitter.com/zencms"><i class="fa fa-twitter"></i></a></li>
        </ul>
    </div>
    <div class="col-lg-2">
        <div class="page-footer-tools">
		<span class="go-top">
		<i class="fa fa-angle-up"></i>
		</span>
        </div>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]-->
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/respond.min.js"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/excanvas.min.js"></script>
<!--[endif]-->
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/theme/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init() // init quick sidebar
    });
</script>

<!-- ZENCMS JAVASCRIPT -->
<script src="<?php echo REAL_HOME ?>/files/systems/templates/admin-flat/js/main.js" type="text/javascript"></script>
<!--/ END ZENCMS JAVASCRIPT -->

<?php ZenView::get_foot() ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>