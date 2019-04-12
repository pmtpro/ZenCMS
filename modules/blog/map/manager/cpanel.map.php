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
    echo '<table class="dTable-disPaginate-hiddenTableSearch ZenPostTable">';
    echo('<thead>
          <tr>
            <th style="width: 35px"></th>
            <th><div>Tên</div></th>
            <th><div>Thư mục</div></th>
            <th><div>Người viết</div></th>
            <th><div>Ngày viết</div></th>
            <th><div>Trạng thái</div></th>
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
        <td>' . (!empty($item['name'])?'<a href="' . $item['full_url'] . '" title="' . $item['title'] . '">' . $item['name'] . '</a>':'<a href="' . $item['full_url'] . '" title="' . $item['title'] . '"><i class="smaller">(Chưa có tiêu đề)</i></a>') . '</td>
        <td>' . (!empty($item['cat'])?'<a href="' . $item['cat']['full_url'] . '" title="' . $item['cat']['title'] . '">' . $item['cat']['name'] . '</a>':'<i class="smaller">Trang chủ</i>') . '</td>
        <td>' . (!empty($item['user'])?'<a href="' . HOME . '/admin/members/editor?id=' . $item['uid'] . '">' . $item['user']['nickname'] . '</a>':'<i class="smaller">N/A</i>') . '</td>
        <td>' . $item['display_time'] . '</td>
        <td><a href="' . $item['status_detail']['full_url'] . '" title="' . $item['status_detail']['name'] . '">' . $item['status_detail']['show'] . '</a></td>');
        echo '</tr>';
    }
    echo '</table>';
    echo '<div class="table-footer"><div class="dataTables_paginate">';
    ZenView::display_paging($pos);
    echo '</div></div>';
};

$menu = '<div class="btn-group">';
$actList = ZenView::get_menu('section-action');
foreach($actList['menu'] as $act) {
    $menu .= '<a href="' . $act['full_url'] . '" class="btn btn-default"><i class="' . $act['icon'] . '"></i></a>';
}
$menu .= '</div>';

ZenView::section(ZenView::get_title(true), function() use ($functionDataTable, $block_title_post) {
    ZenView::display_breadcrumb();
    ZenView::display_message();

    $block_title_post = 'Không lọc';
    $filter_post_menu_data = ZenView::get_menu('filter-post', true);
    $filter_post_menu = '<a href="#" class="dropdown" data-toggle="dropdown"><i class="icon-filter"></i></a>
    <ul class="dropdown-menu">';
    foreach ($filter_post_menu_data['menu'] as $m) {
        if ($m['active']) {
            $block_title_post = $m['name'];
        }
        $filter_post_menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" ' . ($m['active'] ? 'class="status-success"': '') . '><i class="' . $m['icon'] . '"></i>' . $m['name'] . '</a></li>';
    }
    $filter_post_menu .= '</ul>';
    $after_block_post = '<ul class="box-toolbar">
    <li><span class="label label-green">' . ZenView::$D['stats']['count']['post'] . ' bài</span></li>
    <li class="toolbar-link">
        <a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blogID'] . '&act=new"><i class="icon-plus"></i> Viết bài</a>
    </li>
    <li class="toolbar-link">' . $filter_post_menu . '</li>
    </ul>';

    $block_title_cat = 'Không lọc';
    $filter_cat_menu_data = ZenView::get_menu('filter-cat', true);
    $filter_cat_menu = '<a href="#" class="dropdown" data-toggle="dropdown"><i class="icon-filter"></i></a>
    <ul class="dropdown-menu">';
    foreach ($filter_cat_menu_data['menu'] as $m) {
        if ($m['active']) {
            $block_title_cat = $m['name'];
        }
        $filter_cat_menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" ' . ($m['active'] ? 'class="status-success"': '') . '><i class="' . $m['icon'] . '"></i>' . $m['name'] . '</a></li>';
    }
    $filter_cat_menu .= '</ul>';
    $after_block_cat = '<ul class="box-toolbar">
    <li><span class="label label-green">' . ZenView::$D['stats']['count']['cat'] . ' mục</span></li>
    <li class="toolbar-link">
        <a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blogID'] . '&act=new&type=folder"><i class="icon-plus"></i> Tạo thư mục</a>
    </li>
    <li class="toolbar-link">' . $filter_cat_menu . '</li>
    </ul>';

    ZenView::block('<span class="label label-blue">Bài viết trong</span> <a href="' . ZenView::$D['blog']['full_url'] . '" target="_blank">' . ZenView::$D['blog']['name'] . '</a> <i class="smaller status-success">(' . $block_title_post . ')</i>', function() use ($functionDataTable) {
        if (ZenView::message_exists('post')) {
            ZenView::padded(function() {
                ZenView::display_message('post');
            });
        }
        if (!empty(ZenView::$D['posts'])) $functionDataTable(ZenView::$D['posts'], 'post');
    }, array('after' => $after_block_post));

    ZenView::block('<span class="label label-blue">Thư mục trong</span> <a href="' . ZenView::$D['blog']['full_url'] . '" target="_blank">' . ZenView::$D['blog']['name'] . '</a> <i class="smaller status-success">(' . $block_title_cat . ')</i>', function() use ($functionDataTable) {
        if (ZenView::message_exists('cat')) {
            ZenView::padded(function() {
                ZenView::display_message('cat');
            });
        }
        if (!empty(ZenView::$D['cats'])) $functionDataTable(ZenView::$D['cats'], 'cat');
    }, array('after' => $after_block_cat));
}, array('after' => $menu));