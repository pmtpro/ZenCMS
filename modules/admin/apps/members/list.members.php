<?php
/**
 * name = Danh sách
 * icon = icon-list-alt
 * position = 1
 */
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

/**
 * get model
 */
$model = $obj->model->get('account');

/**
 * load library
 */
$security = load_library('security');
$p = load_library('pagination');

$baseUrl = HOME . '/admin/members';

$filter = '';
if (isset($_GET['filter'])) {
    $filter = $security->cleanXSS($_GET['filter']);
}
$data['filter'] = $filter;
$list_perm = sysConfig('user_perm');
$data['permissions'] = $list_perm;

$limit = 10;
$p->setLimit($limit);
$p->SetGetPage('page');
$start = $p->getStart();
$sql_limit = $start.','.$limit;
$data['users'] = $model->get_user_by_perm($filter, $sql_limit);
if (empty($data['users'])) {
    ZenView::set_notice('Không có thành viên nào trong danh sách này');
} else {
    $user_perm_config = sysConfig('user_perm');
    foreach($data['users'] as $kid=>$user) {
        $data['users'][$kid]['full_real_url'] = $user['full_url'];
        $data['users'][$kid]['full_url'] = $baseUrl . '/editor?id=' . $user['id'] . $param_cat_stt;
        $data['users'][$kid]['perm_detail']['name'] = $zen['config']['user_perm']['name'][$data['perm']];
        $data['users'][$kid]['perm_detail']['display'] = '<i class="smaller" style="color: ' . $user_perm_config['color'][$user['perm']] . '">' . $user_perm_config['name'][$user['perm']] . '</i>';
        $data['users'][$kid]['perm_detail']['full_url'] = HOME. '/admin/members/list?filter=' . $user['perm'];
        $data['users'][$kid]['actions']['edit'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/editor&id=' . $user['id'],
            'name' => 'Chỉnh sửa',
            'icon' => 'icon-pencil'
        ));
        $data['users'][$kid]['actions']['view-profile'] = ZenView::gen_menu(array(
            'full_url' => $data['users'][$kid]['full_real_url'],
            'name' => 'Xem trang cá nhân',
            'icon' => 'icon-eye-open',
            'attr' => 'target="_blank"'
        ));
    }
}
$total = $model->total_result;
$p->setTotal($total);
ZenView::set_paging($p->navi_page(), 'list-user');

$page_title = 'Danh sách thành viên';
ZenView::set_title($page_title);
$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url($baseUrl, 'Thành viên');
$tree[] = url($baseUrl . '/list', $page_title);
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('admin/members/list');