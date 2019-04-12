<?php
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
$page_menu = ZenView::get_menu('page_menu', true);
if (!empty($page_menu['menu'])) {
    $menu = '<div class="btn-group">
      <button type="button" class="btn btn-blue" data-toggle="dropdown">
        <i class="icon-wrench"></i> Quản lí
      </button>
      <button class="btn btn-blue" data-toggle="dropdown"><span class="caret"></span></button>
      <ul class="dropdown-menu dropdown-menu-right" role="menu">';
    foreach ($page_menu['menu'] as $m):
        $menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" id="' . $m['id'] . '">' . (!empty($m['icon']) ? '<i class="' . $m['icon'] . '"></i>':'') . $m['name'] . '</a></li>';
    endforeach;
    $menu .= '</ul></div>';
}
