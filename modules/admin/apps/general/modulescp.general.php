<?php
/**
 * name = Modules cpanel
 * icon = admin_general_modulescp
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

$data['list_extends_modules'] = get_extend_apps('admin/general/modulescp');

$data['page_title'] = 'Module Cpanel';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/general', 'Tổng quan');
$tree[] = url(_HOME.'/admin/general/modulescp', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/general/modulescp');
