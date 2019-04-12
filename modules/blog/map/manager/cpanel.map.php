<?php
$functionDataTable = function ($list, $pos) {
    echo '<div class="table-responsive"><table class="table table-bordered">';
    echo '<thead>
          <tr>
            <th style="width: 35px"></th>
            <th><div>Tên</div></th>
            <th><div>Thư mục chứa</div></th>
            <th><div>Người viết</div></th>
            <th><div>Ngày viết</div></th>
            <th><div>Trạng thái</div></th>
          </tr>
          </thead>';
    foreach ($list as $item) {
        if ($item['status'] == 1) {
            $attr = ' class="warning"';
        } elseif ($item['status'] == 2) {
            $attr = ' class="danger"';
        } else $attr = '';
        echo '<tr' . $attr . '>';
        $navBar = '<div class="btn-group">
                <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-cog"></i>
                </button>
                <ul class="dropdown-menu">';
        foreach ($item['actions'] as $act) {
            if (!empty($act['divider'])) $navBar .= '<li class="divider"></li>';
            $navBar .= '<li><a href="' . $act['full_url'] . '" title="' . $act['title'] . '" class="requestAct2" data-id="' . $act['id'] . '" data-url="' . $act['full_url'] . '" ' . (!empty($act['attr']) ? $act['attr'] : '') . '>
                    <i class="' . $act['icon'] . '"></i>' . $act['name'] . '</a></li>';
        }

        $navBar .= '</ul></div>';
        echo('<td>' . $navBar . '</td>
        <td>' . (!empty($item['name']) ? '<a href="' . $item['full_url'] . '" title="' . $item['title'] . '">' . $item['name'] . '</a>' : '<a href="' . $item['full_url'] . '" title="' . $item['title'] . '"><i class="smaller">(Chưa có tiêu đề)</i></a>') . '</td>
        <td>' . (!empty($item['cat']) ? '<a href="' . $item['cat']['full_url'] . '" title="' . $item['cat']['title'] . '">' . $item['cat']['name'] . '</a>' : '<i class="smaller">Trang chủ</i>') . '</td>
        <td>' . (!empty($item['user']) ? '<a href="' . HOME . '/admin/members/editor?id=' . $item['uid'] . '">' . $item['user']['nickname'] . '</a>' : '<i class="smaller">N/A</i>') . '</td>
        <td>' . $item['display_time'] . '</td>
        <td><a href="' . $item['status_detail']['full_url'] . '" title="' . $item['status_detail']['name'] . '">' . $item['status_detail']['show'] . '</a></td>');
        echo '</tr>';
    }
    echo '</table></div>';
    echo '<div class="table-footer"><div class="dataTables_paginate">';
    ZenView::display_paging($pos);
    echo '</div></div>';
};

$menu = '<div class="btn-group">';
$actList = ZenView::get_menu('section-action');
foreach ($actList['menu'] as $act) {
    $menu .= '<a href="' . $act['full_url'] . '" class="btn btn-default"><i class="' . $act['icon'] . '"></i></a>';
}
$menu .= '</div>';

ZenView::section(ZenView::get_title(true), function () use ($functionDataTable) {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    $block_title_post = 'Không lọc';
    $filter_post_menu_data = ZenView::get_menu('filter-post', true);
    $filter_post_menu = '<div class="btn-group"><a href="#" class="dropdown btn btn-default btn-xs" data-toggle="dropdown"><i class="fa fa-filter"></i></a><ul class="dropdown-menu pull-right">';
    foreach ($filter_post_menu_data['menu'] as $m) {
        if (!empty($m['divider'])) $filter_post_menu .= '<li class="divider"></li>';
        if ($m['active']) {
            $block_title_post = $m['name'];
        }
        $filter_post_menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" ' . ($m['active'] ? 'class="status-success"' : '') . '><i class="' . $m['icon'] . '"></i>' . $m['name'] . '</a></li>';
    }
    $filter_post_menu .= '</ul></div>';
    $after_block_post = '<span class="btn btn-default btn-xs">' . ZenView::$D['stats']['count']['post'] . ' bài</span>
    <a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blogID'] . '&act=new" class="btn btn-default btn-xs"><i class="fa fa-plus"></i> Viết bài</a>
    ' . $filter_post_menu . '
    </ul>';
    $block_title_cat = 'Không lọc';
    $filter_cat_menu_data = ZenView::get_menu('filter-cat', true);
    $filter_cat_menu = '<div class="btn-group"><a href="#" class="dropdown btn btn-default btn-xs" data-toggle="dropdown"><i class="fa fa-filter"></i></a>
    <ul class="dropdown-menu pull-right">';
    foreach ($filter_cat_menu_data['menu'] as $m) {
        if (!empty($m['divider'])) $filter_cat_menu .= '<li class="divider"></li>';
        if ($m['active']) {
            $block_title_cat = $m['name'];
        }
        $filter_cat_menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" ' . ($m['active'] ? 'class="status-success"' : '') . '><i class="' . $m['icon'] . '"></i>' . $m['name'] . '</a></li>';
    }
    $filter_cat_menu .= '</ul></div>';
    $after_block_cat = '<span class="btn btn-default btn-xs">' . ZenView::$D['stats']['count']['cat'] . ' mục</span>
    <a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blogID'] . '&act=new&type=folder" class="btn btn-default btn-xs"><i class="fa fa-plus"></i> Tạo thư mục</a>
    ' . $filter_cat_menu;
    ZenView::block('<span class="label label-warning">Bài viết trong</span> <a href="' . ZenView::$D['blog']['full_url'] . '" target="_blank">' . ZenView::$D['blog']['name'] . '</a> <span class="badge badge-primary">' . $block_title_post . '</span>', function () use ($functionDataTable) {
        if (ZenView::message_exists('post')) {
            ZenView::padded(function () {
                ZenView::display_message('post');
            });
        }
        if (!empty(ZenView::$D['posts']))
            $functionDataTable(ZenView::$D['posts'], 'post');
    }, array(
        'after' => $after_block_post
    ));
    ZenView::block('<span class="label label-warning">Thư mục trong</span> <a href="' . ZenView::$D['blog']['full_url'] . '" target="_blank">' . ZenView::$D['blog']['name'] . '</a> <span class="badge badge-primary">(' . $block_title_cat . ')</span>', function () use ($functionDataTable) {
        if (ZenView::message_exists('cat')) {
            ZenView::padded(function () {
                ZenView::display_message('cat');
            });
        }
        if (!empty(ZenView::$D['cats']))
            $functionDataTable(ZenView::$D['cats'], 'cat');
    }, array(
        'after' => $after_block_cat
    ));
}, array(
    'after' => $menu
));