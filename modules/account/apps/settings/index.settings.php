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

$security = load_library('security');
$validation = load_library('validation');

$user = $obj->user;
$model = $obj->model->get('account');

$tree[] = url(_HOME.'/account', 'Tài khoản');
$tree[] = url(_HOME.'/account/settings', 'Cài đặt');
$data['display_tree'] = display_tree($tree);

$path = __SITE_PATH . '/modules/account/apps/settings';

/** @noinspection PhpParamsInspection */
$menus = get_apps($path, 'account/settings');

$data['page_title'] = 'Cài đặt tài khoản';
$data['menus'] = $menus;
$data['user'] = $user;
$obj->view->data = $data;
$obj->view->show('account/settings/index');
