<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenCMS Team
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
 * @copyright 2012-2014 ZenCMS Team
 * @author ZenCMS Team
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
ZenView::section('Tạo widget', function() {
    ZenView::display_breadcrumb();
    ZenView::block(ZenView::$D['group'] . ': Thông tin widget', function() {
        echo '<form class="form-horizontal" method="POST">';
        ZenView::padded(function () {
            ZenView::display_message();
            echo '<div class="form-group">
              <label class="control-label col-lg-2">Tiêu đề (Có thể bỏ qua)</label>
              <div class="col-lg-10">
                <input type="text" name="title" placeholder="Tiêu đề" value="" class="form-control"/>
              </div>
            </div>';
            echo '<div class="form-group">
              <label class="control-label col-lg-2">Nội dung</label>
              <div class="col-lg-10">
                <textarea name="content" rows="6" class="form-control"></textarea>
              </div>
            </div>';
            echo '<div class="form-group">
              <label class="control-label col-lg-2">PHP Callback</label>
              <div class="col-lg-10">
                <input type="text" name="callback" placeholder="Ví dụ account::user_info" value="" class="form-control"/>
              </div>
            </div>';
            echo '<div class="form-group">
              <label class="control-label col-lg-2"></label>
              <div class="col-lg-10">
                <input type="submit" name="submit-save" value="Tạo widget" class="btn btn-primary rm-fill-up"/>
              </div>
            </div>';
        });
        echo '</form>';
    });
});