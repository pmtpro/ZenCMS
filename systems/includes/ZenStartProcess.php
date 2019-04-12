<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
 */

if (PHP_VERSION < 5) {

    exit ('Sorry, ZenCMS only work on php 5 or more');
}

/**
 * set max time process
 */
ini_set('max_execution_time', 120);

$emptydata = false;

if (!defined('__ZEN_DB_HOST') ||
    !defined('__ZEN_DB_USER') ||
    !defined('__ZEN_DB_PASSWORD') ||
    !defined('__ZEN_DB_NAME')) {

    $emptydata = true;

} else {

    $dhost = __ZEN_DB_HOST;
    $duser = __ZEN_DB_USER;
    $dname = __ZEN_DB_NAME;

    if (empty($dhost) || empty($duser) || empty($dname)) {

        $emptydata = true;
    }
}

if ($emptydata == true) {

    $__ins_path = __SITE_PATH . '/install';

    if (file_exists($__ins_path) && is_dir($__ins_path) && file_exists($__ins_path . '/index.php')) {

        header("Location: /install");
        exit;
    }
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());

    return ((float)$usec + (float)$sec);
}

/**
 * return client ip
 *
 * @return mixed
 */
if (!function_exists('client_ip')) {

    function client_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (!function_exists('client_user_agent')) {

    function client_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
