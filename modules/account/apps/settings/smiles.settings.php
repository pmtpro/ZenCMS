<?php
/**
 * name = Smiles
 * icon = fa fa-smile-o
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


$security = load_library('security');
$p = load_library('pagination');
$obj->hook->get('account');
$model = $obj->model->get('account');
$user = $obj->user;
if (!is_array($user['smiles'])) $user['smiles'] = array();

ZenView::set_title('Danh sách smile');
$tree[] = url(HOME . '/account', 'Tài khoản');
$tree[] = url(HOME . '/account/settings', 'Cài đặt');
ZenView::set_breadcrumb($tree);

/**
 * add smile
 */
if (isset($_POST['submit-add'])) {

    $arr_smiles = $_POST['smile'];
    if (is_array($arr_smiles)) {
        /**
         * settings_max_user_smile hook*
         */
        $num = $obj->hook->loader('settings_max_user_smile', 20);
        if (count($user['smiles']) + count($arr_smiles) >= $num) {
            ZenView::set_notice('Nhiều smile phết rồi đấy');
        } else {
            $arr_smiles = array_merge($user['smiles'], $arr_smiles);
            $arr_smiles = array_unique($arr_smiles);
            $update['smiles'] = serialize($arr_smiles);
            if ($model->update_user($user['id'], $update)) {
                ZenView::set_success(1);
                $user = _reload_user_data();
            } else ZenView::set_error('Lỗi ghi dữ liệu');
        }
    }
}

/**
 * set sub menu
 */
ZenView::add_menu('page', ZenView::gen_menu(array(
    'full_url' => HOME . '/account/settings/my_smile',
    'name' => 'My smile',
    'icon' => 'fa fa-smile-o',
    'badge' => count($user['smiles'])
)), true);

$data['user'] = $user;
$data['count_my_smiles'] = count($user['smiles']);

$folder = '';
if (isset($app[1])) {
    $folder = $security->cleanXSS($app[1]);
}

$path = __FILES_PATH . '/systems/images/smiles/' . $folder;
$base = HOME . '/files/systems/images/smiles/' . $folder;

$ignored = array('.', '..', '.svn', '.htaccess', '_basic');

if (is_dir($path) && is_readable($path)) {
    $lists = scandir($path);
} else $lists = array();

if (empty($folder)) {
    $folders = array();
    foreach ($lists as $li) {
        if (is_dir($path . $li) && !in_array($li, $ignored)) {
            $folders[] = array(
                'name' => $li,
                'full_url' => HOME . '/account/settings/smiles/' . $li
            );
        }
    }
    $data['folders'] = $folders;
    $obj->view->data = $data;
    $obj->view->show('account/settings/smiles/folder');
} else {
    if (in_array($folder, $ignored)) {
        ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại!');
    } else {
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

        ZenView::set_paging($p->navi_page());

        for ($i = $start; $i < $end; $i++) {
            if (isset($lists[$i])) {
                if (is_file($path . '/' . $lists[$i]) && !in_array($lists[$i], $ignored)) {
                    $key = ':' . preg_replace('/\.' . getExt($lists[$i]) . '$/', '', $lists[$i]) . ':';
                    $smiles[] = array(
                        'key' => $key,
                        'name' => $lists[$i],
                        'full_url' => $base . '/' . $lists[$i],
                        'added' => in_array($key, $user['smiles'])? true: false
                    );
                }
            }
        }
        $data['smiles'] = $smiles;
    }
    ZenView::set_breadcrumb(url(HOME . '/account/settings/smiles', 'Danh sách thư mục'));
    $obj->view->data = $data;
    $obj->view->show('account/settings/smiles/list');
}