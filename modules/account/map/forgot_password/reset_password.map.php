<?php
ZenView::section('Tài khoản', function() {
    ZenView::block('Đổi mật khẩu', function() {
        ZenView::display_breadcrumb();
        ZenView::display_message();
        echo '<form role="form" class="form-horizontal" method="POST">';
        echo '<div class="form-group">
        <label class="control-label col-lg-3" for="new_password">Mật khẩu mới</label>
        <div class="col-lg-9">
            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Nhập mật khẩu mới"/>
        </div>
        </div>';
        echo '<div class="form-group">
        <label class="control-label col-lg-3" for="re_new_password">Nhập lại mật khẩu mới</label>
        <div class="col-lg-9">
            <input type="password" name="re_new_password" id="re_new_password" class="form-control" placeholder="Nhập lại mật khẩu mới"/>
        </div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-3">
            <input type="submit" name="submit-new-password" class="btn btn-primary" value="Đổi mật khẩu"/>
        </div>
        </div>';
        echo '</form>';
    });
});