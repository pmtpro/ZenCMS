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

$data['page_title'] = 'Chatbox manager';

$tree[] = url(_HOME . '/chatbox/manager', $data['page_title']);
$data['display_tree'] = display_tree_modulescp($tree);

$path = __MODULES_PATH . '/chatbox/apps/manager';

/** @noinspection PhpParamsInspection */
$data['menus'] = get_apps($path, 'chatbox/manager');

$obj->view->data = $data;
$obj->view->show('chatbox/manager/' . $app[0]);
