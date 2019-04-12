<?php
/**
 * name = Cài đặt
 * icon = manager_cpanel
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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

/**
 * get chatbox model
 */
$model = $obj->model->get('chatbox');

if (isset($_POST['sub'])) {

    if (!empty($_POST['chatbox_allow_guest_chat'])) {

        $_POST['chatbox_allow_guest_chat'] = 1;
    } else {

        $_POST['chatbox_allow_guest_chat'] = 0;
    }
    if (!is_numeric($_POST['chatbox_num_item_per_page'])) {

        $_POST['chatbox_num_item_per_page'] = 10;

    }

    $update['chatbox_allow_guest_chat'] = $_POST['chatbox_allow_guest_chat'];

    $update['chatbox_num_item_per_page'] = $_POST['chatbox_num_item_per_page'];

    if ($model->update_config($update) ) {

        $data['success'] = 'Thành công';

        $obj->config->reload();

    } else {
        $data['errors'][] = 'Lỗi dữ liệu';
    }
}

$data['page_title'] = 'Cài đặt';
$tree[] = url(_HOME . '/chatbox/manager', 'Chatbox manager');
$tree[] = url(_HOME . '/chatbox/manager/cpanel', 'Cài đặt');
$data['display_tree'] = display_tree_modulescp($tree);
$obj->view->data = $data;
$obj->view->show('chatbox/manager/cpanel');

