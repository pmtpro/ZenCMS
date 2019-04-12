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
ZenView::section('Đăng nhập thành viên', function() {
   ZenView::block('Thông tin đăng nhập', function() {
       ZenView::padded(function() {
           ZenView::display_message();
           echo '<form method="POST">';
           echo '<div class="form-group">
           <label for="username">Tên đăng nhập</label>
           <input type="text" class="form-control" id="username" name="username"/>
           </div>';
           echo '<div class="form-group">
           <label for="password">Mật khẩu</label>
           <input type="password" class="form-control" id="password" name="password"/>
           </div>';
           if (ZenView::$D['limit_login']) {
               echo '<div class="form-group">
                <div class="col-md-2"><img src="' . ZenView::$D['captcha_src'] . '" id="zen-login-captcha" title="Nhập captcha"/></div>
                <div class="col-md-10">
                    <label for="captcha_code">Nhập mã xác nhận</label><br/>
                    <input type="text" id="captcha_code" name="captcha_code" style="width:50px;"/>
                </div>
               </div>';
           }
           echo '<div class="form-group">
           <input type="checkbox" id="remember_me" name="remember_me" value="1"/> <label for="remember_me">Ghi nhớ tôi</label>
           </div>';
           echo '<div class="form-group">
            <input type="hidden" name="token_login" value="' . ZenView::$D['token_login'] . '"/>
            <input type="submit" name="submit-login" value="Đăng nhập" class="btn btn-primary"/>
            <a href="' . HOME . '/account/forgot_password">Quên mật khẩu</a>
            </div>';
           echo '</form>';
       });
   });
});