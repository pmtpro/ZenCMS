<?php
/**
 * name = Smiles
 * icon = smiles
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

$data['page_title'] = 'Smiles';
$security = load_library('security');
$p = load_library('pagination');
$model = $obj->model->get('account');
$user = $obj->user;
$tree[] = url(_HOME . '/account', 'Tài khoản');
$tree[] = url(_HOME . '/account/settings', 'Cài đặt');


if (isset($_POST['sub_add'])) {

    if (count($user['smiles']) >= 20) {

        $data['notices'] = 'Nhiều smile phết rồi đấy';

    } else {

        $arr_smiles = $_POST['smile'];

        $arr_smiles = array_merge($user['smiles'], $arr_smiles);

        $arr_smiles = array_unique($arr_smiles);

        $update['smiles'] = serialize($arr_smiles);

        if ($model->update_user($user['id'], $update)) {

            $data['success'] = 'Thành công';
            $user = _reload_user_data();
        } else {
            $data['errors'] = 'Lỗi ghi dữ liệu';
        }
    }
}

$data['user'] = $user;

$data['count_my_smiles'] = count($user['smiles']);

$folder = '';
if (isset($app[1])) {
    $folder = $security->cleanXSS($app[1]);
}

$path = __FILES_PATH . '/images/smiles/' . $folder;
$base = _HOME . '/files/images/smiles/' . $folder;

$ignored = array('.', '..', '.svn', '.htaccess', '_basic');

if (is_dir($path) && is_readable($path)) {
    $lists = scandir($path);
} else {
    $lists = array();
}

if (empty($folder)) {

    $cats = array();
    foreach ($lists as $li) {
        if (is_dir($path . $li) && !in_array($li, $ignored)) {
            $cats[] = $li;
        }
    }
    $data['cats'] = $cats;
    $data['display_tree'] = display_tree($tree);
    $obj->view->data = $data;
    $obj->view->show('account/settings/smiles/smiles_folder');

} else {

    if (!in_array($folder, $ignored)) {

        $smiles = array();

        $total = count($lists);

        for ($i = 1; $i <= count($lists); $i++) {

            if (isset($lists[$i])) {

                if (!is_file($path . '/' . $lists[$i]) || in_array($lists[$i], $ignored)) {
                    $total--;
                }
            } else {
                unset($lists[$i]);
            }
        }

        $limit = 10;
        $p->setLimit($limit);
        $p->SetGetPage('page');
        $start = $p->getStart();
        $end = $start + $limit;
        $p->setTotal($total);

        $data['smiles_pagination'] = $p->navi_page();

        for ($i = $start; $i < $end; $i++) {

            if (isset($lists[$i])) {

                if (is_file($path . '/' . $lists[$i]) && !in_array($lists[$i], $ignored)) {

                    $key = ':' . preg_replace('/\.' . get_ext($lists[$i]) . '$/', '', $lists[$i]) . ':';
                    $smiles[$key] = $base . '/' . $lists[$i];
                }
            }
        }

        $data['smiles'] = $smiles;
        $tree[] = url(_HOME . '/account/settings/smiles', 'Smiles');
        $data['display_tree'] = display_tree($tree);
        $obj->view->data = $data;
        $obj->view->show('account/settings/smiles/smiles_list');

    } else {
        $data['errors'] = 'Lỗi dữ liệu';
        $data['smiles_pagination'] = '';
        $data['smiles'] = array();
        $tree[] = url(_HOME . '/account/settings/smiles', 'Smiles');
        $data['display_tree'] = display_tree($tree);
        $obj->view->data = $data;
        $obj->view->show('account/settings/smiles/smiles_list');
    }
}
?>