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
ZenView::section('Cài đặt đăng kí', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Cài đặt đăng kí', function() {
        if (ZenView::message_exists()) {
            ZenView::row(function() {
                ZenView::display_message();
            });
        }
        echo '<form method="POST">';
        ZenView::row('<input type="checkbox" id="register_turn_off" name="register_turn_off" value="1" ' . (ZenView::$D['config']['register_turn_off'] ? 'checked' : '') . ' />
            <label for="register_turn_off">Không cho phép thành viên đăng kí mới</label>');
        ZenView::row('<label for="register_message">Thông báo ngừng đăng kí</label><br/>
            <textarea id="register_message" name="register_message">' . ZenView::$D['config']['register_message'] . '</textarea>');
        ZenView::row('<input type="checkbox" id="register_turn_on_authorized_email" name="register_turn_on_authorized_email" value="1" ' . (ZenView::$D['config']['register_turn_on_authorized_email'] ? 'checked' : '') . ' />
            <label for="register_turn_on_authorized_email">Yêu cầu xác thực email khi đăng kí</label>');
        ZenView::row('<input type="submit" name="submit-settings" value="Lưu thay đổi" class="btn btn-blue"/>');
        echo '</form>';
    });
});