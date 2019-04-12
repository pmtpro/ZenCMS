<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    echo '<ul class="nav nav-tabs" role="tablist">
      <li class="active"><a href="#main-config" data-toggle="tab"><span class="fa fa-gear"></span> Cấu hình trang</a></li>
      <li><a href="#mail-config" data-toggle="tab"><span class="fa fa-envelope-o"></span> Mail</a></li>
      <li><a href="#account-sync-config" data-toggle="tab"><span class="fa fa-retweet"></span> Đồng bộ</a></li>
      <li><a href="#system-config" data-toggle="tab"><span class="fa fa-lock"></span> Hệ thống</a></li>
    </ul>';
    echo '<div class="tab-content">';

    /**
     * #main-config
     */
    echo '<div class="tab-pane active" id="main-config">';
    ZenView::block('Cấu hình trang', function() {
        ZenView::display_message('main-config');
        echo '<form role="form" class="form-horizontal" method="POST" action="' . curPageURL() . '#main-config">';
        echo '<div class="form-group">
            <label for="home" class="col-sm-2 col-lg-2 control-label">Địa chỉ trang chủ</label>
            <div class="col-sm-9 col-lg-9"><input type="text" id="home" name="home" value="' . dbConfig('home') . '" class="form-control"/></div>
            </div>
            <div class="form-group">
            <label for="title" class="col-sm-2 col-lg-2 control-label">Tiêu đề trang</label>
            <div class="col-sm-9 col-lg-9"><input type="text" id="title" name="title" value="' . dbConfig('title') . '" class="form-control"/></div>
            </div>
            <div class="form-group">
            <label for="keyword" class="col-sm-2 col-lg-2 control-label">Keyword</label>
            <div class="col-sm-9 col-lg-9"><textarea name="keyword" rows="3" id="keyword" class="form-control">' . dbConfig('keyword') . '</textarea></div>
            </div>
            <div class="form-group">
            <label for="des" class="col-sm-2 col-lg-2 control-label">Mô tả trang</label>
            <div class="col-sm-9 col-lg-9"><textarea name="des" rows="3" id="des" class="form-control">' . dbConfig('des') . '</textarea></div>
            </div>
            <div class="form-group">
            <label for="image" class="col-sm-2 col-lg-2 control-label">Cover trang</label>
            <div class="col-sm-9 col-lg-9"><input type="text" id="image" name="image" value="' . dbConfig('image') . '" placeholder="http://" class="form-control"/></div>
            </div>
            <div class="form-group">
            <div class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-main" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>
            </form>';
    });
    echo '</div>';

    /**
     * #mail-config
     */
    echo '<div class="tab-pane" id="mail-config">';
    ZenView::block('Cấu hình gửi mail', function() {
        echo '<form role="form" class="form-horizontal fill-up validatable" method="POST" action="' . curPageURL() . '#mail-config">';
        ZenView::display_message('mail-config');
        echo '<div class="form-group">
            <label for="mail_host" class="col-sm-2 col-lg-2 control-label">Kiểu gửi mail</label>
            <div class="col-sm-9 col-lg-9">
            <select name="mail_type" class="form-control">
                <option value="php_mail" ' . (dbConfig('mail_type') == 'php_mail' ? 'selected' : '') . '>Sử dụng PHP Mail()</option>
                <option value="smtp" ' . (dbConfig('mail_type') == 'smtp' ? 'selected' : '') . '>Sử dụng SMTP</option>
            </select>
            </div>
            </div>';
        echo '<div class="form-group">
            <label for="mail_host" class="col-sm-2 col-lg-2 control-label">Host SMTP</label>
            <div class="col-sm-9 col-lg-9">
            <input type="text" id="mail_host" name="mail_host" value="' . dbConfig('mail_host') . '" class="form-control">
            <span class="help-block">Ví dụ: Gmail là smtp.gmail.com, Yahoo là smtp.mail.yahoo.com</span>
            </div>
            </div>';
        echo '<div class="form-group">
            <label for="mail_port" class="col-sm-2 col-lg-2 control-label">Cổng gửi mail</label>
            <div class="col-sm-9 col-lg-9">
            <input type="text" id="mail_port" name="mail_port" value="' . dbConfig('mail_port') . '" class="form-control">
            <span class="help-block">Mặc định là 587</span>
            </div>
            </div>';
        echo '<div class="form-group">
            <label for="mail_smtp_secure" class="col-sm-2 col-lg-2 control-label">Mã hóa</label>
            <div class="col-sm-9 col-lg-9">
            <select class="form-control" name="mail_smtp_secure" id="mail_smtp_secure">';
        foreach(ZenView::$D['mail_config']['mail_smtp_secure'] as $secure => $name_secure):
            echo '<option value="' . $secure . '" ' . (dbConfig('mail_smtp_secure') == $secure ? 'selected' : '') . '>' . $name_secure . '</option>';
        endforeach;
        echo '</select></div></div>';
        echo '<div class="form-group">
            <label for="mail_smtp_auth" class="col-sm-2 col-lg-2 control-label">Xác thực SMTP</label>
            <div class="col-sm-9 col-lg-9">
                <input type="checkbox" class="iButton-icons" id="mail_smtp_auth" name="mail_smtp_auth" value="1" ' . (dbConfig('mail_smtp_auth') ? 'checked' : '') . '/>
            </div>
            </div>';
        echo '<div class="form-group">
            <label class="col-sm-2 col-lg-2 control-label" for="mail_username">Tên đăng nhập SMTP</label>
            <div class="col-sm-9 col-lg-9">
            <input type="email" class="form-control" id="mail_username" name="mail_username" value="' . dbConfig('mail_username') . '" placeholder="Tên tài khoản"/>
            <span class="help-block">Tên đăng nhập tài khoản mail</span>
            </div>
            </div>';
        echo '<div class="form-group">
            <label class="col-sm-2 col-lg-2 control-label" for="mail_password">Mật khẩu SMTP</label>
            <div class="col-sm-9 col-lg-9">
            <input type="password" class="form-control" id="mail_password" name="mail_password" value="' . dbConfig('mail_password') . '" placeholder="Mật khẩu"/>
            <span class="help-block">Mật khẩu tài khoản mail</span>
            </div>
            </div>';
        echo '<div class="form-group">
            <label class="col-sm-2 col-lg-2 control-label" for="mail_setfrom">Email người gửi</label>
            <div class="col-sm-9 col-lg-9">
            <input type="email" class="form-control" id="mail_setfrom" name="mail_setfrom" value="' . dbConfig('mail_setfrom') . '" placeholder="Ví dụ: zencms@yourdomain.com"/>
            </div>
            </div>';
        echo '<div class="form-group">
            <label class="col-sm-2 col-lg-2 control-label" for="mail_name">Tên người gửi</label>
            <div class="col-sm-9 col-lg-9">
            <input type="text" class="form-control" id="mail_name" name="mail_name" value="' . dbConfig('mail_name') . '" placeholder="Ví dụ: ZenCMS"/>
            </div>
            </div>';
        echo '<div class="form-group">
            <div class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-mail" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>';
        echo '</form>';
        echo '<h3 class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">Test mail</h3>';
        echo '<form role="form" class="form-horizontal fill-up validatable" method="POST" action="' . curPageURL() . '#mail-config">';
        echo '<div class="form-group">
            <label class="col-sm-2 col-lg-2 control-label" for="test_mail_to">Gửi đến</label>
            <div class="col-sm-9 col-lg-9">
            <input type="text" class="form-control" id="test_mail_to" name="test_mail_to"/>
            <div class="help-block">Hành động này sẽ gửi 1 mail đến hộp thư bạn chỉ định bên trên</div>
            </div>
            </div>';
        echo '<div class="form-group">
            <div class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-test-mail" class="btn btn-danger">Send mail and test <span class="fa fa-send"></span></button>
            </div>
            </div>';
        echo '</form>';
    });
    ZenView::block('Global mail', function() {
        ZenView::display_message('global-mail-config');
        echo '<form role="form" method="POST" action="' . curPageURL() . '#mail-config">';
        echo '<div class="row">';
        echo '<div class="col-lg-2">
            <div class="help-block">
                Dùng {$mail_subject} để lấy tiêu đề mail<br/>
                Dùng {$mail_content} để lấy nội dung mail<br/>
                Dùng {$mail_from} để lấy mail người gửi<br/>
                Dùng {$mail_to} để lấy mail gửi đến<br/>
                Dùng {$mail_time} để lấy thời gian gửi
            </div>
        </div>';
        echo '<div class="col-lg-9">';
        echo '<div class="form-group">
            <textarea name="global_mail_content" id="global_mail_content" class="form-control" rows="9">' . h_decode(dbConfig('global_mail_content')) . '</textarea>
            <div class="help-block">Bạn có thể sử dụng HTML</div>
        </div>';
        echo '</div>';
        echo '</div>';//end row
        echo '<div class="row"><div class="col-lg-9 col-lg-offset-2"><input type="submit" name="submit-global-mail" value="Lưu thay đổi" class="btn btn-primary"/></div></div>';
        echo '</form>';
    });
    echo '</div>';

    /**
     * #account-sync-config
     */
    echo '<div class="tab-pane" id="account-sync-config">';
    ZenView::block('<a name="account-sync-config"></a>Đồng bộ tài khoản', function() {
        ZenView::padded(function() {
            ZenView::display_message('account-sync-config');
            $userSync = dbConfig('zencmsvnSync-user');
            if ($userSync && is_array($userSync)) {
                echo '<form role="form" class="form-horizontal" method="POST" action="' . curPageURL() . '#account-sync-config">';
                echo '<div class="form-group">
                <label class="col-sm-2 col-lg-2 control-label">Bạn đã đồng bộ với tài khoản</label>
                <div class="col-sm-9 col-lg-9">
                <div class="form-control-static">
                    <div class="media">
                      <a class="pull-left" href="#">
                        <img class="media-object" src="' . $userSync['full_avatar'] . '" style="max-width: 50px"/>
                      </a>
                      <div class="media-body">
                        <div class="media-heading"><b class="text-info">' . $userSync['nickname'] . '</b></div>
                        Đã kết nối ' . m_timetostr($userSync['time_connect']) . '
                      </div>
                    </div>
                </div>
                </div>
                </div>';
                echo '<div class="form-group">
                <div class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">
                    <button type="submit" name="submit-cancel-account-sync" class="btn btn-warning">Ngắt kết nối <span class="fa fa-times"></span></button></p>
                </div>
                </div>';
                echo '</form>';
            } else {
                echo '<form role="form" class="form-horizontal" method="POST" action="' . curPageURL() . '#account-sync-config">';
                echo '<div class="form-group">
                <label for="zen-username" class="col-sm-2 col-lg-2 control-label">Tên đăng nhập</label>
                <div class="col-sm-9 col-lg-9">
                <input type="text" id="zen-username" name="zen-username" value="' . dbConfig('zen-username') . '" placeholder="Username" class="form-control"/>
                <span class="help-block">Tên đăng nhập tài khoản trên <a href="http://zencms.vn" target="_blank">http://zencms.vn</a></span>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="zen-password" class="col-sm-2 col-lg-2 control-label">Mật khẩu</label>
                <div class="col-sm-9 col-lg-9">
                <input type="password" id="zen-password" name="zen-password" value="' . dbConfig('zen-password') . '" placeholder="Password" class="form-control"/>
                <span class="help-block">Mật khẩu đăng nhập tài khoản trên <a href="http://zencms.vn" target="_blank">http://zencms.vn</a></span>
                </div>
                </div>';
                echo '<div class="form-group">
                <div class="col-sm-9 col-sm-offset-2 col-lg-9 col-lg-offset-2">
                  <button type="submit" name="submit-account-sync" class="btn btn-primary">Đăng nhập <i class="fa fa-sign-in"></i></button>
                </div>
                </div>';
                echo '</form>';
            }
        });
    });
    echo '</div>';



    /**
     * #system-config
     */
    echo '<div class="tab-pane" id="system-config">';
    ZenView::block('<a name="system-config"></a>Cấu hình hệ thống', function() {
        ZenView::display_message('system-config');
        echo '<form role="form" class="form-horizontal" method="POST" action="' . curPageURL() . '#system-config">';
        $zl = tz_list();
        echo '<div class="form-group">
            <label for="timezone_identifier" class="col-sm-2 col-lg-2 control-label">Múi giờ</label>
            <div class="col-sm-9 col-lg-9">';
        echo '<select name="timezone_identifier" id="timezone_identifier" class="form-control">';
        foreach ($zl as $item) {
            echo '<option value="' . $item['zone'] . '" ' . (sysConfig('timezone_identifier') == $item['zone'] ? 'selected' : '') . '>' . $item['zone'] . ' ' . $item['diff_from_GMT'] . '</option>';
        }
        echo '</select>';
        echo '</div></div>';

        echo '<div class="form-group">
            <label for="date_format" class="col-sm-2 col-lg-2 control-label">Định dạng ngày tháng</label>
            <div class="col-sm-9 col-lg-9">';
        echo '<select name="date_format" id="date_format" class="form-control">';
        foreach (ZenView::$D['date_format'] as $key => $val) {
            echo '<option value="' . $key . '" ' . (sysConfig('date_format') == $key ? 'selected' : '') . '>' . $val . '</option>';
        }
        echo '</select>';
        echo '</div></div>';

        echo '<div class="form-group">
            <label for="time_format" class="col-sm-2 col-lg-2 control-label">Định dạng giờ</label>
            <div class="col-sm-9 col-lg-9">';
        echo '<select name="time_format" id="time_format" class="form-control">';
        foreach (ZenView::$D['time_format'] as $key => $val) {
            echo '<option value="' . $key . '" ' . (sysConfig('time_format') == $key ? 'selected' : '') . '>' . $val . '</option>';
        }
        echo '</select>';
        echo '</div></div>';

        echo '<div class="form-group">
        <div class="col-sm-9 col-lg-9 col-sm-offset-2 col-lg-offset-2">
            <input type="submit" name="submit-system-config" value="Lưu thay đổi" class="btn btn-primary"/>
        </div>
        </div>';

        echo '</form>';
    });
    echo '</div>';

    echo '</div>';
});