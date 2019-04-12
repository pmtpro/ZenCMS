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
ZenView::section('Tải lên module', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Thông tin module', function() {
        ZenView::padded(function() {
            ZenView::display_message();
            ZenView::display_message('module-updatable');
            ZenView::col(function() {
                $tableFunc = function($moduleInfo) {
                    echo('<table class="table table-normal">
                    <tr><td class="icon"><i class="icon-adjust"></i></td><td>Tên</td><td>' . $moduleInfo['name'] . '</td></tr>
                    <tr><td class="icon"><i class="icon-link"></i></td><td>URL</td><td>' . $moduleInfo['url'] . '</td></tr>
                    <tr><td class="icon"><i class="icon-user"></i></td><td>Tác giả</td><td>' . $moduleInfo['author'] . '</td></tr>
                    <tr><td class="icon"><i class="icon-umbrella"></i></td><td>Version</td><td>' . $moduleInfo['version'] . '</td></tr>
                    <tr><td class="icon"><i class="icon-comment"></i></td><td>Mô tả</td><td>' . $moduleInfo['des'] . '</td></tr>
                </table>');
                };
                ZenView::col_item(6, function() use ($tableFunc) {
                    ZenView::block('Module mới tải lên', function() use ($tableFunc) {
                        call_user_func($tableFunc, ZenView::$D['mod']);
                    }) ;
                });
                ZenView::col_item(6, function() use ($tableFunc) {
                    ZenView::block('Phiên bản trước đó', function() use ($tableFunc) {
                        call_user_func($tableFunc, ZenView::$D['o_mod']);
                    }) ;
                });
            });
        });
        echo('<div class="box-footer padded">
          <span class="pull-right">
            <form method="POST">
                <input type="submit" name="submit-update" class="btn btn-blue" value="Cập nhật"/>
                <input type="submit" name="submit-delete" class="btn btn-default" value="Không cập nhật"/>
            </form>
          </span>
        </div>');
    });
}, array('after'=>$menu));