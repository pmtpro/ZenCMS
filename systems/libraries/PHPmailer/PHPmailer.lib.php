<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Note: This is a trial version MVC model
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    include_once __SYSTEMS_PATH . '/libraries/PHPmailer/php4/class.phpmailer.php';

} else {
    include_once __SYSTEMS_PATH . '/libraries/PHPmailer/php5/class.phpmailer.php';

}
?>