<?php
/**
 * name = Chữ kí
 * icon = sign
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

if(isset($_POST['sub_change'])) {

    $sign = $_POST['sign'];

    if (strlen($sign) > 50) {

        $data['notices'][] = 'Chữ kí quá dài. Chữ kí chỉ cho phép <b>nhỏ hơn 50 kí tự</b> (Hiện tại '.strlen($sign).' kí tự)';

    } else {

        $update['sign'] = h($sign);

        if(!$model->update_user($user['id'], $update)) {
            $data['notices'][] = 'Lỗi dữ liệu!';
        } else {
            $data['success'] = 'Cập nhât chữ kí thành công!';
            $user = _reload_user_data();
        }
    }

}

$tree[] = url(_HOME.'/account', 'Tài khoản');
$tree[] = url(_HOME.'/account/settings', 'Cài đặt');
$tree[] = url(_HOME.'/account/settings/sign', 'Chữ kí');
$data['display_tree'] = display_tree($tree);
$data['page_title'] = 'Thay đổi chữ kí';
$data['user'] = $user;
$obj->view->data = $data;
$obj->view->show('account/settings/sign');
?>