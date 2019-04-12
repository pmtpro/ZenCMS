<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
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
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

ini_set('memory_limit', '-1');

ob_start();
/**
 * start session *
 */
session_start();

/**
 * * define the key access for all file **
 */
define('__ZEN_KEY_ACCESS', 'ZENCMS');
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
 * include the config file
 */
require __SYSTEMS_PATH . '/includes/ZenConfig.php';

/**
 * include the start file
 */
require __SYSTEMS_PATH . '/includes/ZenStartProcess.php';

/**
 * include the initialization file
 */
require __SYSTEMS_PATH . '/includes/ZenInit.php';

/**
 * include the core file
 */
require __SYSTEMS_PATH . '/includes/ZenCore.php';

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
require __SYSTEMS_PATH . '/includes/ZenEndProcess.php';
