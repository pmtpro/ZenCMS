<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
ob_start();
if (PHP_VERSION < 5.3) {
    exit ('Sorry, ZenCMS only work on php 5.3 or more');
}
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
/**
 * start session
 */
session_start();

/**
 * * define the key access for all file **
 */
define('__ZEN_KEY_ACCESS', rand());

if (preg_match('/win/is', PHP_OS)) {
    define('DS', "\\");
} else {
    define('DS', "/");
}
/**
 * * define the site path **
 */
define('__INSTALL_PATH', realpath(dirname(__FILE__)));

$sitepath = preg_replace('/install\/?$/', '', __INSTALL_PATH);
$sitepath = rtrim($sitepath, '\\');
$sitepath = rtrim($sitepath, '/');

/**
 * define site path
 */
define('__SITE_PATH', $sitepath);

/**
 * define system path
 */
define('__SYSTEMS_PATH', __SITE_PATH . '/systems');

define('__SYSTEMS_INCLUDES_PATH', __SITE_PATH . '/systems/includes');

define('__SYSTEMS_INCLUDES_CONFIG_PATH', __SITE_PATH . '/systems/includes/config');

/**
 * define path database file
 */
define('__DB_FILE_PATH', __SITE_PATH . '/systems/includes/config/ZenDB.php');

/**
 * define path database file
 */
define('__PRIVATE_FILE_PATH', __SITE_PATH . '/systems/includes/config/ZenPRIVATE.php');


define('__SQL_FILE_NAME', 'ZenCMS.sql');

define('__SQL_FILE', __INSTALL_PATH . '/' . __SQL_FILE_NAME);

/**
 * include database center
 */
include __SITE_PATH . '/systems/init/ZenDatabase.class.php';

include __INSTALL_PATH . '/functions.php';

define('_URL_FILES_SYSTEMS', HOME . '/files/systems');

if (file_exists(__DB_FILE_PATH)) {
    include __DB_FILE_PATH;
}

$db = &ZenDatabase::getInstance();
$title = "Cài đặt ZenCMS 6.0.0";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <base href="<?php echo REAL_HOME ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title ?></title>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="robots" content="noindex"/>
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
</head>
<body class="page-header-fixed page-footer-fixed page-quick-sidebar-over-content page-sidebar-closed">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">

        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo REAL_HOME ?>/install">
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
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>

<div class="page-container">

    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu page-sidebar-menu-closed" data-auto-scroll="true" data-slide-speed="200">
                <li class="sidebar-toggler-wrapper">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler"></div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>
                <li>
                    <a href="http://zencms.vn" target="_blank">
                        <i class="fa fa-home"></i>
                        <span class="title">Trang chủ</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li>
                    <a href="http://forum.zencms.vn" target="_blank">
                        <i class="fa fa-comment"></i>
                        <span class="title">Diễn đàn</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li>
                    <a href="http://docs.zencms.vn" target="_blank">
                        <i class="fa fa-question-circle"></i>
                        <span class="title">Tài liệu sử dụng</span>
                        <span class="selected"></span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="col-md-12">
                <?php
                if (!empty($_GET['do'])) {
                    $do = $_GET['do'];
                } else {
                    $do = 'GettingStarted';
                }
                if ($do != 'GettingStarted') {
                    if (empty($_SESSION['agree'])) {
                        redirect('install?do=GettingStarted');
                    }
                }
                $do_file = __INSTALL_PATH . '/do/' . $do . '.do.php';
                if (file_exists($do_file)) {
                    include $do_file;
                }
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>

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
<!-- END JAVASCRIPTS -->
</body>
</html>
