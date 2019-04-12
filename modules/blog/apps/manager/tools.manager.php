<?php
/**
 * name = Tools
 * icon = blog_manager_tools
 * position = 180
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


$data['list_extends_modules'] = get_extend_apps('blog/manager/tools');

$page_title = 'Tools';

$data['page_title'] = $page_title;
$tree[] = url(_HOME . '/blog/manager', 'Blog manager');
$tree[] = url(_HOME . '/blog/manager/tools', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);

/** @noinspection PhpParamsInspection */
$data['menus'] = get_extend_apps('blog/manager/tools');

if (empty($data['menus'])) {

    $data['errors'][] = 'Hiện tại chưa có ứng dụng nào';
}

$obj->view->data = $data;
$obj->view->show('blog/manager/tools');