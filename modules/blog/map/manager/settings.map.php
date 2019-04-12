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
ZenView::section('Cài đặt', function() {
    ZenView::display_breadcrumb();
    ZenView::block(ZenView::get_title(true), function() {
        ZenView::padded(function() {
            ZenView::display_message();
            echo '<form role="form" class="form-horizontal validatable" method="POST">';
            echo '<div class="form-group">
            <label for="allow_post_comment" class="col-lg-2 control-label">Comment bài viết</label>
            <div class="col-lg-9">
            <input type="checkbox" id="allow_post_comment" name="allow_post_comment" value="1" class="form-control iButton-icons" ' . (ZenView::$D['config']['allow_post_comment'] ? 'checked': '') . '/>
            </div>
            </div>';
            echo '<div class="form-group">
            <div class="col-lg-9 col-lg-offset-2"><button type="submit" name="submit-setting" class="btn btn-primary">Lưu thay đổi</button></div>
            </div>';
            echo '</form>';
        });
    });
});