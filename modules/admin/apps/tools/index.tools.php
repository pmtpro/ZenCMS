<?php
/**
 * folder_name = Tools
 * position = 60
 */
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

$model = $obj->model->get('admin');
$security = load_library('security');
$p = load_library('pagination');

$data['page_title'] = 'Tools';

$path = __MODULES_PATH . '/admin/apps/tools';

/** @noinspection PhpParamsInspection */
$data['menus'] = get_apps($path, 'admin/tools');

$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/general', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/tools/index');
