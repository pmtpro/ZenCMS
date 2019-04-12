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
 * start count cache
 */
$GLOBALS['count']['cache'] = 0;

/**
 * include info source
 */
include_once __SYSTEMS_PATH . '/includes/PHP/lib.php';

/**
 * include version
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenVERSION.php';

/**
 * include info source
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenINFO.php';

/**
 * include private file
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenPRIVATE.php';

/**
 * include database info
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenDB.php';

/**
 * include main config
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenMAINCONFIG.php';

/**
 * include define
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenDEFINE.php';

/**
 * include mime types
 */
include_once __SYSTEMS_PATH . '/includes/config/ZenMIMETYPES.php';