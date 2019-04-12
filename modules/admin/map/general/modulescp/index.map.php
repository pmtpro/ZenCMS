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
ZenView::section('Module CPanel', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Danh sách ứng dụng', function() {
        ZenView::padded(function() {
            ZenView::display_message();
            echo '<div class="action-nav-normal action-nav-line">';
            echo '<div class="row action-nav-row">';
            foreach (ZenView::$D['list_extends_modules'] as $mod):
                echo('<div class="col-sm-2 action-nav-button">
                  <a href="' . $mod['full_url'] . '" title="' . $mod['title'] . '" data-original-title="' . $mod['title'] . '" rel="tooltip">
                    <i class="' . $mod['icon'] . '"></i>
                    <span>' . $mod['name'] . '</span>
                  </a>
                  <span class="triangle-button red"><i class="icon-plus"></i></span>
                </div>');
            endforeach;
            echo '</div>';
            echo '</div>';
        });
    });
});