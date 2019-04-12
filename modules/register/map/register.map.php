<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
ZenView::section('Đăng kí', function() {
    ZenView::block('Đăng kí thành viên', function() {
        ZenView::padded(function() {
            echo '<div class="zen-register-box">';
            ZenView::display_message();
            echo '<form method="POST">';
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
            echo '<div class="form-group">
            <label for="captcha_code" class="col-sm-2 control-label"><img src="' . ZenView::$D['captcha_src'] . '"/></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="captcha_code" id="captcha_code" style="max-width:100px" placeholder="Mã xác nhận"/>
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