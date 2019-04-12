<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::col(function() {
        ZenView::col_item(8, function() {
            echo '<div class="thumbnail">
              ' . (ZenView::$D['info']['screenshot'] ? '<img src="' . ZenView::$D['info']['screenshot'] . '">' : '') . '
              <div class="caption">
                <h2 class="text-center">' . ZenView::$D['info']['name'] . '</h2>
                <table class="table table-bordered">
                <tbody><tr>
                    <td>Package ID</td>
                    <td>' . ZenView::$D['info']['package'] . '</td>
                </tr>
                <tr>
                    <td>Tên</td>
                    <td>' . ZenView::$D['info']['name'] . '</td>
                </tr>
                <tr>
                    <td>Phiên bản</td>
                    <td>' . ZenView::$D['info']['version'] . '</td>
                </tr>
                <tr>
                    <td>Tác giả</td>
                    <td>' . ZenView::$D['info']['author'] . '</td>
                </tr>
                <tr>
                    <td>Mô tả</td>
                    <td>' . ZenView::$D['info']['des'] . '</td>
                </tr>
                </tbody></table>
              </div>
            </div>';
        });
        ZenView::col_item(4, function() {
            ZenView::block('Tính năng', function() {
                echo '<div class="list-group">';
                foreach (ZenView::$D['info']['actions'] as $opt) {
                    echo '<div class="list-group-item"><a href="' . $opt['full_url'] . '"><span class="' . $opt['icon'] . '"></span> ' . $opt['name'] . '</a></div>';
                }
                echo '</div>';
            });
        });
    });
}, array('after' => $menu));