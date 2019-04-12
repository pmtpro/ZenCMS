<?php
/**
 * name = Chỉnh sửa thông tin
 * icon = info
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

$user = $obj->user;
$model = $obj->model->get('account');

if (isset($arg[0])) {
    $act = $security->cleanXSS($arg[0]);
}

if (isset($_POST['sub_edit'])) {

    if (empty($_POST['fullname'])) {

        $data['notices'][] = 'Bạn chưa nhập tên';

    } elseif (strlen($_POST['fullname']) > 100) {

        $data['notices'][] = 'Đây không phải tên thật của bạn';

    }

    if (empty($_POST['day']) || empty($_POST['month']) || empty($_POST['year'])) {

        $data['notices'][] = 'Bạn chưa nhập ngày sinh';
    } else {

        if (!$validation->isValid('birthday', $_POST['day']) ||
            !$validation->isValid('birthmonth', $_POST['month']) ||
            !$validation->isValid('birthyear', $_POST['year'])) {

            $data['notices'][] = 'Định dạng ngày sinh không đúng';
        }
    }

    if (empty($_POST['sex']) || !in_array($_POST['sex'], array('male', 'female'))) {
        $_POST['sex'] = '';
    }

    if (!empty($_POST['email'])) {

        if (!$validation->isValid('email', $_POST['email'])) {

            $data['notices'][] = 'Sai định dạng email';
        }
    }

    if (empty($data['notices'])) {

        $update['fullname'] = h($_POST['fullname']);
        $update['email'] = h($_POST['email']);
        $update['sex'] = h($_POST['sex']);
        $update['birth'] = $_POST['day'] . '-' . $_POST['month'] . '-' . $_POST['year'];
        $update['birth'] = h($update['birth']);

        if ($model->update_user($user['id'], $update)) {

            $data['success'] = 'Thành công!';

            $user = _reload_user_data();

        } else {

            $data['notices'][] = 'Không thể ghi dữ liệu';
        }
    }
}

$user['sex_selected_male'] = '';
$user['sex_selected_female'] = '';
$user['sex_selected_unknown'] = '';

if ($user['sex'] == 'male') {
    $user['sex_selected_male'] = 'selected';
} elseif ($user['sex'] == 'female') {
    $user['sex_selected_female'] = 'selected';
} else {
    $user['sex_selected_unknown'] = 'selected';
}

$tree[] = url(_HOME.'/account', 'Tài khoản');
$tree[] = url(_HOME.'/account/settings', 'Cài đặt');
$tree[] = url(_HOME.'/account/settings/info', 'Sửa thông tin');
$data['display_tree'] = display_tree($tree);
$data['page_title'] = 'Chỉnh sửa hồ sơ';
$data['user'] = $user;
$obj->view->data = $data;
$obj->view->show('account/settings/info');

?>