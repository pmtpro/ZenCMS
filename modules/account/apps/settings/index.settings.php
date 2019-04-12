<?php
/**
 * name = Cài đặt chung
 * icon = glyphicon glyphicon-cog
 * position = 10099
 */
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
$security = load_library('security');
$model = $obj->model->get('account');
if (isset($_POST['submit-save'])) {
    $update['allow_wall_comment'] = (int) $security->removeSQLI($_POST['allow_wall_comment']);
    $update['allow_view_wall_comment'] = (int) $security->removeSQLI($_POST['allow_view_wall_comment']);
    if ($model->update_user_setting($obj->user['id'], $update)) {
        ZenView::set_success(1);
    } else {
        ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại');
    }
}

$data['user_set'] = $model->get_user_setting($obj->user['id']);

ZenView::set_title('Cài đặt tài khoản');
$data['user'] = $user;
ZenView::set_breadcrumb(url(HOME.'/account', 'Tài khoản'));
$obj->view->data = $data;
$obj->view->show('account/settings/index');