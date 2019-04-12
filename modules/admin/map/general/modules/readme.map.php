<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::block('Thông tin file readme', function() {
        ZenView::col(function() {
            ZenView::col_item(3, function() {
                echo '<a href="' . HOME . '/admin/general/modules" class="btn btn-default"><span class="fa fa-long-arrow-left"></span> Trở lại</a>';
            });
            ZenView::col_item(9, function() {
                echo ZenView::$D['module']['readme'];
            });
        });
    });
});