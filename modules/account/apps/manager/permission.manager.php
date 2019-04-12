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

$data['page_title'] = 'Quản lí chức vụ';
/**
 * get account model
 */
$model = $obj->model->get('account');
/**
 * load user data
 */
$data['user'] = $obj->user;
/**
 * load libraries
 */
$security = load_library('security');
$pe = load_library('permission');
$pe->set_user($data['user']);

$uname = '';

if (isset($app[1])) {

    $uname = $security->cleanXSS($app[1]);
}

/**
 * get user data from username
 */
$member = $model->get_user_data($uname);

$tree[] = url(_HOME.'/account/wall/'.$member['username'], $member['nickname']);
$tree[] = url(_HOME.'/account/manager/permission/'.$member['username'], 'Quản lí chức vụ');
$data['display_tree'] = display_tree($tree);

if (empty($member)) {

    $obj->view->data = $data;
    $obj->view->show('account/manager/user_not_found');
    return;
}

if ($pe->is_lower_levels_of($member['id'])) {

    $obj->view->data = $data;
    $obj->view->show('account/manager/not_allowed_change');
    return;
}

if ($uname == $data['user']['username']) {

    $data['notices'] = 'Bạn không thể tự chỉnh sửa mình';
    $obj->view->data = $data;
    $obj->view->show('account/manager/not_allowed_change');
    return;
}
$list_perm = sys_config('user_perm');

if (isset($list_perm['name']['guest'])) {

    unset($list_perm['name']['guest']);
}


if (isset($_POST['sub_change'])) {

    $perm = $_POST['perm'];

    if(!isset($list_perm['name'][$perm])) {

        $data['errors'][] = 'Không tồn tại chức vụ này';

    } else {

        $update['perm'] = $perm;

        if (!$model->update_user($member['id'], $update)) {

            $data['errors'][] = 'Lỗi dự liệu';

        } else {

            reload('Thành công');
        }

    }

}

$data['permissions'] = $list_perm;
$data['member'] = $member;
$obj->view->data = $data;
$obj->view->show('account/manager/permission');
?>