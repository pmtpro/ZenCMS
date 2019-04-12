<?php
ob_start();

if (PHP_VERSION < 5) {

    exit ('Sorry, ZenCMS only work on php 5 or more');
}

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

if (file_exists(__DB_FILE_PATH)) {

    include __DB_FILE_PATH;

}

$db = new ZenDatabase();

$title = "ZenCMS - Web developers - code web - code wap";

?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cài đặt ZenCMS - <?php echo $title ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Googlebot" content="all"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="revisit-after" content="1 days"/>
    <link rel="canonical" href=""/>
    <link rel="shortcut icon" href="<?php echo _HOME ?>/install/images/favicon.ico"/>
    <link href="theme/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="body">
    <div class="header">

        <div id="logo">
            <a href="<?php echo _HOME ?>/install" title="<?php echo $title ?>">
                <img src="images/logo.png" title="<?php echo $title ?>"/>
            </a>
        </div>

        <div id="menu">
            <ul>
                <li class="separator">
                    <a class="iconHomed" href="http://zencms.vn" title="<?php echo $title ?>">
                        ZenCMS
                    </a>
                </li>
            </ul>
        </div>

    </div>
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
    <div class="footer">
        Power by <a href="http://zencms.vn" target="_blank" title="<?php echo $title ?>">ZenCMS</a><br/>
        &copy; 2013 <a href="https://plus.google.com/105389259758063759944/posts?rel=author" target="_blank" title="ZenThang">ZenThang</a>
    </div>
</div>
</body>
</html>