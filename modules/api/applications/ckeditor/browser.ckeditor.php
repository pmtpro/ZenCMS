<?php
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
if ((is_ajax_request() && !confirmRequest($_GET['token'])) || !is_ajax_request()) {
    exit;
}
/**
 * get hook api
 */
$hook = $obj->hook->get('api');

/**
 * load time helper
 */
load_helper('time');

/************* upload image **************/
$dirStore = 'files/posts/images';
/**
 * set directory upload image
 */
$imageDir = __SITE_PATH . '/' . $dirStore;
$today = getdate();
$subDir = $today['mon'] . '-' . $today['year'];
$currBrowserDir = $imageDir . '/' . $subDir;

if (!empty($_POST['dir']) && is_dir($imageDir . '/' . $_POST['dir'])) {
    $subDir = $_POST['dir'];
    $currBrowserDir = $imageDir . '/' . $subDir;
}
if (!file_exists($currBrowserDir) || !is_dir($currBrowserDir)) {
    $currBrowserDir = $imageDir;
}
/**
 * scan file
 */
$imageList = scandir($currBrowserDir);
$img_array = array();
$img_dsort = array();
$final_array = array();
foreach ($imageList as $key=>$img) {
    $hash = explode('.', $img);
    $ext = end($hash);
    if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
        unset($imageList[$key]);
    } else {
        $out = array();
        $out['name'] = $img;
        $out['full_path'] = $currBrowserDir . '/' . $img;
        $out['base_url'] = '/' . $dirStore . '/' . $subDir . '/' . $img;
        $out['full_url'] = HOME . $out['base_url'];
        $out['mtime'] = filemtime($out['full_path']);
        $out['display_mtime'] = m_timetostr($out['mtime']);
        $img_array[] = $out;
        $img_dsort[] = $out['mtime'];
    }
}
/**
 * sort list file by mod time
 */
$merge_arrays = array_combine($img_dsort, $img_array);
krsort($merge_arrays);
foreach($merge_arrays as $key => $value) {
    $final_array[] = $value;
}
$data['image_list'] = $final_array;

/**
 * scan directory
 */
$list_dir = scandir($imageDir);
$dir_array = array();
$dir_dsort = array();
$final_array = array();
foreach ($list_dir as $dir) {
    if (!in_array($dir, array('.', '..')) && is_dir($imageDir . '/' . $dir)) {
        $outDir['name'] = $dir;
        $outDir['full_path'] = $imageDir . '/' . $dir;
        $outDir['mtime'] = filemtime($outDir['full_path']);
        $outDir['display_mtime'] = m_timetostr($outDir['mtime']);
        $dir_array[] = $outDir;
        $dir_dsort[] = $outDir['mtime'];
    }
}
/**
 * sort list dir by mod time
 */
$merge_arrays = array_combine($dir_dsort, $dir_array);
krsort($merge_arrays);
foreach($merge_arrays as $key => $value) {
    $final_array[] = $value;
}
$data['dir_list'] = $final_array;

$data['current_dir_name'] = $subDir;
$data['CKEditorFuncNum'] = isset($_GET['CKEditorFuncNum']) ? $_GET['CKEditorFuncNum'] : 1;
$obj->view->data = $data;
$obj->view->show('api/ckeditor/browser', array('only_map' => true));