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
ZenView::section('Cầu hình chính', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Cấu hình trang', function() {
        ZenView::padded(function() {
            ZenView::display_message('main-config');
            echo '<form role="form" class="form-horizontal fill-up validatable" method="POST">';
            echo('<div class="form-group">
            <label for="home" class="col-lg-2 control-label">Địa chỉ trang chủ</label>
            <div class="col-lg-9"><input type="text" id="home" name="home" value="' . dbConfig('home') . '" class="form-control"/></div>
            </div>
            <div class="form-group">
            <label for="title" class="col-lg-2 control-label">Tiêu đề trang</label>
            <div class="col-lg-9"><input type="text" id="title" name="title" value="' . dbConfig('title') . '" class="form-control"/></div>
            </div>
            <div class="form-group">
            <label for="keyword" class="col-lg-2 control-label">Keyword</label>
            <div class="col-lg-9"><textarea name="keyword" rows="3" id="keyword">' . dbConfig('keyword') . '</textarea></div>
            </div>
            <div class="form-group">
            <label for="des" class="col-lg-2 control-label">Mô tả trang</label>
            <div class="col-lg-9"><textarea name="des" rows="3" id="des">' . dbConfig('des') . '</textarea></div>
            </div>
            <div class="form-group">
            <div class="col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-main" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>
            </form>');
        });
    });
    ZenView::block('Cấu hình gửi mail', function() {
        ZenView::padded(function() {
            echo '<form role="form" class="form-horizontal fill-up validatable" method="POST">';
            ZenView::display_message('mail-config');
            echo('<div class="form-group">
            <label for="mail_host" class="col-lg-2 control-label">Host SMTP</label>
            <div class="col-lg-9">
            <input type="text" id="mail_host" name="mail_host" value="' . dbConfig('mail_host') . '" class="form-control">
            <span class="help-block">Ví dụ: Gmail là smtp.gmail.com, Yahoo là smtp.mail.yahoo.com</span>
            </div>
            </div>');
            echo('<div class="form-group">
            <label for="mail_port" class="col-lg-2 control-label">Cổng gửi mail</label>
            <div class="col-lg-9">
            <input type="text" id="mail_port" name="mail_port" value="' . dbConfig('mail_port') . '" class="form-control">
            <span class="help-block">Mặc định là 587</span>
            </div>
            </div>');
            echo('<div class="form-group">
            <label for="mail_smtp_secure" class="col-lg-2 control-label">Mã hóa</label>
            <div class="col-lg-9">
            <select class="uniform" name="mail_smtp_secure" id="mail_smtp_secure">');
            foreach(ZenView::$D['mail_config']['mail_smtp_secure'] as $secure => $name_secure):
                echo '<option value="' . $secure . '" ' . (dbConfig('mail_smtp_secure') == $secure ? 'selected' : '') . '>' . $name_secure . '</option>';
            endforeach;
            echo '</select></div></div>';
            echo('<div class="form-group">
            <label for="mail_smtp_auth" class="col-lg-2 control-label">Xác thực SMTP</label>
            <div class="col-lg-9">
                <input type="checkbox" class="iButton-icons" id="mail_smtp_auth" name="mail_smtp_auth" value="1" ' . (dbConfig('mail_smtp_auth') ? 'checked' : '') . '/>
            </div>
            </div>');
            echo('<div class="form-group">
            <label class="col-lg-2 control-label" for="mail_username">Tên đăng nhập</label>
            <div class="col-lg-9">
            <input type="email" class="form-control" id="mail_username" name="mail_username" value="' . dbConfig('mail_username') . '" placeholder="Tên tài khoản"/>
            <span class="help-block">Tên đăng nhập tài khoản mail</span>
            </div>
            </div>');
            echo('<div class="form-group">
            <label class="col-lg-2 control-label" for="mail_password">Mật khẩu</label>
            <div class="col-lg-9">
            <input type="password" class="form-control" id="mail_password" name="mail_password" value="' . dbConfig('mail_password') . '" placeholder="Mật khẩu"/>
            <span class="help-block">Mật khẩu tài khoản mail</span>
            </div>
            </div>');
            echo('<div class="form-group">
            <label class="col-lg-2 control-label" for="mail_setfrom">Email người gửi</label>
            <div class="col-lg-9">
            <input type="email" class="form-control" id="mail_setfrom" name="mail_setfrom" value="' . dbConfig('mail_setfrom') . '" placeholder="Ví dụ: zencms@yourdomain.com"/>
            </div>
            </div>');
            echo('<div class="form-group">
            <label class="col-lg-2 control-label" for="mail_name">Tên người gửi</label>
            <div class="col-lg-9">
            <input type="text" class="form-control" id="mail_name" name="mail_name" value="' . dbConfig('mail_name') . '" placeholder="Ví dụ: ZenCMS"/>
            </div>
            </div>');
            echo('<div class="form-group">
            <div class="col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-mail" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>');
            echo '</form>';
        });
    });
});