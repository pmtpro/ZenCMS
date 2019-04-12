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
ZenView::section('Module CPanel', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Danh sách ứng dụng', function() {
        ZenView::padded(function() {
            ZenView::display_message();
            foreach (ZenView::$D['list_extends_modules'] as $mod):
                echo '<a href="' . $mod['full_url'] . '" class="btn" title="' . $mod['title'] . '">
                    <i class="' . $mod['icon'] . '"></i>
                    <span>' . $mod['name'] . '</span>
                </a> ';
            endforeach;
        });
    });
});