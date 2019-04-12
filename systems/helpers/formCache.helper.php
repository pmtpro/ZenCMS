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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

/**
 * this function will auto save form value
 * @param $name
 * @param bool $timeSave: false is forever
 */
function formCacheSave($name, $timeSave = false)
{
    if (empty($name))  return;
    if (!defined('MODULE_NAME')) return;
    if (isset($_POST[$name])) {
        $out = $_POST[$name];
    } else $out = false;
    if (!$timeSave) {
        $time = time() + 356*24*60*60;
    } else $time = time() + $timeSave;
    setcookie("helper[formCache][" . MODULE_NAME . "][$name]", $out, $time);
}

/**
 * this function will return the value of form input by name
 * @param $name
 * @param bool $return
 * @return bool
 */
function formCacheGet($name, $return = false)
{
    if (empty ($name)) return false;
    if (!defined('MODULE_NAME')) return false;
    if ($return && !empty($_COOKIE['helper']['formCache'][MODULE_NAME][$name])) {
        return $return;
    }
    if (isset($_COOKIE['helper']['formCache'][MODULE_NAME][$name])) {
        return $_COOKIE['helper']['formCache'][MODULE_NAME][$name];
    }
}