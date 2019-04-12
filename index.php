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

ob_start();

/**
 * * define the key access for all file **
 */
define('__ZEN_KEY_ACCESS', rand());
/**
 * * define the site path **
 */
define('__SITE_PATH', realpath(dirname(__FILE__)));
/**
 * * define the modules path **
 */
define('__MODULES_PATH', __SITE_PATH . '/modules');
/**
 * * define the templates path **
 */
define('__TEMPLATES_PATH', __SITE_PATH . '/templates');
/**
 * * define the systems path **
 */
define('__SYSTEMS_PATH', __SITE_PATH . '/systems');
/**
 * * define the files path **
 */
define('__FILES_PATH', __SITE_PATH . '/files');
/**
 * * define the files path **
 */
define('__FOLDER_TPL_NAME', 'tpl');
/**
 * * define the tmp path **
 */
define('__TMP_DIR', __FILES_PATH . '/systems/tmp');

/**
 * start session *
 */
session_start();

/**
 * include the config file
 */
include_once __SYSTEMS_PATH . '/includes/ZenConfig.php';

/**
 * include the start file
 */
include_once __SYSTEMS_PATH . '/includes/ZenStartProcess.php';

/**
 * include the initialization file
 */
include_once __SYSTEMS_PATH . '/includes/ZenInit.php';

/**
 * include the core file
 */
include_once __SYSTEMS_PATH . '/includes/ZenCore.php';

/**
 * load the router
 */
$registry->router = new ZenRouter($registry);

/**
 * set the controller path
 */
$registry->router->setPath(__MODULES_PATH);

/**
 * * load the controller **
 */
$registry->router->loader();

/**
 * * include the system.php file **
 */
include_once __SYSTEMS_PATH . '/includes/ZenEndProcess.php';
