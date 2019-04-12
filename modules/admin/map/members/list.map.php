<?php
$functionDataTable = function($list, $pos) {
    echo '<div class="table-responsive"><table class="table table-bordered">';
    echo '<thead>
          <tr>
            <th style="width: 35px"></th>
            <th><div>Username</div></th>
            <th><div>Nickname</div></th>
            <th><div>Email</div></th>
            <th><div>Ngày đăng kí</div></th>
            <th><div>Quyền</div></th>
          </tr>
          </thead>';
    foreach ($list as $item) {
        echo '<tr>';
        $navBar = '<div class="btn-group">
                <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-cog"></i>
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
    echo '</table></div>';
    ZenView::display_paging($pos);
};
ZenView::section('Quản lí thành viên', function() use ($functionDataTable) {
    ZenView::display_breadcrumb();
    $blockMenu = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-search"></i></a>
    <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="' . HOME . '/admin/members/list">Tất cả</a></li>';
    foreach (ZenView::$D['permissions']['name'] as $perm => $name) {
        $blockMenu .= '<li><a href="' . HOME . '/admin/members/list?filter=' . $perm . '">' . $name . '</a></li>';
    }
    $blockMenu .= '</ul></div>';
    ZenView::block('Danh sách thành viên', function() use ($functionDataTable) {
        if (ZenView::message_exists()) {
            ZenView::padded(function() {
                ZenView::display_message();
            });
        }
        $functionDataTable(ZenView::$D['users'], 'list-user');
    }, array('after' => $blockMenu));
});