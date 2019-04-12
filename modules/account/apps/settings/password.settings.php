<?php
/**
 * name = Thay đổi mật khẩu
 * icon = password
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

$security = load_library('security');
$validation = load_library('validation');
$security = load_library('security');

$user = $obj->user;
$model = $obj->model->get('account');

$user_config = sys_config('user');
$password_min_len = $user_config['password']['min_length'];
$password_max_len = $user_config['password']['max_length'];

if (isset($arg[0])) {
    $act = $security->cleanXSS($arg[0]);
}

if (isset($_POST['sub_change'])) {

    /**
     * check token security
     */
    if(!$security->check_token('token_change_password')) {

        $data['errors'] = 'Lỗi dữ liệu';

    } else {

        $password = md5(md5($_POST['oldpassword']));

        if ($password != $user['password']) {

            $data['notices'] = 'Mật khẩu cũ không đúng';

        } else {
            $newpassword = $_POST['newpassword'];

            $re_newpassword = $_POST['re_newpassword'];

            if ($newpassword != $re_newpassword) {

                $data['notices'] = 'Xác nhận mật khẩu không đúng';

            } else {

                if (strlen($newpassword) < $password_min_len or strlen($newpassword) > $password_max_len) {

                    $data['errors'] = 'Mật khẩu chỉ được phép trong khoảng ' . $password_min_len . ' đến ' . $password_max_len . ' kí tự';

                } else {

                    $update['password'] = md5(md5($newpassword));

                    if(!$model->update_user($user['id'], $update)) {

                        $data['errors'] = 'Lỗi ghi dữ liệu';
                    } else {
                        $data['success'] = 'Thay đổi mật khẩu thành công!';
                    }
                }
            }
        }

    }
}

$tree[] = url(_HOME.'/account', 'Tài khoản');
$tree[] = url(_HOME.'/account/settings', 'Cài đặt');
$tree[] = url(_HOME.'/account/settings/password', 'Mật khẩu');
$data['display_tree'] = display_tree($tree);
$data['page_title'] = 'Thay đổi mật khẩu';
$data['user'] = $user;
$data['token'] = $security->get_token('token_change_password');
$obj->view->data = $data;
$obj->view->show('account/settings/password');
?>