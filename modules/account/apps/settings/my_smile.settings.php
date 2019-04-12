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

$security = load_library('security');
$p = load_library('pagination');
$model = $obj->model->get('account');
$user = $obj->user;

if (isset($_POST['submit-delete'])) {
    foreach ($_POST['smile'] as $sm) {
        foreach($user['smiles'] as $key => $my_smile) {
            if ($my_smile == $sm) {
                unset($user['smiles'][$key]);
            }
        }
        $update['smiles'] = serialize($user['smiles']);
        if ($model->update_user($user['id'], $update)) {
            ZenView::set_success(1);
        } else {
            ZenView::set_error('Lỗi trong khi xóa');
        }
    }
}

$data['count_my_smiles'] = count($user['smiles']);
$data['smiles_pagination'] = '';
$total = count($user['smiles']);
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
    ZenView::set_paging($p->navi_page());
}
$data['smiles'] = $smiles;
$data['user'] = $user;

ZenView::set_title('My smile');
$tree[] = url(HOME . '/account', 'Tài khoản');
$tree[] = url(HOME . '/account/settings', 'Cài đặt');
$tree[] = url(HOME . '/account/settings/smiles', 'Smiles');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('account/settings/smiles/my_smile');