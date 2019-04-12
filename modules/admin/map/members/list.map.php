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
$functionDataTable = function($list, $pos) {
    echo '<table class="dTable-disPaginate-hiddenTableSearch">';
    echo('<thead>
          <tr>
            <th style="width: 35px"></th>
            <th><div>Username</div></th>
            <th><div>Nickname</div></th>
            <th><div>Email</div></th>
            <th><div>Ngày đăng kí</div></th>
            <th><div>Quyền</div></th>
          </tr>
          </thead>');
    foreach ($list as $item) {
        echo '<tr>';
        $navBar = '<div class="btn-group">
                <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-cog"></i>
                </button>
                <ul class="dropdown-menu">';
        foreach($item['actions'] as $act) {
            if (!empty($act['divider'])) $navBar .= '<li class="divider"></li>';
            $navBar .= '<li><a href="' . $act['full_url'] . '" title="' . $act['title'] . '" class="requestAct2" data-id="' . $act['id'] . '" data-url="' . $act['full_url'] . '" ' . (!empty($act['attr'])?$act['attr']:'') . '>
                    <i class="' . $act['icon'] . '"></i>' . $act['name'] . '</a></li>';
        }
        $navBar .= '</ul></div>';
        echo('<td>' . $navBar . '</td>
        <td><a href="' . $item['full_url'] . '">' . $item['username'] . '</a></td>
        <td><a href="' . $item['full_url'] . '">' . $item['nickname'] . '</a></td>
        <td>' . ($item['email']?$item['email']:'N/A') . '</td>
        <td>' . $item['display_time_reg'] . '</td>
        <td><a href="' . $item['perm_detail']['full_url'] . '">' . $item['perm_detail']['display'] . '</a></td>');
        echo '</tr>';
    }
    echo '</table>';
    echo '<div class="table-footer"><div class="dataTables_paginate">';
    ZenView::display_paging($pos);
    echo '</div></div>';
};
ZenView::section('Quản lí thành viên', function() use ($functionDataTable) {
    ZenView::display_breadcrumb();
    $blockMenu = '<ul class="box-toolbar">
    <li class="toolbar-link">
    <a href="#" data-toggle="dropdown"><i class="icon-search"></i></a>
    <ul class="dropdown-menu">
    <li><a href="' . HOME . '/admin/members/list">Tất cả</a></li>';
    foreach (ZenView::$D['permissions']['name'] as $perm => $name) {
        $blockMenu .= '<li><a href="' . HOME . '/admin/members/list?filter=' . $perm . '">' . $name . '</a></li>';
    }
    $blockMenu .= '</ul>
    </li>
    </ul>';
    ZenView::block('Danh sách thành viên', function() use ($functionDataTable) {
        if (ZenView::message_exists()) {
            ZenView::padded(function() {
                ZenView::display_message();
            });
        }
        $functionDataTable(ZenView::$D['users'], 'list-user');
    }, array('after' => $blockMenu));
});