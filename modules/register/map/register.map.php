<?php
ZenView::section('Đăng kí', function() {
    ZenView::block('Đăng kí thành viên', function() {
        ZenView::padded(function() {
            echo '<div class="zen-register-box">';
            ZenView::display_message();
            echo '<form method="POST" class="form-horizontal">';
            echo '<div class="form-group">
            <label for="username" class="col-sm-2 control-label">Tên đăng nhập</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập"/>
            </div>
            </div>';
            echo '<div class="form-group">
            <label for="password" class="col-sm-2 control-label">Mật khẩu</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"/>
            </div>
            </div>';
            echo '<div class="form-group">
            <label for="repassword" class="col-sm-2 control-label">Nhập lại mật khẩu</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="repassword" name="repassword"  placeholder="Nhập lại mật khẩu"/>
            </div>
            </div>';
            if (modConfig('register_turn_on_authorized_email', 'register')) {
                echo '<div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="email" name="email"  placeholder="Email"/>
                </div>
                </div>';
            }
            echo '<div class="form-group row">
                <div class="col-md-2"><img src="' . ZenView::$D['captcha_src'] . '" id="zen-login-captcha" title="Nhập captcha"/></div>
                <div class="col-md-10">
                    <label for="captcha_code">Nhập mã xác nhận</label><br/>
                    <input type="text" name="captcha_code" id="captcha_code" style="max-width:100px" placeholder="Mã xác nhận"/>
                </div>
               </div>';
            echo '<div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="hidden" name="token_register" value="' . ZenView::$D['token_register'] . '"/>
              <input type="submit" name="submit-register" value="Đăng kí" class="btn btn-primary"/>
            </div>
            </div>';
            echo '</form>';
            echo '</div>';
        });
    });
});