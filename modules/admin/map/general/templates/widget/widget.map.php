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
ZenView::section('Danh sách widget', function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    echo '<form method="POST">';
    foreach (ZenView::$D['widget_groups'] as $group) {
        ZenView::block(ZenView::$D['widget_list'][$group]['config']['desc'] ? ZenView::$D['widget_list'][$group]['config']['desc'] . ' (<i class="smaller">' . $group . '</i>)' : $group, function() use ($group) {
            ZenView::padded(function() use ($group) {
                if (isset(ZenView::$D['widget_groups_data'][$group]) && is_array(ZenView::$D['widget_groups_data'][$group])) {
                    $groupData = ZenView::$D['widget_groups_data'][$group];
                    echo '<div class="row">';
                    foreach ($groupData as $widget) {
                        echo '<div class="col-md-3"><div class="well relative">
                          <a href="' . ZenView::$D['base_url'] . '?act=delete&id=' . $widget['id'] . '"><span class="triangle-button red"><i class="icon-trash"></i></span></a>
                          <input type="text" name="weight[' . $widget['id'] . ']" style="width: 30px" value="' . $widget['weight'] . '"/>
                          <span class="widget-title">
                            <a href="' . ZenView::$D['base_url'] . '?act=edit&id=' . $widget['id'] . '">' . ($widget['title']? $widget['title'] : '<i class="smaller">Không tiêu đề</i>') . ' <i class="icon-pencil"></i></a>
                          </span>
                        </div></div>';
                    }
                    echo '</div>';
                } else echo '<i class="smaller">Chưa có widget nào ở đây</i>';
            });
            echo '<div class="box-footer">
            <input type="submit" name="submit-order[' . $group . ']" value="Sắp xếp" class="btn btn-blue rm-fill-up">
            </div>';
        }, array('after' => '<ul class="box-toolbar"><li class="toolbar-link">
        <a href="' . ZenView::$D['base_url'] . '?act=new&wg=' . urlencode($group) . '"><i class="icon-plus"></i> Thêm mới</a></li></ul>'));
    }
    echo '</form>';
}, array('after' => $menu));