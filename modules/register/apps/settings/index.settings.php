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


if (isset($_POST['sub_settings'])) {

    if (!empty($_POST['register_turn_off'])) {

        $update['register_turn_off'] = 1;
    } else {

        $update['register_turn_off'] = 0;
    }

    if (!empty($_POST['register_turn_on_authorized_email'])) {

        $update['register_turn_on_authorized_email'] = 1;
    } else {

        $update['register_turn_on_authorized_email'] = 0;
    }

    if (!empty($_POST['register_message'])) {

        $update['register_message'] = h($_POST['register_message']);

    } else {

        $update['register_message'] = 'Đây không phải thời gian đăng kí tài khoản';
    }

    if ($obj->model->get()->_update_config($update)) {

        $data['success'] = 'Thành công';
        $obj->config->reload();
    } else {

        $data['notices'][] = 'Lỗi dữ liệu';
    }
}

$data['page_title'] = 'Cài đặt đăng kí';
$tree[] = url(_HOME . '/register/settings', $data['page_title']);
$data['display_tree'] = display_tree_modulescp($tree);
$obj->view->data = $data;
$obj->view->show('register/settings/index');