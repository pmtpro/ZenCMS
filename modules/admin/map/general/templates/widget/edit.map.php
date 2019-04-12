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
ZenView::section('Chỉnh sửa widget', function() {
    ZenView::display_breadcrumb();
    ZenView::block(ZenView::$D['widget_data']['title'] ? ZenView::$D['widget_data']['title'] : 'Không tiêu đề', function() {
        echo '<form class="form-horizontal fill-up validatable" method="POST">';
        ZenView::padded(function () {
            ZenView::display_message();
            echo '<div class="form-group">
              <label class="control-label col-lg-2">Tiêu đề (Có thể bỏ qua)</label>
              <div class="col-lg-10">
                <input type="text" name="title" placeholder="Tiêu đề" value="' . ZenView::$D['widget_data']['title'] . '"/>
              </div>
            </div>';
            echo '<div class="form-group">
              <label class="control-label col-lg-2">Nội dung</label>
              <div class="col-lg-10">
                <textarea name="content" class="validate[required]" rows="6">' . ZenView::$D['widget_data']['content'] . '</textarea>
              </div>
            </div>';
        });
        echo '<div class="box-footer">
        <input type="submit" name="submit-save" value="Lưu thay đổi" class="btn btn-blue rm-fill-up">
        </div>';
        echo '</form>';
    });
});