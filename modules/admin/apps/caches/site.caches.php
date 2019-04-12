<?php
/**
 * name = Quản lí cache
 * icon = admin_caches_site
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

load_helper('fhandle');

$path_cache = __FILES_PATH . '/systems/cache/data';

$files = glob($path_cache.'/*');

if (isset($_POST['sub_delete_cache'])) {

    foreach ($files as $f) {

        unlink($f);
    }
    $data['success'] = 'Thành công';
}

$files = glob($path_cache.'/*');

$data['total_cache'] = count($files);

$data['total_cache_size'] = foldersize($path_cache);

$data['page_title'] = 'Quản lí cache';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/caches', 'Caches');
$tree[] = url(_HOME.'/admin/caches/site', $data['page_title']);
$data['display_tree'] = display_tree($tree);
$obj->view->data = $data;
$obj->view->show('admin/caches/site');
?>