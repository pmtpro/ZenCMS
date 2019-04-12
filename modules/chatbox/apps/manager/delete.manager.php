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

/**
 * get chatbox model
 */
$model = $obj->model->get('chatbox');

/**
 * load security library
 */
$security = load_library('security');
$permission = load_library('permission');
$permission->set_user($obj->user);

/**
 * load user data
 */
$user = $obj->user;

$cid = 0;

if (isset($app[1])) {

    $cid = $app[1];
}

$chat = $model->get_chat_data($cid);

$data['chat'] = $chat;

if (empty($chat)) {

    go_back();
    return;
}

if (isset($_POST['sub_delete'])) {

    if ($permission->is_lower_levels_of($chat['uid'])) {

        $data['errors'] = 'Bạn không có quyền xóa bài của cấp trên';

    } else {

        if (!$model->delete($cid)) {

            $data['errors'] = 'Lỗi dữ liệu';
        } else {

            redirect(_HOME . '/chatbox');
        }
    }

}

$data['page_title'] = 'Xóa bình luận';
$tree[] = url(_HOME . '/chatbox/manager', 'Chatbox manager');
$tree[] = url(_HOME . '/chatbox/manager/delete', $data['page_title']);
$data['display_tree'] = display_tree_modulescp($tree);
$obj->view->data = $data;
$obj->view->show('chatbox/manager/delete');