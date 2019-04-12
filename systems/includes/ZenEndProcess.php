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

if (!isset($_SESSION['last_url'])) {

    $_SESSION['last_url'] = array();
}

if (isset($_SESSION['last_url'])) {

    if (!is_array($_SESSION['last_url'])) {

        $_SESSION['last_url'] = array();
    }
    if (end($_SESSION['last_url']) != curPageURL()) {

        $_SESSION['last_url'][] = curPageURL();
    }
}

@ob_end_flush();