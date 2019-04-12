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
ZenView::section('Tài khoản của bạn', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block('Hồ sơ', function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                echo '<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">';
                echo '<div class="form-group">
                    <label for="fullname" class="col-sm-2 control-label">Tên thật</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Tên thật của bạn" value="' . ZenView::$D['user']['fullname'] . '"/>
                      ' . ZenView::get_message('fullname', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="status" class="col-sm-2 control-label">Trạng thái</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="status" name="status" placeholder="Dòng trạng thái" value="' . ZenView::$D['user']['status'] . '"/>
                      ' . ZenView::get_message('status', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="avatar" class="col-sm-2 control-label">Avatar</label>
                    <div class="col-sm-2">
                        <img src="' . ZenView::$D['user']['full_avatar'] . '?' . time() . '" alt="Avatar" class="img-thumbnail img-responsive"/>
                    </div>
                    <div class="col-sm-6">
                        <input type="file" class="form-control input-sm" id="avatar" name="avatar"/><br/>
                        <input type="text" class="form-control input-sm" id="avatar-url" name="avatar-url" placeholder="Url hình ảnh"/>
                        ' . ZenView::get_message('avatar', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="sex" class="col-sm-2 control-label">Giới tính</label>
                    <div class="col-sm-8">
                        <div class="radio">
                          <label for="sex-male">
                            <input type="radio" name="sex" id="sex-male" value="male" ' . (ZenView::$D['user']['sex']=='male'?'checked':'') . '/> Nam
                          </label>
                        </div>
                        <div class="radio">
                            <label for="sex-female">
                                <input type="radio" name="sex" id="sex-female" value="female" ' . (ZenView::$D['user']['sex']=='female'?'checked':'') . '/> Nữ
                            </label>
                        </div>
                        <div class="radio">
                            <label for="sex-">
                                <input type="radio" name="sex" id="sex-" ' . (ZenView::$D['user']['sex']==''?'checked':'') . '/> Không xác định
                            </label>
                        </div>
                        ' . ZenView::get_message('sex', '<p class="text-danger help-block">%s</p>') . '
                    </div>
                </div>';
                echo '<div class="form-group">
                    <label for="birth" class="col-sm-2 control-label">Ngày sinh</label>
                    <div class="col-sm-8">';
                echo '<div class="row">';
                echo '<div class="col-sm-4">';
                echo '<label>Ngày
                    <select class="form-control" name="birth-day">';
                for ($i=ZenView::$D['birth_config']['day']['start']; $i<=ZenView::$D['birth_config']['day']['end']; $i++) {
                    echo '<option value="' . $i . '" ' . (ZenView::$D['user']['birth'] ? (date('d', ZenView::$D['user']['birth'])-$i==0?'selected':''):'') . '>' . $i . '</option>';
                }
                echo '</select></label>';
                echo '</div>';
                echo '<div class="col-sm-4">';
                echo '<label>Tháng
                    <select class="form-control" name="birth-month">';
                for ($i=ZenView::$D['birth_config']['month']['start']; $i<=ZenView::$D['birth_config']['month']['end']; $i++) {
                    echo '<option value="' . $i . '" ' . (ZenView::$D['user']['birth'] ? (date('m', ZenView::$D['user']['birth'])-$i==0?'selected':''):'') . '>' . $i . '</option>';
                }
                echo '</select></label>';
                echo '</div>';
                echo '<div class="col-sm-4">';
                echo '<label>Năm
                    <select class="form-control" name="birth-year">';
                for ($i=ZenView::$D['birth_config']['year']['start']; $i<=ZenView::$D['birth_config']['year']['end']; $i++) {
                    echo '<option value="' . $i . '" ' . (ZenView::$D['user']['birth'] ? (date('Y', ZenView::$D['user']['birth'])-$i==0?'selected':''):'') . '>' . $i . '</option>';
                }
                echo '</select></label>';
                echo '</div>';
                echo '</div>';//end row
                echo ZenView::get_message('birth', '<p class="text-danger help-block">%s</p>');
                echo '</div></div>';
                echo '<div class="form-group">
                <label for="sign" class="col-sm-2 control-label">Chữ kí</label>
                <div class="col-sm-8">
                  <textarea class="form-control" id="sign" name="sign" placeholder="Chữ kí...">' . ZenView::$D['user']['sign'] . '</textarea>
                  ' . ZenView::get_message('sign', '<p class="text-danger help-block">%s</p>') . '
                </div>
                </div>';
                echo '<div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-8">
                    <input type="submit" name="submit-save" class="btn btn-success" value="Lưu thay đổi"/>
                </div>
                </div>';
                echo '</form>';
            });
        });
        ZenView::col_item(3, function() {

            $objMenu = ZenView::get_menu('main');
            ZenView::block($objMenu['name'], function() use ($objMenu) {
                echo '<ul class="list-group">';
                foreach ($objMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });
        });
    });
});