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
ZenView::section('Trang cá nhân', function() {
    ZenView::block('Cài đặt tài khoản', function() {
        ZenView::display_breadcrumb();
        ZenView::padded(function() {
            ZenView::display_message();
            echo '<form class="form-horizontal" role="form" method="POST">';
            echo '<h3 class="row-header">Tùy chọn</h3>';
            echo '<div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <div class="checkbox">
                    <label>
                      <input type="checkbox" name="allow_wall_comment" value="1" ' . (ZenView::$D['user_set']['allow_wall_comment'] == 1? 'checked': '') . '/> Cho phép tất cả mọi người comment trên tường nhà tôi
                    </label>
                </div>
            </div>
            </div>';
            echo '<div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <div class="checkbox">
                    <label>
                      <input type="checkbox" name="allow_view_wall_comment" value="1" ' . (ZenView::$D['user_set']['allow_view_wall_comment'] == 1? 'checked': '') . '/> Bất kì ai cũng có thể xem những thảo luận trên tường nhà tôi
                    </label>
                </div>
            </div>
            </div>';
            echo '<div class="form-group">
            <label class="col-sm-6 control-label"></label>
            <div class="col-sm-2">
              <input type="submit" name="submit-save" value="Lưu thay đổi" class="btn btn-primary"/>
            </div>
        </div>';
            echo '</form>';
        });
    });
});