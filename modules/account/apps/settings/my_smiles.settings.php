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

$data['page_title'] = 'My Smiles';
$security = load_library('security');
$p = load_library('pagination');
$model = $obj->model->get('account');
$user = $obj->user;
$tree[] = url(_HOME . '/account', 'Tài khoản');
$tree[] = url(_HOME . '/account/settings', 'Cài đặt');
$tree[] = url(_HOME . '/account/settings/smiles', 'Smiles');
$data['display_tree'] = display_tree($tree);


if (isset($_POST['sub_delete'])) {

    foreach ($_POST['smile'] as $sm) {

        foreach($user['smiles'] as $key => $my_smile) {

            if ($my_smile == $sm) {

                unset($user['smiles'][$key]);
            }
        }
        $update['smiles'] = serialize($user['smiles']);

        if ($model->update_user($user['id'], $update)) {

            $data['success'] = 'Thành công';
        } else {
            $data['notices'] = 'Không thế xóa';
        }
    }
}

$data['count_my_smiles'] = count($user['smiles']);

$data['smiles_pagination'] = '';

$total = @count($user['smiles']);

$smiles = array();

if (!$total) {

    $smiles = array();

} else {

    $newsm = array();

    foreach ($user['smiles'] as $sm) {
        $newsm[] = $sm;
    }
    $user['smiles'] = $newsm;

    $limit = 10;
    $p->setLimit($limit);
    $p->SetGetPage('page');
    $start = $p->getStart();
    $end = $start + $limit;
    $p->setTotal($total);

    for ($i = $start; $i < $end; $i++) {

        if (isset($user['smiles'][$i])) {
            $smiles[] = $user['smiles'][$i];
        }
    }

    $data['smiles_pagination'] = $p->navi_page();
}
$data['smiles'] = $smiles;
$data['user'] = $user;
$obj->view->data = $data;
$obj->view->show('account/settings/smiles/my_smiles');
?>