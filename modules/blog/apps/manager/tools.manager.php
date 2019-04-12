<?php
/**
 * name = Tool
 * icon = fa fa-wrench
 * position = 180
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
if (!defined('__ZEN_KEY_ACCESS'))
    exit('No direct script access allowed');

$data['list_extends_modules'] = get_extend_apps('blog/manager/tools');

$page_title = 'Blog tool';
ZenView::set_title($page_title);
$tree[] = url(genUrlAppFollow('blog/manager'), 'Quản lí blog');
ZenView::set_breadcrumb($tree);

/**
 @noinspection PhpParamsInspection */
$data['menus'] = get_extend_apps('blog/manager/tools');

if (empty($data['menus'])) {
    ZenView::set_notice('Hiện tại chưa có ứng dụng nào');
}
$obj->view->data = $data;
$obj->view->show('blog/manager/tools');
