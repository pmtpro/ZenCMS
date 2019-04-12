<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::block('Thông tin module', function() {
        ZenView::col(function() {
            ZenView::col_item(3, function() {
                echo '<a href="' . HOME . '/admin/general/modules" class="btn btn-default"><span class="fa fa-long-arrow-left"></span> Trở lại</a>';
            });
            ZenView::col_item(9, function() {
                echo '<table class="table table-bordered table-striped">
                <tr><td>Tên</td><td>' . ZenView::$D['module']['name'] . '</td></tr>
                <tr><td>Package ID</td><td>' . ZenView::$D['module']['package'] . '</td></tr>
                <tr><td>Phiên bản</td><td>' . ZenView::$D['module']['version'] . '</td></tr>
                <tr><td>Tác giả</td><td>' . ZenView::$D['module']['author'] . '</td></tr>
                <tr><td>Mô tả</td><td>' . ZenView::$D['module']['des'] . '</td></tr>
                </table>';
            });
        });
    });
});