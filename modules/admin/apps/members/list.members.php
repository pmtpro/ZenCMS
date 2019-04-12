<?php
/**
 * name = Danh sách thành viên
 * icon = admin_members_list
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

$model = $obj->model->get('admin');
$security = load_library('security');
$p = load_library('pagination');

$filter = '';

if (isset($_GET['filter'])) {

    $filter = $security->cleanXSS($_GET['filter']);
}

$data['filter'] = $filter;

$list_perm = sys_config('user_perm');

$data['permissions'] = $list_perm;

$limit = 10;
$p->setLimit($limit);
$p->SetGetPage('page');
$start = $p->getStart();
$sql_limit = $start.','.$limit;

$data['users'] = $model->get_users($filter, $sql_limit);

$total = $model->total_result;

$p->setTotal($total);
$data['users_pagination'] = $p->navi_page();

$data['page_title'] = 'Danh sách thành viên';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/members', 'Thành viên');
$tree[] = url(_HOME.'/admin/members/list', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/members/list');
?>