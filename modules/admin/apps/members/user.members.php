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

$data['page_title'] = 'Quản lí thành viên';

$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/members', 'Thành viên');
$tree[] = url(_HOME.'/admin/members/list', 'Danh sách thành viên');
$data['display_tree'] = display_tree($tree);

load_helper('time');
$model = $obj->model->get('admin');
$security = load_library('security');
$p = load_library('pagination');
$uname = '';
if (isset($app[1])) {

    $uname = $security->cleanXSS($app[1]);

}
$user = $model->get_user_data($uname);

if (empty($user)) {
    $obj->view->data = $data;
    $obj->view->show('admin/members/_user_not_found');
    return;
}

$data['user'] = $user;
$obj->view->data = $data;
$obj->view->show('admin/members/_user');
?>