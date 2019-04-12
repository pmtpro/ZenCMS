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
ZenView::section(ZenView::get_title(true), function ()
{
    ZenView::display_breadcrumb(); ZenView::display_message(); ZenView::col(function
        ()
    {
        ZenView::col_item(4, function ()
        {
            ZenView::block('Quản lí blog', function ()
            {
                ZenView::padded(function ()
                {
                    echo '<ul class="list-group">'; $page_menu = ZenView::get_menu('page_menu');
                        foreach ($page_menu['menu'] as $menu) {
                        echo '<li class="list-group-item">
                        <a href="' . $menu['full_url'] . '" title="' . $menu['title'] .
                            '"><i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</a>
                        </li>'; }
                    echo '</ul>'; echo '<ul class="list-group">'; echo
                        '<li class="list-group-item">Hôm nay đã viết <span class="badge badge-danger">' .
                        ZenView::$D['stat']['number_today_post'] . '</span></li>'; echo
                        '<li class="list-group-item">Tổng số bài đã viết <span class="badge badge-success">' .
                        ZenView::$D['stat']['number_post'] . '</span></li>'; echo
                        '<li class="list-group-item">Tổng số thư mục <span class="badge badge-success">' .
                        ZenView::$D['stat']['number_folder'] . '</span></li>'; echo '</ul>'; }
                ); }
            ); }
        ); ZenView::col_item(8, function ()
        {
            ZenView::block('Biều đồ viết bài', function ()
            {
                ZenView::padded(function ()
                {
                    echo '<div id="post-stat-chart"></div>'; }
                ); }
            ); }
        ); }
    ); }
);
