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

$data['page_title'] = 'Hộp thư';
$data['user'] = $obj->user;

$path = __MODULES_PATH . '/account/apps/messages';

/** @noinspection PhpParamsInspection */
$data['menus'] = get_apps($path, 'account/messages');

$tree[] = url(_HOME.'/account', 'Tài khoản');
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('account/messages/index');