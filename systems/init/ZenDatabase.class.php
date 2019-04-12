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
 * make sure you support php mysqli_connect or not
 */
if (function_exists('mysqli_connect')) {

    /**
     * include mysqli
     */
    include __SYSTEMS_PATH.'/init/ZenDatabase/mysqli.class.php';
} else {

    include __SYSTEMS_PATH.'/init/ZenDatabase/mysql.class.php';
}
?>