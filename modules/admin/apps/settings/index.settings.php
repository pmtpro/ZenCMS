<?php
/**
 * folder_name = Cài đặt chức năng
 * position = 20
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

$data['page_title'] = 'Cài đặt chức năng';

$path = __MODULES_PATH . '/admin/apps/settings';

/** @noinspection PhpParamsInspection */
$data['menus'] = get_apps($path, 'admin/settings');

$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/settings', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/settings/index');
