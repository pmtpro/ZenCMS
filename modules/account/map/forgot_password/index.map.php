<?php
ZenView::section('Tài khoản', function() {
    ZenView::block('Quên mật khẩu', function() {
        ZenView::display_breadcrumb();
        ZenView::display_message();
        echo '<form role="form" class="form-horizontal" method="POST">';
        echo '<div class="form-group">
        <label class="control-label col-lg-3" for="username">Nhập username</label>
        <div class="col-lg-9">
            <input type="text" name="username" id="username" class="form-control" placeholder="Nhập username"/>
        </div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-3">
            <input type="submit" name="submit-get-password" class="btn btn-primary" value="Lấy lại mật khẩu"/>
        </div>
        </div>';
        echo '</form>';
    });
});