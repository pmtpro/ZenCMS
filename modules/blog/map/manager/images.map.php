<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    if (ZenView::$D['blog']) {
        ZenView::col(function() {
            ZenView::col_item(4, function() {
                ZenView::block('Tải ảnh lên', function() {
                    ZenView::display_message('upload-image');
                    echo '<form class="fill-up" method="POST" enctype="multipart/form-data">';
                    echo '<div class="form-group">
                    <label>Upload qua URL</label>
                    <textarea name="images-url" class="form-control" rows="6"></textarea>
                    <div class="help-block pull-right">Mỗi URL ảnh để ở 1 dòng</div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Hoặc chọn ảnh</label>
                    <input type="file" name="images[]" class="form-control" multiple/>
                    </div>';
                    echo '<div class="form-group">
                    <input type="submit" name="submit-upload" value="Tải lên" class="btn btn-primary"/>
                    </div>';
                    echo '</form>';
                });
                ZenView::block('Hành động', function() {
                    if (ZenView::$D['show_form_delete_all_images']) {
                        echo '<a name="delete_all_images"></a>';
                        ZenView::display_message('delete-all-images');
                        echo '<form method="POST">';
                        echo '<div class="form-group text-center">
                            <input type="submit" name="submit-delete-all-images" value="Xóa tất cả hình ảnh" class="btn btn-danger"/>
                            <a href="' . ZenView::$D['main_url'] . '" class="btn btn-default">Hủy</a>
                        </div>';
                        echo '</form>';
                    }
                    echo '<div class="list-group">';
                    echo '<div class="list-group-item"><a href="' . ZenView::$D['main_url'] . '&act=delete_all_images#delete_all_images"><span class="fa fa-times"></span> Xóa tất cả hình ảnh</a></div>';
                    echo '</div>';
                });
            });
            ZenView::col_item(8, function() {
                ZenView::block('Danh sách ảnh đã tải lên', function() {
                    ZenView::display_message('list-images');
                    echo '<div id="list-images">';
                    $i = 0;
                    $numCol = 4;//col-lg-3
                    $colName = 12/$numCol;
                    $numItem = count(ZenView::$D['list_images']);
                    foreach (ZenView::$D['list_images'] as $img) {
                        $i++;
                        if ($i == 1 || $i%$numCol == 1) {
                            echo '<div class="row">';
                        }
                        echo '<div class="col-lg-' . $colName . '"><div class="thumbnail">
                            <img src="' . $img['full_url'] . '" style="max-height: 100px"/>';
                        echo '<div class="caption text-center">';
                        echo '<div class="btn-group">';
                        echo '<a class="btn btn-success btn-sm copy-short-url" data-clipboard-text="' . $img['short_url'] . '">Copy</a>';
                        echo '<button class="btn btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>';
                        echo '<ul class="dropdown-menu">';
                        foreach ($img['actions'] as $item) {
                            if ($item['divider']) {
                                echo '<li class="divider"></li>';
                            }
                            echo '<li><a href="' . $item['full_url'] . '" ' . (isset($item['attr']) ? $item['attr'] : '') . '><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div></div>';
                        if ($i == $numItem || $i%$numCol == 0) {
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                });
            });
        });
    }
}, array('after' => '<div class="pull-right sparkline-box">
<div class="btn-group"><a href="' . ZenView::$D['base_url'] . '/editor&id=' . ZenView::$D['blog']['id'] . '" class="btn btn-default"><i class="fa fa-arrow-left"></i></a></div>
</div>'));