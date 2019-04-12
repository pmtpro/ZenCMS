<?php
ZenView::section('Đính kèm: ' . (ZenView::$D['blog']['name']?ZenView::$D['blog']['name']: 'Không tiêu đề'), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::col(function() {
        ZenView::col_item(4, function() {
            ZenView::block('Thêm link', function() {
                ZenView::padded(function() {
                    ZenView::display_message('link-editor');
                    echo '<form method="POST">';
                    echo '<div class="form-group">
                    <label>Tên link</label>
                    <input type="text" name="name" class="form-control" placeholder="Tên link" value="' . ZenView::$D['link']['name'] . '"/>
                    </div>';
                        echo '<div class="form-group">
                    <label>Đường dẫn link</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-link"></i></span>
                        <input type="text" name="link" class="form-control" placeholder="http://" value="' . ZenView::$D['link']['link'] . '"/>
                    </div>
                    </div>';
                    echo '<input type="submit" name="submit-link" value="Lưu thay đổi" class="btn btn-primary"/>';
                    if (!empty(ZenView::$D['link_editor_id'])) {
                        echo ' <input type="submit" name="submit-del-link" value="Xóa" class="btn btn-danger" ' . cfm('Bạn có chắc chắn muốn xóa link này?') . '/>';
                        echo '<input type="hidden" name="link_editor_id" value="' . ZenView::$D['link_editor_id'] . '"/>';
                    }
                    echo '</form>';
                });
            });
        });
        ZenView::col_item(8, function() {
            ZenView::block('Danh sách link', function() {
                if (ZenView::message_exists('link-list')) {
                    ZenView::padded(function() {
                        ZenView::display_message('link-list');
                    });
                }
                if (!empty(ZenView::$D['links'])) {
                echo '<div class="table-responsive"><table class="table table-bordered">';
                echo '<thead><tr>
                        <td style="width: 40px"></td>
                        <td>Tên</td>
                        <td>Link</td>
                    </tr></thead>';
                foreach (ZenView::$D['links'] as $link) {
                    echo '<tr>
                        <td><div class="btn-group">
                            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                              <li><a href="' . ZenView::$D['current_url'] . '&editLink=' . $link['id'] . '">Chỉnh sửa</a></li>
                              <li><a href="' . $link['real_link'] . '" target="_blank">Xem link</a></li>
                            </ul>
                        </div></td>
                        <td>' . $link['name'] . '</td>
                        <td>' . $link['real_link'] . '</td>
                        </tr>';
                }
                echo '</table></div>';
                }
            });
        });
    });
    ZenView::col(function() {
        ZenView::col_item(4, function() {
            ZenView::block('Thêm file', function() {
                ZenView::padded(function() {
                    ZenView::display_message('file-editor');
                    if (!empty(ZenView::$D['file_editor_id'])) {
                        echo '<form method="POST" class="form-horizontal">';
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label">Tên hiển thị file</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" placeholder="Tên hiển thị file" value="' . ZenView::$D['file']['name'] . '"/>
                        </div>
                        </div>';
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label">Tên file</label>
                        <div class="col-sm-8">
                            <input type="text" name="file_name" class="form-control" value="' . ZenView::$D['file']['file_name'] . '"/>
                        </div>
                        </div>';
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input type="submit" name="submit-file" value="Lưu thay đổi" class="btn btn-primary"/>
                            <input type="submit" name="submit-del-file" value="Xóa" class="btn btn-danger" ' . cfm('Bạn có chắc chắn muốn xóa file này?') . '/>
                        </div>
                        </div>';
                        echo '</form>';
                    }
                });
                ZenView::padded(function() {
                    echo '<form method="POST" enctype="multipart/form-data" class="form-horizontal">';
                    for ($i=0; $i<ZenView::$D['number_file_per_upload']; $i++) {
                        $num = $i+1;
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label">Tên hiển thị ' . $num . '</label>
                        <div class="col-sm-8">
                            <input type="text" name="name[]" placeholder="Tên hiển thị" class="form-control"/>
                        </div>
                        </div>';
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label">Tên file ' . $num . '</label>
                        <div class="col-sm-8">
                            <input type="text" name="file_name[]" placeholder="Tên file" class="form-control"/>
                        </div>
                        </div>';
                        echo '<div class="form-group">
                        <label class="col-sm-4 control-label">File ' . $num . '</label>
                        <div class="col-sm-8">
                            <input type="file" name="file[]" class="form-control"/>
                        </div>
                        </div>';
                    }
                    echo '<div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input type="submit" name="submit-upload" value="Upload" class="btn btn-primary"/>
                        </div>
                        </div>';
                    echo '</form>';
                });
            });
        });
        ZenView::col_item(8, function() {
            ZenView::block('Danh sách file', function() {
                if (ZenView::message_exists('file-list')) {
                    ZenView::padded(function() {
                        ZenView::display_message('file-list');
                    });
                }
                if (!empty(ZenView::$D['files'])) {
                    echo '<div class="table-responsive"><table class="table table-bordered">';
                    echo '<thead><tr>
                        <td style="width: 40px"></td>
                        <td>Tên</td>
                        <td>Link</td>
                        <td>Size</td>
                    </tr></thead>';
                    foreach (ZenView::$D['files'] as $file) {
                        echo '<tr>
                        <td><div class="btn-group">
                            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                              <li><a href="' . ZenView::$D['current_url'] . '&editFile=' . $file['id'] . '">Chỉnh sửa</a></li>
                              <li><a href="' . $file['full_url'] . '" target="_blank">Xem file</a></li>
                            </ul>
                        </div></td>
                        <td>' . $file['name'] . '</td>
                        <td><a href="' . $file['full_url'] . '" title="Mở file" target="_blank">' . $file['url'] . '</a></td>
                        <td>' . size2text($file['size']) . '</td>
                        </tr>';
                    }
                    echo '</table></div>';
                }
            });
        });
    });
}, array('after' => '<div class="pull-right sparkline-box">
<div class="btn-group"><a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blog']['id'] . '" class="btn btn-default"><i class="fa fa-arrow-left"></i></a></div>
</div>'));