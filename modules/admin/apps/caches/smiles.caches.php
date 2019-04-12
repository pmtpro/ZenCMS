<?php
/**
 * name = Smile
 * icon = admin_caches_smiles
 * position = 1
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

if (isset($_POST['sub_reload_smiles_cache'])) {

    $ignored = array('.', '..', '.svn', '.htaccess');
    $cache = __FILES_PATH . '/systems/cache/smiles.dat';
    $path = __FILES_PATH . '/images/smiles';
    $base = _URL_FILES_IMAGES . '/smiles';
    $list_smiles = array();

    $lists = scandir($path);

    foreach ($lists as $folder) {

        $folder_path = $path . '/' . $folder;

        if (is_dir($folder_path) && !in_array($folder, $ignored) && is_readable($folder_path)) {

            $smiles = scandir($folder_path);

            foreach ($smiles as $smile) {

                if (is_file($folder_path . '/' . $smile) && !in_array($smile, $ignored) && is_readable($folder_path . '/' . $smile)) {

                    $url = $base . '/' . $folder . '/' . $smile;

                    if ($folder != '_basic') {

                        $key = ':' . preg_replace('/\.' . get_ext($smile) . '$/', '', $smile) . ':';

                        $list_smiles[$key] = '<img src="' . $url . '"/ title="' . $smile . '">';

                    } else {

                        $key = ' '.hexToStr(preg_replace('/\.' . get_ext($smile) . '$/', '', $smile)).' ';

                        $list_smiles[$key] = ' <img src="' . $url . '"/ title="' . $smile . '"> ';

                    }

                }
            }

        }
    }
    $dat = serialize($list_smiles);

    if (file_put_contents($cache, $dat)) {

        $data['success'] = 'Cập nhật cache thành công';
    } else {
        $data['errors'] = 'Không thể lưu cache';
    }
}

$data['page_title'] = 'Cập nhật smile';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/caches', 'Caches');
$tree[] = url(_HOME.'/admin/caches/smiles', $data['page_title']);
$data['display_tree'] = display_tree($tree);
$obj->view->data = $data;
$obj->view->show('admin/caches/smiles');
?>