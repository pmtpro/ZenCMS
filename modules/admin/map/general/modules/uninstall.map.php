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
ZenView::section('Gỡ bỏ module', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Xác nhận gỡ bỏ module', function() {
        ZenView::padded(function() {
            echo '<form method="POST">';
            ZenView::display_message();
            echo('<div class="form-group">
            <input type="submit" name="submit-uninstall" value="Gỡ bỏ" class="btn btn-red"/>
            <input type="submit" name="submit-cancel" value="Hủy" class="btn btn-default"/>
            </div>');
            echo '</form>';
        });
    });
}, array('after'=>$menu));