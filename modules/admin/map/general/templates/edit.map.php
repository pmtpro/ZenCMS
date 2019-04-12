<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::col(function() {
        ZenView::col_item(8, function() {
            ZenView::block('Chỉnh sửa', function() {
                ZenView::display_message();
                if (ZenView::$D['file_name']) {
                    echo '<form method="POST">';
                    echo '<div class="form-group">
                    <label for="file_name">Tên file</label>
                    <input type="text" name="file_name" id="file_name" value="' . ZenView::$D['file_name'] . '" class="form-control" ' . ((!ZenView::$D['is_writable'] || !ZenView::$D['allow_rename']) ? 'readonly="readonly"' : '') . '/>
                    </div>';
                    if (isset(ZenView::$D['file_content'])) {
                        echo '<div class="form-group">
                        <label for="file_content">Nội dung file</label>
                        <textarea name="file_content" id="file_content" class="form-control" rows="30" ' . (!ZenView::$D['is_writable'] ? 'readonly="readonly"' : '') . '>' . ZenView::$D['file_content'] . '</textarea>
                        </div>';
                    } else {
                        if (ZenView::$D['is_image']) {
                            echo '<div class="text-center"><img src="' . ZenView::$D['download_link'] . '?t=' . time() . '" style="max-width: 100%"/></div>';
                        } else {
                            echo '<div class="text-center"><a href="' . ZenView::$D['download_link'] . '" title="Tải file về" class="btn btn-success"><span class="fa fa-download"></span> ' . ZenView::$D['file_name'] . '</a></div>';
                        }
                    }
                    echo '<div class="form-group text-center">
                    <input type="submit" name="submit-reload" value="Tải lại nội dung" class="btn btn-default"/>
                    <input type="submit" name="submit-save" value="Lưu lại" ' . (!ZenView::$D['is_writable'] ? 'disabled' : '') . ' class="btn btn-primary"/>
                    </div>';
                    echo '</form>';
                }
            });
        });
        ZenView::col_item(4, function() {
           ZenView::block('Danh sách file', function() {
               echo ZenView::$D['tree'];
           });
        });
    });
}, array('after' => $menu));