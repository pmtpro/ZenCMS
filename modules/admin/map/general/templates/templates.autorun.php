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
$pageMenu = ZenView::get_menu('page_menu', true);
/**
 * menu_templates_controls hook*
 */
$pageMenu['menu'] = hook('admin', 'menu_templates_controls', $pageMenu['menu']);

$menu = '<div class="btn-group">
  <button type="button" class="btn btn-primary" data-toggle="dropdown">
    <i class="fa fa-wrench"></i> Quản lí
  </button>
  <button class="btn btn-primary" data-toggle="dropdown"><span class="caret"></span></button>
  <ul class="dropdown-menu pull-right" role="menu">';
foreach ($pageMenu['menu'] as $m):
    $menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '"><i class="' . $m['icon'] . '"></i>' . $m['name'] . '</a></li>';
endforeach;
$menu .= '</ul></div>';