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
ZenView::section('Cài đặt tài khoản', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block('Cài đặt bảo mật', function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                echo '<h3>Mật khẩu</h3>';
                ZenView::display_message('security-password');
                echo '<form class="form-horizontal" name="form-password" role="form" method="POST">';
                echo '<div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Mật khẩu cũ</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" id="password" name="password"/>
                      ' . ZenView::get_message('password', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="new-password" class="col-sm-2 control-label">Mật khẩu mới</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" id="new-password" name="new-password"/>
                      ' . ZenView::get_message('new-password', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="re-new-password" class="col-sm-2 control-label">Nhập lại mật khẩu mới</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" id="re-new-password" name="re-new-password"/>
                      ' . ZenView::get_message('re-new-password', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <button type="submit" name="submit-change-password" class="btn btn-primary">Thay đổi mật khẩu <span class="fa fa-lock"></span></button>
                    </div>
                </div>';
                echo '</form>';

                echo '<h3>Mail</h3>';
                ZenView::display_message('security-email');
                echo '<form class="form-horizontal" name="form-email" role="form" method="POST">';
                echo '<div class="form-group">
                    <label for="confirm-password" class="col-sm-2 control-label">Mật khẩu</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" id="confirm-password" name="confirm-password"/>
                      ' . ZenView::get_message('confirm-password', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Đổi email</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="email" name="email"/>
                      ' . ZenView::get_message('email', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <input type="submit" name="submit-change-email" class="btn btn-primary" value="Thay đổi email"/>
                    </div>
                </div>';
                echo '</form>';
            });
        });
        ZenView::col_item(3, function() {
            $pageMenu = ZenView::get_menu('page');
            if (isset($pageMenu['name'])) ZenView::block($pageMenu['name'], function() use ($pageMenu) {
                echo '<ul class="list-group">';
                foreach ($pageMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });

            $objMenu = ZenView::get_menu('main');
            ZenView::block($objMenu['name'], function() use ($objMenu) {
                echo '<ul class="list-group">';
                foreach ($objMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });
        });
    });
});