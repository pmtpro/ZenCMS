<?php
/**
 * name = Quản lí cache
 * icon = fa fa-retweet
 * position = 20
 */
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

load_helper('fhandle');

$path_cache = __FILES_PATH . '/systems/cache/data';
$dir_list = glob($path_cache.'/*', GLOB_ONLYDIR);
if (isset($_POST['submit-delete-cache'])) {
    foreach ($dir_list as $dir) {
        rrmdir($dir);
    }
    ZenView::set_success(1);
}
$data['total_cache'] = 0;
$data['total_cache_size'] = 0;
$dir_list = glob($path_cache.'/*', GLOB_ONLYDIR);
if ($dir_list) foreach ($dir_list as $dir) {
    $list_cache = glob($dir . '/*');
    $data['total_cache'] += count($list_cache);
    $data['total_cache_size'] += foldersize($dir);
}
ZenView::set_tip('Có tất cả <b>' . $data['total_cache'] . '</b> file cache trong hệ thống chiếm <b>' . size2text($data['total_cache_size']) . '</b>');
ZenView::set_title('Quản lí cache');
$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/caches', 'Caches');
$tree[] = url(HOME.'/admin/caches/site', 'Quản lí cache');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('admin/caches/site');