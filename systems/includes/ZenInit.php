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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

/** include the caching class ** */
require __SYSTEMS_PATH . '/init/ZenCaching.class.php';

/** include the model class ** */
require __SYSTEMS_PATH . '/init/ZenModel.class.php';

/** include the controller class ** */
require __SYSTEMS_PATH . '/init/ZenController.class.php';

/** include the view class ** */
require __SYSTEMS_PATH . '/init/ZenView.class.php';

/** include the template class ** */
require __SYSTEMS_PATH . '/init/ZenTemplate.class.php';

/** include the registry class ** */
require __SYSTEMS_PATH . '/init/ZenRegistry.class.php';

/** include the settings class ** */
require __SYSTEMS_PATH . '/init/ZenSettings.class.php';

/** include the hook class ** */
require __SYSTEMS_PATH . '/init/ZenHook.class.php';

/** include the router class ** */
require __SYSTEMS_PATH . '/init/ZenRouter.class.php';

/** include the database class ** */
require __SYSTEMS_PATH . '/init/ZenDatabase.class.php';

/** include the config class ** */
require __SYSTEMS_PATH . '/init/ZenConfig.class.php';

/** a new registry object ** */
$registry = new ZenRegistry;

/** create the database registry object ** */
$db = &ZenDatabase::getInstance();
$zen['db'] = $db;
$registry->db = $db;