<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    if (!ZenView::$D['install_process']) {
        ZenView::col(function() {
            ZenView::col_item(8, function() {
                ZenView::block('Chọn template', function() {
                    ZenView::display_message();
                    ZenView::display_message('template-accept-format');
                    echo '<form role="form" class="form-horizontal" method="POST" enctype="multipart/form-data">';
                    echo'<div class="form-group">
                    <label for="upload-template" class="col-lg-2 control-label">Chọn tệp tin</label>
                    <div class="col-lg-9">
                    <input type="file" name="template" id="upload-template" accept="" class="form-control"/>
                    </div>
                    </div>';
                    echo '<div class="form-group"><div class="col-lg-9 col-lg-offset-2">
                      <button type="submit" name="submit-upload" class="btn btn-primary">Cài đặt</button>
                    </div></div>';
                    echo '</form>';
                });
            });
            ZenView::col_item(4, function() {
                ZenView::block('Chú ý', function() {
                    ZenView::display_message('install-notice');
                });
            });
        });
    } else {
        ZenView::col(function() {
            ZenView::col_item(ZenView::$D['template_existed'] ? 6 : 6, function() {
                ZenView::block('Kiểm tra cài đặt template', function() {
                    ZenView::display_message();
                    ZenView::col(function() {
                        if (ZenView::$D['template_existed']) ZenView::col_item(6, function() {
                            echo '<table class="table table-bordered">';
                            echo '<thead><tr><th></th><th>Template cũ</th></tr></thead>';
                            echo '<tr><td>Tên</td><td>' . ZenView::$D['template_existed_info']['name'] . '</td></tr>';
                            echo '<tr><td>Tác giả</td><td>' . ZenView::$D['template_existed_info']['author'] . '</td></tr>';
                            echo '<tr ' . (ZenView::$D['updatable'] === 1 ? 'class="success"' : (ZenView::$D['updatable'] === -1 ? 'class="warning"' : '')) . '><td>Phiên bản</td><td>' . ZenView::$D['template_existed_info']['version'] . '</td></tr>';
                            echo '<tr><td>Mô tả</td><td>' . ZenView::$D['template_existed_info']['des'] . '</td></tr>';
                            echo '<tr><td>Kích thước tệp tin</td><td>N/A</td></tr>';
                            echo '</table>';
                        });
                        ZenView::col_item(ZenView::$D['template_existed'] ? 6 : 12, function() {
                            echo '<table class="table table-bordered">';
                            echo '<thead><tr><th></th><th>Template mới</th></tr></thead>';
                            echo '<tr><td>Tên</td><td>' . ZenView::$D['template_info']['name'] . '</td></tr>';
                            echo '<tr><td>Tác giả</td><td>' . ZenView::$D['template_info']['author'] . '</td></tr>';
                            echo '<tr ' . (ZenView::$D['updatable'] === 1 ? 'class="success"' : (ZenView::$D['updatable'] === -1 ? 'class="warning"' : '')) . '><td>Phiên bản</td><td>' . ZenView::$D['template_info']['version'] . '</td></tr>';
                            echo '<tr><td>Mô tả</td><td>' . ZenView::$D['template_info']['des'] . '</td></tr>';
                            echo '<tr><td>Kích thước tệp tin</td><td>' . ZenView::$D['template_info']['file_size'] . '</td></tr>';
                            echo '</table>';
                        });
                    });

                    echo '<form method="POST" class="form-horizontal">';
                    if (!empty(ZenView::$D['folder_install_already_exists'])) {
                        echo '<div class="form-group">
                    <div class="col-lg-12">
                    <label><input type="radio" name="option-install" value="overwrite" checked/> Ghi đè cài đặt vào thư mục đã tồn tại</label><br/>
                    <label><input type="radio" name="option-install" value="remove"/> Xóa thư mục đã tồn tại và cài đặt</label>
                    </div></div>';
                    }
                    echo '<div class="form-group">
                <div class="col-lg-12 text-center">';
                    echo '<input type="submit" name="submit-confirm-install" class="btn btn-primary btn-lg" value="' . ((ZenView::$D['updatable'] == 1 && ZenView::$D['update_info']) ? 'Cập nhật template' : 'Cài đặt') . '"/> ';
                    echo '<input type="submit" name="submit-confirm-not-install" class="btn btn-default btn-lg" value="Hủy bỏ"/>';
                    echo '</div></div>';
                    echo '</form>';
                });
            });
            ZenView::col_item(ZenView::$D['template_existed'] ? 6 : 6, function() {
                ZenView::block('Thông tin thêm', function() {
                    ZenView::display_message('more-info');
                    if (ZenView::$D['template_struct']) {
                        echo '<table class="table table-bordered table-striped">';
                        echo '<thead><tr><th>Cấu trúc template vừa tải lên</th></tr></thead>';
                        foreach (ZenView::$D['template_struct'] as $item) {
                            echo '<tr><td>' . $item . '</td></tr>';
                        }
                        echo '</table>';
                    }
                    if (ZenView::$D['updatable'] == 1) {
                        if (ZenView::$D['update_info']) {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-bordered table-striped">';
                            echo '<thead><tr><th>Thông tin update</th><th>Trạng thái</th></tr></thead>';
                            foreach (ZenView::$D['update_info'] as $file => $item) {
                                echo '<tr><td>' . $file . '</td><td>' . $item . '</td></tr>';
                            }
                            echo '</table>';
                            echo '</div>';
                        }
                    }
                });
            });
        });
    }
}, array('after' => $menu));