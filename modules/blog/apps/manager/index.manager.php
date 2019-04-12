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

$data['page_title'] = 'Blog manager';

$tree[] = url(_HOME . '/blog/manager', 'Blog manager');
$data['display_tree'] = display_tree_modulescp($tree);

$path = __SITE_PATH . '/modules/blog/apps/manager';

/** @noinspection PhpParamsInspection */
$data['menus'] = get_apps($path, 'blog/manager');

$obj->view->data = $data;
$obj->view->show('blog/manager/' . $app[0]);
