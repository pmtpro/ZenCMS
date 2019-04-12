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
/**
 * get model
 */
$accModel = $obj->model->get('account');

/**
 * load library
 */
$security = load_library('security');
$valid = load_library('validation');
$p = load_library('pagination');

$baseUrl = HOME . '/admin/members';

if (isset($_GET['id'])) {
    $id = $security->removeSQLI($_GET['id']);
} else {
    $id = 0;
}
if (!$accModel->user_is_exists($id)) {
    ZenView::set_error('Xin lỗi, không tồn tại người dùng này!', ZPUBLIC, $baseUrl . '/list');
    exit;
}
$data['not_allow'] = false;

$data['user'] = $accModel->get_user_data($id);
$perm_config = sysConfig('user_perm');
$list_perm_value = array_keys($perm_config['key']);

if ((($perm_config['key'][$obj->user['perm']] <= $perm_config['key'][$data['user']['perm']]) || $data['user']['protect'] == 1) && $obj->user['id'] != $data['user']['id']) {
    ZenView::set_error('Xin lỗi, bạn không đủ quyền để chỉnh sửa người này!');
    $data['not_allow'] = true;
} else {
    if (isset($_POST['submit-save'])) {
        if ($_POST['nickname'] != $data['user']['nickname']) {
            if (strlen($_POST['nickname'])<2 || strlen($_POST['nickname'])>20) {
                ZenView::set_error('Nickname phải nằm trong khoảng từ 2 đến 20 kí tự');
            } else {
                $update['nickname'] = h($security->cleanXSS($_POST['nickname']));
            }
        }
        if (!empty($_POST['password'])) {
            if (!isset($_POST['repassword']) || $_POST['password'] != $_POST['repassword']) {
                ZenView::set_error('Xác nhận password không hợp lệ!');
            } else {
                $update['password'] = md5(md5($_POST['password']));
            }
        }
        if ($_POST['email'] != $data['user']['email']) {
            if (!$valid->isValid('email', $_POST['email'])) {
                ZenView::set_error('Không đúng định dạng email!');
            } else {
                $update['email'] = h($security->cleanXSS($_POST['email']));
            }
        }
        if ($_POST['perm'] != $data['user']['perm']) {
            if (in_array($_POST['perm'], $list_perm_value)) {
                $update['perm'] = $_POST['perm'];
            }
        }
        if (!empty($update)) {
            if ($accModel->update_user($id, $update)) {
                ZenView::set_success(1);
                $data['user'] = $accModel->get_user_data($id);
            } else {
                ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại!');
            }
        }
    }
}

$page_title = 'Chỉnh sửa: ' . $data['user']['nickname'];
ZenView::set_title($page_title);
$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url($baseUrl, 'Thành viên');
$tree[] = url($baseUrl . '/list', 'Danh sách');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('admin/members/editor');