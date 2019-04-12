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
ZenView::section('Quản lí', function() {
    ZenView::display_breadcrumb();
    ZenView::block(ZenView::get_title(true), function() {
        ZenView::padded(function() {
            ZenView::display_message();
            foreach (ZenView::$D['menus'] as $menu) {
                //ZenView::row('<i class="' . $menu['icon'] . '"></i> <a href="' . $menu['full_url'] . '">' . $menu['name'] . '</a>');
                echo('<div class="action-nav-normal action-nav-line">
                <div class="row action-nav-row">
                <div class="col-sm-2 action-nav-button">
                  <a href="' . $menu['full_url'] . '" title="' . $menu['name'] . '">
                    <i class="' . $menu['icon'] . '"></i>
                    <span>' . $menu['name'] . '</span>
                  </a>
                </div>
                </div>
                </div>');
            }
        });
    });
});