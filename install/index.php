<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
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
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
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
$title = "Cài đặt ZenCMS 5.0.0";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title ?></title>
    <meta name="ROBOTS" content="noindex"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="Googlebot" content="noindex"/>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <link rel="shortcut icon" href="http://localhost/files/systems/images/zen5-favicon.png"/>
    <!-- start: CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,vietnamese' rel='stylesheet' type='text/css'/>
    <link href="<?php echo _URL_FILES_SYSTEMS ?>/templates/admin/theme/css/application.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?php echo HOME ?>/install/theme/style.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="<?php echo _URL_FILES_SYSTEMS ?>/templates/admin/js/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="<?php echo _URL_FILES_SYSTEMS ?>/templates/admin/js/jquery-ui-1.10.2.js" type="text/javascript"></script>
    <script src="<?php echo _URL_FILES_SYSTEMS ?>/templates/admin/js/bootstrap.zencms.js" type="text/javascript"></script>
    <script src="<?php echo _URL_FILES_SYSTEMS ?>/templates/admin/js/jquery.zencms.js" type="text/javascript"></script>
    <!-- end: CSS -->
</head>
<body>
<nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo HOME ?>/install"><img src="<?php echo _URL_FILES_SYSTEMS ?>/images/zen-cp-logo.png"/></a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
            <span class="sr-only">Toggle Side Navigation</span>
            <i class="icon-th-list"></i>
        </button>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
            <span class="sr-only">Toggle Top Navigation</span>
            <i class="icon-align-justify"></i>
        </button>
    </div>
</nav>
<div class="container">
    <div class="col-md-8 col-md-offset-2">
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
    <div class="col-md-8 col-md-offset-2">
        <div class="footer">
            <table>
                <tbody><tr>
                    <td>
                        <b>ZENCMS SOFTWARE</b><br>
                        © 2012-2014 <b><a href="http://zenthang.com" title="ZenThang">ZenThang</a></b>
                    </td>
                    <td width="50%" align="right">
                        <span><a href="http://zencms.vn" target="_blank">TRANG CHỦ</a></span> /
                        <span><a href="http://zencms.vn/blog" target="_blank">HƯỚNG DẪN SỬ DỤNG</a></span> /
                        <span><a href="http://zencms.vn/developer-documentation-1.html" target="_blank">DEVELOPER DOCUMMENT</a></span>/
                        <span><a href="http://zencms.vn/license" target="_blank">Điều khoản sử dụng</a></span>
                        <div>
                            Liên hệ: <b><a href="http://fb.com/thangangle">Zen Thắng</a></b> - <b>thangangle@yahoo.com</b>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
