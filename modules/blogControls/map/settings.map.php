<?php
ZenView::section(ZenView::get_title(true), function() {
	ZenView::display_breadcrumb();
    ZenView::block('Cài đặt blog', function(){
        ZenView::display_message();
        echo '<div class="row"><h3 class="col-lg-9 col-lg-offset-3">Cài đặt chính</h3></div>';
        echo '<form method="POST" class="form-horizontal">';
        echo '<div class="form-group">
        <label class="control-label col-lg-3">Cài đặt chính</label>
        <div class="col-lg-9">
            <label><input type="checkbox" class="iButton-icons" name="turn_on_import" id="turn_on_import" value="1" '.(ZenView::$D['config']['turn_on_import']=='1'?'checked':'').' /> tự động nhập khẩu ảnh</label><br/>
            <label><input type="checkbox" class="iButton-icons" name="import_local" id="import_local" value="1" '.(ZenView::$D['config']['import_local']=='1'?'checked':'').' /> tự động lấy ảnh về host</label><br/>
            <label><input type="checkbox" class="iButton-icons" name="turn_on_watermark" id="turn_on_watermark" value="1" '.(ZenView::$D['config']['turn_on_watermark']=='1'?'checked':'').' /> đóng dấu ảnh</label><br/>
            <label><input type="checkbox" class="iButton-icons" name="turn_on_auto_gen_desc" id="turn_on_auto_gen_desc" value="1" '.(ZenView::$D['config']['turn_on_auto_gen_desc']=='1'?'checked':'').' /> tự động tạo mô tả nếu thiếu</label>
        </div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-3"><input type="submit" name="submit" id="submit" value="Lưu thay đổi" class="btn btn-primary"/></div>
        </div>';
        echo '</form>';

        echo '<div class="row"><h3 class="col-lg-9 col-lg-offset-3">Cài đặt đóng dấu</h3></div>';
        echo '<form class="form-horizontal" method="POST">';
        ZenView::display_message('watermark-image');
        echo '<div class="form-group">
              <label for="text_watermark" class="control-label col-lg-3">Chữ cần đóng dấu</label>
              <div class="col-lg-6">
                <input type="text" class="form-control" id="text_watermark" name="text_watermark" value="' . ZenView::$D['config']['text_watermark'] . '"/>
              </div>
            </div>';
        echo '<div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
            <input type="submit" name="submit-watermark" id="submit-watermark" value="Lưu thay đổi" class="btn btn-primary"/>
            </div>
            </div>';
        echo '</form>';

        echo '<div class="row"><h3 class="col-lg-9 col-lg-offset-3">Cài đặt mô tả</h3></div>';
        echo '<form class="form-horizontal" method="POST">';
        ZenView::display_message('auto-gen-desc');
        echo '<div class="form-group">
              <label for="num_word_desc_auto_cut" class="control-label col-lg-3">Số lượng kí tự sẽ cắt (khoảng)</label>
              <div class="col-lg-6">
                <input type="text" class="form-control" id="num_word_desc_auto_cut" name="num_word_desc_auto_cut" value="' . ZenView::$D['config']['num_word_desc_auto_cut'] . '"/>
              </div>
            </div>';
        echo '<div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
            <input type="submit" name="submit-desc" id="submit-desc" value="Lưu thay đổi" class="btn btn-primary"/>
            </div>
            </div>';
        echo '</form>';
    });
});