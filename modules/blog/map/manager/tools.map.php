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
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::col(function(){
        ZenView::col_item(8, function() {
            ZenView::block('Dánh sách tool', function() {
                ZenView::padded(function() {
                    ZenView::display_message();
                    echo '<div class="list-group">';
                    foreach (ZenView::$D['menus'] as $menu) {
                        echo '<a href="' . $menu['full_url'] . '" title="' . $menu['title'] . '" class="list-group-item">
                        <h4><i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</h4>
                        <p class="list-group-item-text">' . $menu['des'] . '</p>
                        </a>';
                    }
                    echo '</div>';
                });
            });
        });
        ZenView::col_item(4, function() {
            echo '<ul class="list-group">';
            $page_menu = ZenView::get_menu('page_menu');
            foreach ($page_menu['menu'] as $menu) {
                echo '<li class="list-group-item">
                <a href="' . $menu['full_url'] . '" title="' . $menu['title'] . '"><i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</a>
                </li>';
            }
            echo '</ul>';
        });
    });
});