<?php
/**
 * name = Bản quyền java
 * icon = admin_settings_java_copyright
 * position = 20
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

$model = $obj->model->get('admin');
$security = load_library('security');

if (isset($_POST['sub'])) {

    if (!empty($_POST['delete_confirm_java'])) {

        $update['delete_confirm_java'] = h($_POST['delete_confirm_java']);

        $model->update_config($update);

        $data['success'] = 'Thành công';

        $obj->config->reload();

    } else {

        $data['notices'][] = 'Bạn chưa nhập đầy đủ';
    }
}


$data['page_title'] = 'Cài đặt bản quyền java';
$tree[] = url(_HOME . '/admin', 'Admin CP');
$tree[] = url(_HOME . '/admin/settings', 'Cài đặt');
$tree[] = url(_HOME . '/admin/settings/java_copyright', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/settings/java_copyright');