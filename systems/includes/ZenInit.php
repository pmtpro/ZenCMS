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

/** include the caching class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenCaching.class.php';

/** include the model class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenModel.class.php';

/** include the controller class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenController.class.php';

/** include the template class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenView.class.php';

/** include the template class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenTemplate.class.php';

/** include the registry class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenRegistry.class.php';

/** include the settings class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenSettings.class.php';

/** include the settings class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenHook.class.php';

/** include the router class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenRouter.class.php';

/** include the database class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenDatabase.class.php';

/** include the config class ** */
include __SYSTEMS_PATH . '/init/' . 'ZenConfig.class.php';

/** a new registry object ** */
$registry = new ZenRegistry;

/** create the database registry object ** */
$db = & ZenDatabase::getInstance();
$registry->db = $db;

