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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
ZenView::section('Cài đặt giao diện', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Chọn giao diện và cài đặt', function() {
        ZenView::padded(function() {
            ZenView::display_message();
            ZenView::display_message('template-accept-format');
            echo '<form role="form" class="form-horizontal fill-up validatable" method="POST" enctype="multipart/form-data">';
            echo '<div class="form-group">
            <label for="Mobile" class="col-lg-2 control-label">Chọn tệp tin</label>
            <div class="col-lg-9">
            <input type="file" name="template" accept=""/>
            </div>
            </div>';
            echo '<div class="form-group"><div class="col-lg-9 col-lg-offset-2">
            <button type="submit" name="submit-upload" class="btn btn-primary">Tải lên</button>
            </div></div>';
            echo '</form>';
        });
    });
}, array('after'=>$menu));