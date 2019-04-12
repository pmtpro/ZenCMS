<?php
/**
 * name = Module cpanel
 * icon = icon-hospital
 * position = 60
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

$data['list_extends_modules'] = get_extend_apps('admin/general/modulescp');

ZenView::set_title('Module Cpanel');
$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/general', 'Tổng quan');
$tree[] = url(HOME.'/admin/general/modulescp', 'Module Cpanel');
ZenView::set_breadcrumb($tree);

if (!empty($_GET['appFollow'])) {
    $a = new ZenRouter($registry);
    $a->setPath(__MODULES_PATH);
    $a->loader($_GET['appFollow']);
    exit;
}
$obj->view->data = $data;
$obj->view->show('admin/general/modulescp/index');
