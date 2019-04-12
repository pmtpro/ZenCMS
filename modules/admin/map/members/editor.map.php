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
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::block(ZenView::$D['user']['nickname'], function() {
        ZenView::padded(function() {
            echo '<form method="POST">';
            echo('<table class="table table-bordered table-striped responsive"><tbody>
            <tr>
                <td width="35%">Username</td>
                <td width="65%">' . ZenView::$D['user']['username'] . '</td>
            </tr>
            <tr>
                <td width="35%">Nickname</td>
                <td width="65%"><input type="text" name="nickname" placeholder="Nickname" value="' . ZenView::$D['user']['nickname'] . '"/></td>
            </tr>
            <tr>
                <td width="35%">Đổi mật khẩu</td>
                <td width="65%"><input type="password" name="password" placeholder="Đổi mật khẩu"/></td>
            </tr>
            <tr>
                <td width="35%">Nhập lại mật khẩu</td>
                <td width="65%"><input type="password" name="repassword" placeholder="Nhập lại mật khẩu"/></td>
            </tr>
            <tr>
                <td width="35%">Email</td>
                <td width="65%"><input type="text" name="email" placeholder="Email" value="' . ZenView::$D['user']['email'] . '"/></td>
            </tr>');
            echo('<tr>
            <td width="35%">Quyền hạn</td>
            <td width="65%"><select name="perm">');
            $user_perm = sysConfig('user_perm');
            foreach ($user_perm['name'] as $permKey=>$permName) {
                echo '<option value="' . $permKey . '" ' . (ZenView::$D['user']['perm'] == $permKey ? 'selected':'') . '>' . $permName . '</option>';
            }
            echo('</select></td>
            </tr>');
            echo '</tbody></table>';
        });
        echo('<div class="box-footer">
        <input type="submit" name="submit-save" id="submit-save" value="Lưu thay đổi" class="btn btn-blue rm-fill-up"/>
        </div>');
        echo '</form>';
    });
});