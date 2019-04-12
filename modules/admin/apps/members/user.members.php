<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$data['page_title'] = 'Quản lí thành viên';

$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/members', 'Thành viên');
$tree[] = url(HOME.'/admin/members/list', 'Danh sách thành viên');
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