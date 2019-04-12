<?php
ZenView::section('Thay đổi API Key', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Nhập apiKey', function() {
        ZenView::padded(function() {
            ZenView::display_message('set_api');
			echo '<form method="POST">
					<div class="form-group">
						API Key: <input type="text" name="apikey" value="'.ZenView::$D['cur_api'].'" class="validate[required]"/>
						<span class="help-block note"><i class="icon-warning-sign"></i> Đoạn mã này bạn được Mobigate cung cấp sẵn (Lấy API Key tại <b><a href="http://mobigate.vn/tai-khoan">đây</a></b>)</span>
					</div>
					<div class="form-actions">
						<input type="submit" value="Thay đổi" class="btn btn-blue" />
					</div>
				</form>';
        });
    });
});