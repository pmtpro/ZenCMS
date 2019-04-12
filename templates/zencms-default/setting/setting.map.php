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
ZenView::block('Cấu hình blog', function() use ($registry) {
    ZenView::padded(function() use ($registry) {
        ZenView::display_message();

        echo '<form role="form" class="form-horizontal validatable" method="POST">';

        echo '<div class="form-group">
        <label for="index_num_post_top_new" class="col-lg-4 control-label">Số lượng bài viết hiển thị trên trang chủ</label>
        <div class="col-lg-8"><input type="text" id="index_num_post_top_new" name="index_num_post_top_new" value="' .  tplConfig('index_num_post_top_new', 'zencms-default') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-offset-4 col-lg-8">
            <label><input type="checkbox" id="index_display_top_new_paging" name="index_display_top_new_paging" value="1" ' .  (tplConfig('index_display_top_new_paging', 'zencms-default') ? 'checked' : '') . '/> Hiển thị phân trang ở trang chủ</label>
        </div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_top_hot" class="col-lg-4 control-label">Số lượng bài viết nhiều lượt xem nhất hiện thị bên phải</label>
        <div class="col-lg-8"><input type="text" id="num_post_top_hot" name="num_post_top_hot" value="' .  tplConfig('num_post_top_hot', 'zencms-default') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_in_folder" class="col-lg-4 control-label">Số lượng bài viết hiển thị trong thư mục</label>
        <div class="col-lg-8"><input type="text" id="num_post_in_folder" name="num_post_in_folder" value="' .  tplConfig('num_post_in_folder', 'zencms-default') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_same_post_in_folder" class="col-lg-4 control-label">Số bài viết tương tự trong thư mục</label>
        <div class="col-lg-8"><input type="text" id="num_same_post_in_folder" name="num_same_post_in_folder" value="' .  tplConfig('num_same_post_in_folder', 'zencms-default') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_same_post_in_post" class="col-lg-4 control-label">Số bài viết tương tự trong bài viết</label>
        <div class="col-lg-8"><input type="text" id="num_same_post_in_post" name="num_same_post_in_post" value="' .  tplConfig('num_same_post_in_post', 'zencms-default') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-8 col-lg-offset-4"><button type="submit" name="submit-blog" class="btn btn-primary">Lưu thay đổi</button></div>
        </div>';
        echo '</form>';
    });
});