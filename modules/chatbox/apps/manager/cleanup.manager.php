<?php
/**
 * name = Dọn dẹp
 * icon = chatbox_manager_cleanup
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

/**
 * get chatbox model
 */
$model = $obj->model->get('chatbox');

if (isset($_POST['sub_cleanup'])) {

    if (!empty($_POST['group']) && is_array($_POST['group'])) {

        foreach ($_POST['group'] as $group) {

            $model->cleanup($group);
        }
        $data['success'] = 'Thành công';
    }
}

$data['page_title'] = 'Dọn dẹp';
$tree[] = url(_HOME . '/chatbox/manager', 'Chatbox manager');
$tree[] = url(_HOME . '/chatbox/manager/cleanup', $data['page_title']);
$data['display_tree'] = display_tree_modulescp($tree);
$obj->view->data = $data;
$obj->view->show('chatbox/manager/cleanup');

