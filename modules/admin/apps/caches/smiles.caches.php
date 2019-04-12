<?php
/**
 * name = Smile
 * icon = icon-github-alt
 * position = 1
 */
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
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
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$path = __FILES_PATH . '/systems/images/smiles';
$cache = $path . '/smiles.dat';
$base = _URL_FILES_SYSTEMS . '/images/smiles';
if (isset($_POST['submit-reload-cache'])) {

    $ignored = array('.', '..', '.svn', '.htaccess');
    $list_smiles = array();
    $lists = scandir($path);
    foreach ($lists as $folder) {
        $folder_path = $path . '/' . $folder;
        if (is_dir($folder_path) && !in_array($folder, $ignored) && is_readable($folder_path)) {
            $smiles = scandir($folder_path);
            foreach ($smiles as $smile) {
                if (is_file($folder_path . '/' . $smile) && !in_array($smile, $ignored) && is_readable($folder_path . '/' . $smile)) {
                    $url = $folder . '/' . $smile;
                    $full_url = $base . '/' . $url;
                    if ($folder != '_basic') {
                        $key = ':' . preg_replace('/\.' . getExt($smile) . '$/', '', $smile) . ':';
                        $real_key = $key;
                    } else {
                        $real_key = hexToStr(preg_replace('/\.' . getExt($smile) . '$/', '', $smile));
                        $key = ''. $real_key .'';
                    }
                    $list_smiles[$key] = array(
                        'file_name' => $smile,
                        'folder' => $folder,
                        'full_url' => $full_url
                    );
                }
            }
        }
    }
    /**
     * order smile by smile length
     */
    $order_arr = array();
    foreach ($list_smiles as $sm => $img) {
        $order_arr[$sm] = strlen($sm);
    }
    arsort($order_arr);
    $cacheSm = array();
    foreach ($order_arr as $sm => $smLen) {
        $cacheSm[$sm] = $list_smiles[$sm];
    }

    /**
     * encode smiley cache
     */
    $dat = serialize($cacheSm);
    if (file_put_contents($cache, $dat)) {
        ZenView::set_success('Cập nhật cache thành công');
    } else {
        ZenView::set_error('Không thể lưu cache');
    }
}

ZenView::set_title('Cập nhật smile');
ZenView::set_tip('Nhấn vào cập nhật để cập nhật lại toàn bộ smile có trong hệ thống!');
$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/caches', 'Caches');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('admin/caches/smiles');