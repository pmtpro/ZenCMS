<?php
/**
 * name = Cài đặt bảo mật
 * icon = glyphicon glyphicon-lock
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

$security = load_library('security');
$validation = load_library('validation');

$user = $obj->user;
$model = $obj->model->get('account');

if (isset($_POST['submit-change-password'])) {
    if (md5(md5($_POST['password'])) != $user['password']) {
        ZenView::set_error('Mật khẩu cũ không đúng', 'security-password');
    } else {
        if ($_POST['new-password'] != $_POST['re-new-password'] || empty($_POST['new-password']) || empty($_POST['re-new-password'])) {
            ZenView::set_error('Xác nhận mật khẩu mới không hợp lệ', 'security-password');
        } else {
            $newPass = md5(md5($_POST['new-password']));
            if ($model->update_user($user['id'], array('password'=>$newPass))) {
                ZenView::set_success('Thay đổi mật khẩu thành công, hệ thông sẽ tự động logout để cập nhật token mới', 'security-password');
            } else {
                ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại', 'security-password');
            }
        }
    }
}

if (isset($_POST['submit-change-email'])) {
    if (md5(md5($_POST['confirm-password'])) != $user['password']) {
        ZenView::set_error('Xác nhận mât khẩu không đúng', 'security');
    } else {
        if (!$validation->isValid('email', $_POST['email'])) {
            ZenView::set_error('Không đúng định dạng mail', 'security-email');
        } else {
            if (!$model->update_user($user['id'], array('email' => h($security->cleanXSS($_POST['email']))))) {
                ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại!', 'security-email');
            } else {
                ZenView::set_success('Thay đổi email thành công', 'security-email');
            }
        }
    }
}

ZenView::set_title('Bảo mật tài khoản');
$data['user'] = $user;
$tree[] = url(HOME.'/account', 'Tài khoản');
$tree[] = url(HOME.'/account/settings', 'Cài đặt');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('account/settings/security');
