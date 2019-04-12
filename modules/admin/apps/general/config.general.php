<?php
/**
 * name = Cấu hình chính
 * icon = admin_general_config
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
$model = $obj->model->get('admin');
$parse = load_library('parse');

if (isset($_POST['sub'])) {

    if (!$parse->valid_url($_POST['home'])) {
        $data['errors'] = 'Địa chỉ trang chủ không chính xác';
    }
    $update['home'] = $_POST['home'];

    if (!strlen($_POST['home'])) {
        $data['errors'] = 'Bạn chưa nhập tiêu đề trang';
    }
    $update['title'] = h($_POST['title']);

    $update['keyword'] = h($_POST['keyword']);

    if (strlen($_POST['des']) > 250) {
        $data['notices'][] = 'Chiều dài mô tả lớn hơn 250 kí tự không tốt cho seo! (Hiện tại: ' . strlen($_POST['des']) . ' kí tự)<br/>
                Chú ý: Chiều dài mô tả vào khoảng 160-250 kí tự';
    }
    $update['des'] = h($_POST['des']);

    if (!isset($data['errors'])) {

        $model->update_config($update);

        $data['success'] = 'Thành công!';

        $obj->config->reload();
    }
}

if (isset($_POST['sub_mail'])) {

    if (isset($_POST['mail_host']) && strlen($_POST['mail_host']) > 0 && strlen($_POST['mail_host']) <= 255) {
        $update_mail['mail_host'] = $_POST['mail_host'];
    } else {
        $data['errors'][] = 'Địa chỉ host mail không chính xác';
    }

    if (isset($_POST['mail_port']) && is_numeric($_POST['mail_port']) && !empty($_POST['mail_port'])) {
        $update_mail['mail_port'] = $_POST['mail_port'];
    } else {
        $data['errors'][] = 'Cổng không chính xác';
    }

    if (isset($_POST['mail_smtp_secure']) && ($_POST['mail_smtp_secure'] == 'tls' || $_POST['mail_smtp_secure'] == 'ssl')) {
        $update_mail['mail_smtp_secure'] = $_POST['mail_smtp_secure'];
    } else {
        $data['errors'][] = 'Không tồn tại phương thức mã hóa này';
    }

    if (isset($_POST['mail_smtp_auth']) && !empty($_POST['mail_smtp_auth'])) {
        $update_mail['mail_smtp_auth'] = 1;
    } else {
        $update_mail['mail_smtp_auth'] = 0;
    }

    if (isset($_POST['mail_username'])) {
        $update_mail['mail_username'] = $_POST['mail_username'];
    } else {
        $update_mail['mail_username'] = '';
    }

    if (isset($_POST['mail_password'])) {
        $update_mail['mail_password'] = base64_encode($_POST['mail_password']);
    } else {
        $update_mail['mail_password'] = '';
    }

    if (isset($_POST['mail_setfrom']) && $parse->valid_email($_POST['mail_setfrom'])) {
        $update_mail['mail_setfrom'] = $_POST['mail_setfrom'];
    } else {
        $data['errors'][] = 'Email gửi không chính xác';
    }

    if (isset($_POST['mail_name'])) {
        $update_mail['mail_name'] = $_POST['mail_name'];
    } else {
        $update_mail['mail_name'] = '';
    }

    if (!isset($data['errors'])) {
        $model->update_config($update_mail);
        $data['success'] = 'Thành công!';
        $obj->config->reload();
    }
}

$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/general', 'Tổng quan');
$tree[] = url(_HOME.'/admin/general/config', 'Cấu hình chính');
$data['display_tree'] = display_tree($tree);

$data['page_title'] = 'Cấu hình chính';
$obj->view->data = $data;
$obj->view->show('admin/general/config');
?>