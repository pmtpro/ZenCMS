<?php
ZenView::section(ZenView::get_title(true), function() {
   ZenView::display_breadcrumb();
    ZenView::block('Thông tin phiên bản', function() {
        ZenView::display_message();
        ZenView::col(function() {
            ZenView::col_item(6, function() {
                echo '<table class="table table-bordered table-striped">
                <tr>
                <thead><th>Phiên bản cũ</th></thead>
                </tr>
                <tr>
                <td>Tên</td>
                <td>' . ZenView::$D['old_version']['name'] . '</td>
                </tr>
                <tr>
                <td>Tác giả</td>
                <td>' . ZenView::$D['old_version']['author'] . '</td>
                </tr>
                <tr>
                <td class="success">Phiên bản</td>
                <td class="success">' . ZenView::$D['old_version']['version'] . '</td>
                </tr>
                <tr>
                <td>Mô tả</td>
                <td>' . ZenView::$D['old_version']['des'] . '</td>
                </tr>
                </table>';
            });
            ZenView::col_item(6, function() {
                echo '<table class="table table-bordered table-striped">
                <tr>
                <thead><th>Phiên bản mới</th></thead>
                </tr>
                <tr>
                <td>Tên</td>
                <td>' . ZenView::$D['new_version']->name . '</td>
                </tr>
                <tr>
                <td>Tác giả</td>
                <td>' . ZenView::$D['new_version']->author . '</td>
                </tr>
                <tr>
                <td class="success">Phiên bản</td>
                <td class="success">' . ZenView::$D['new_version']->version . '</td>
                </tr>
                <tr>
                <td>Mô tả</td>
                <td>' . ZenView::$D['new_version']->des . '</td>
                </tr>
                </table>';
            });
        });
        echo '<form method="POST">
        <div class="text-center">
        <button type="submit" name="submit-update" class="btn ' . (ZenView::$D['new_version']->downloadable ? 'btn-primary' : 'btn-warning') . '">' . (ZenView::$D['new_version']->downloadable ? '<span class="fa fa-level-up"></span> Câp nhật' : '<span class="fa fa-shopping-cart"></span> ' . number_format(ZenView::$D['new_version']->amount) . ZenView::$D['new_version']->currency) . '</button>
        <input type="submit" name="submit-cancel" value="Hủy bỏ" class="btn btn-default"/>
        </div>
        </form>';
    });
});