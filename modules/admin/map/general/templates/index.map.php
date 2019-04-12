<?php
ZenView::section('Danh sách giao diện', function () {
    ZenView::display_breadcrumb();
    ZenView::block('Giao diện đã cài đặt', function () {
        ZenView::padded(function () {
            ZenView::display_message();
            ZenView::display_message('template-number-temp');
            echo '<div class="row">';
            foreach (ZenView::$D['templates'] as $key => $temp) {
                echo'<div class="col-sm-6 col-md-4 zen-template-screenshot">
                    <div class="thumbnail">
                        <img src="' . $temp['screenshot'] . '" style="height: 200px;"/>
                        <div class="caption text-center">
                            <h3><a href="' . HOME . '/admin/general/templates/detail/' . $temp['package'] . '" title="Chi tiết">' . $temp['name'] . '</a></h3>
                            <p>' . $temp['des'] . '</p>
                            <div class="action">
                                <div class="btn-group text-left">
                                    <a href="' . HOME . '?_review_template=' . $key . '" target="_blank" class="btn btn-primary"><span class="fa fa-eye"></span> Xem trước</a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
                echo '<ul class="dropdown-menu">';
                foreach ($temp['actions'] as $opt) {
                    if (!empty($opt['divider'])) echo '<li class="divider"></li>';
                    echo '<li><a href="' . $opt['full_url'] . '"><span class="' . $opt['icon'] . '"></span> ' . $opt['name'] . '</a></li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        });
    });
}, array('after' => $menu));