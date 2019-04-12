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

/**
 * Determines if the current version of PHP is greater then the supplied value
 * @access	public
 * @param	string
 * @return	bool	TRUE if the current version is $version or higher
 */
if (!function_exists('is_php'))
{
    function is_php($version = '5.0.0')
    {
        static $_is_php;
        $version = (string)$version;

        if ( ! isset($_is_php[$version])) {
            $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
        }

        return $_is_php[$version];
    }
}

if (!is_php('5.3')) {
    exit ('Sorry, ZenCMS work on PHP version minimum 5.3');
}

/**
 * set max time process
 */
ini_set('max_execution_time', 120);

/**
 * return client ip
 * @return mixed
 */
if (!function_exists('client_ip')) {
    function client_ip() {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (!function_exists('client_user_agent')) {
    function client_user_agent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}

/**
 * return http host
 * @return string
 */
if (!function_exists('getHttpHost')) {
    function getHttpHost() {
        $scheme = (isset($_SERVER['HTTPS']) && ($_SERVER['SERVER_PORT'] == '443')) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $request = trim(dirname($_SERVER["PHP_SELF"]),'/');
        $home = sprintf('%s://%s/%s', $scheme, $host, $request);
        $home = rtrim($home, '/');
        return $home;
    }
}

function microTime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$emptyDB = false;
if (!defined('__ZEN_DB_HOST') ||
    !defined('__ZEN_DB_USER') ||
    !defined('__ZEN_DB_PASSWORD') ||
    !defined('__ZEN_DB_NAME')) {
    $emptyDB = true;
} else {
    $dHost = __ZEN_DB_HOST;
    $dUser = __ZEN_DB_USER;
    $dName = __ZEN_DB_NAME;
    if (empty($dHost) || empty($dUser) || empty($dName)) {
        $emptyDB = true;
    }
}
if ($emptyDB == true) {
    $__ins_path = __SITE_PATH . '/install';
    if (file_exists($__ins_path) && is_dir($__ins_path) && file_exists($__ins_path . '/index.php')) {
        header("Location: " . getHttpHost() . "/install");
        exit;
    } else exit('Bạn chưa cài đặt thông số Database');
}