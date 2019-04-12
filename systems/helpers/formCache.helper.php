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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

/**
 * set form cache
 *
 * @param $name
 * @return bool
 */
function sFormCache($name)
{

    if (empty ($name)) {
        return;
    }

    if (!defined('__MODULE_NAME')) {
        return;
    }
    if (isset($_POST[$name])) {

        $out = $_POST[$name];
    } else {

        $out = false;
    }

    $_SESSION['formCache'][__MODULE_NAME][$name] = $out;
}

/**
 * gform cache
 *
 * @param $name
 * @param string $return
 * @return bool|string
 */
function gFormCache($name, $return = false)
{

    if (empty ($name)) {
        return false;
    }

    if (!defined('__MODULE_NAME')) {

        return false;
    }

    if (isset ($_SESSION['formCache'][__MODULE_NAME][$name]) && !empty ($_SESSION['formCache'][__MODULE_NAME][$name])) {

        if ($return == false) {

            return $_SESSION['formCache'][__MODULE_NAME][$name];
        }
        return $return;
    }
}

?>