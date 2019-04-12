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
ZenView::section('Cài đặt đăng kí', function() {
    ZenView::display_breadcrumb();
    ZenView::col(function() {
        ZenView::col_item(8, function() {
            ZenView::block('Cài đặt đăng kí', function() {
                ZenView::display_message();
                echo '<form method="POST" class="form-horizontal">';
                echo '<div class="form-group">
                <label for="register_turn_off" class="col-lg-4 control-label">Không cho phép thành viên đăng kí mới</label>
                <div class="col-lg-8">
                    <input type="checkbox" id="register_turn_off" name="register_turn_off" value="1" ' . (ZenView::$D['config']['register_turn_off'] ? 'checked' : '') . '/>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="register_message" class="col-lg-4 control-label">Thông báo ngừng đăng kí</label>
                <div class="col-lg-8">
                    <textarea id="register_message" name="register_message" class="form-control">' . h(ZenView::$D['config']['register_message']) . '</textarea>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="register_turn_on_authorized_email" class="col-lg-4 control-label">Yêu cầu xác thực email khi đăng kí</label>
                <div class="col-lg-8">
                    <input type="checkbox" id="register_turn_on_authorized_email" name="register_turn_on_authorized_email" value="1" ' . (ZenView::$D['config']['register_turn_on_authorized_email'] ? 'checked' : '') . '/>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="msg_register_success" class="col-lg-4 control-label">Thông báo đăng kí hoàn thành (không yêu cầu mail kích hoạt)</label>
                <div class="col-lg-8">
                    <textarea id="msg_register_success" name="msg_register_success" class="form-control">' . h(ZenView::$D['config']['msg_register_success']) . '</textarea>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="msg_register_success_send_success" class="col-lg-4 control-label">Thông báo đăng kí hoàn thành (khi đã gửi mail kích hoạt)</label>
                <div class="col-lg-8">
                    <textarea id="msg_register_success_send_success" name="msg_register_success_send_success" class="form-control">' . h(ZenView::$D['config']['msg_register_success_send_success']) . '</textarea>
                </div>
                </div>';
                echo '<div class="form-group">
                <label for="msg_register_success_send_fail" class="col-lg-4 control-label">Thông báo đăng kí hoàn thành (khi không gửi được mail kích hoạt)</label>
                <div class="col-lg-8">
                    <textarea id="msg_register_success_send_fail" name="msg_register_success_send_fail" class="form-control">' . h(ZenView::$D['config']['msg_register_success_send_fail']) . '</textarea>
                </div>
                </div>';
                echo '<div class="form-group">
                <label class="col-lg-4 control-label"></label>
                <div class="col-lg-8">
                    <input type="submit" name="submit-settings" value="Lưu thay đổi" class="btn btn-primary"/>
                </div>
                </div>';
                echo '</form>';
            });
        });
        ZenView::col_item(4, function() {
            ZenView::block('Hỗ trợ', function() {
                echo '<table class="table">
                    <thead><tr><td>Key</td><td>Mô tả</td></tr></thead>
                    <tr><td>{$reg_nickname}</td><td>Lấy nickname đăng kí</td></tr>
                    <tr><td>{$reg_username}</td><td>Lấy username đăng kí</td></tr>
                    <tr><td>{$reg_email}</td><td>Lấy email đăng kí</td></tr>
                </table>';
            });
        });
    });

});