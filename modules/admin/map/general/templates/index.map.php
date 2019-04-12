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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
$stick_action = function($template) {
    if (ZenView::$D['templates'][$template]['actions']['setting']) {
        echo '<a href="' . ZenView::$D['templates'][$template]['actions']['setting']['full_url'] . '" class="btn btn-sm btn-default">
                    <i class="' . ZenView::$D['templates'][$template]['actions']['setting']['icon'] . '"></i>
                </a>';
    }
    if (ZenView::$D['templates'][$template]['actions']['widget']) {
        echo ' <a href="' . ZenView::$D['templates'][$template]['actions']['widget']['full_url'] . '" class="btn btn-sm btn-default">
                    <i class="' . ZenView::$D['templates'][$template]['actions']['widget']['icon'] . '"></i>
                </a>';
    }
};

ZenView::section('Cài đặt giao diện', function() use ($stick_action) {
    ZenView::display_breadcrumb();
    ZenView::block('Cài đặt chung', function() use ($stick_action) {
        ZenView::padded(function() use ($stick_action) {
            echo '<form role="form" class="form-horizontal fill-up validatable" method="POST">';
            ZenView::display_message('general-template-setting');
            echo('<div class="form-group">
            <label for="Mobile" class="col-lg-2 control-label">Giao diện điện thoại</label>
            <div class="col-lg-7">
            <select name="Mobile" id="Mobile" class="uniform">
                <option value="" ' .(empty(ZenView::$D['current']['Mobile'])? 'selected' : '') . '>Chưa chọn</option>');
            foreach (ZenView::$D['templates'] as $key => $temp) {
                echo '<option value="' . $key . '" ' . (ZenView::$D['current']['Mobile'] == $key ? 'selected' : '') . '>' . $temp["name"] . '</option>';
            }
            echo '</select></div>';
            echo '<div class="col-lg-2">';
            $stick_action(ZenView::$D['current']['Mobile']);
            echo '</div>';
            echo '</div>';
            echo('<div class="form-group">
            <label for="other" class="col-lg-2 control-label">Giao diện máy tính và các thiết bị khác</label>
            <div class="col-lg-7">
            <select name="other" id="other" class="uniform">
                <option value="" ' .(empty(ZenView::$D['current']['other'])? 'selected' : '') . '>Chưa chọn</option>');
            foreach (ZenView::$D['templates'] as $key => $temp) {
                echo '<option value="' . $key . '" ' . (ZenView::$D['current']['other'] == $key ? 'selected' : '') . '>' . $temp["name"] . '</option>';
            }
            echo '</select></div>';
            echo '<div class="col-lg-2">';
            $stick_action(ZenView::$D['current']['other']);
            echo '</div>';
            echo '</div>';
            echo'<div class="form-group">
            <div class="col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-general" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>';
            echo '</form>';
        });
    });
    ZenView::block('Theo hệ điều hành', function() use ($stick_action) {
        ZenView::padded(function() use ($stick_action) {
            echo '<form role="form" class="form-horizontal fill-up validatable" method="POST">';
            ZenView::display_message('os-template-setting');
            foreach (ZenView::$D['device_os'] as $os) {
                echo('<div class="form-group">
                <label for="' . $os . '" class="col-lg-2 control-label">' . $os . '</label>
                <div class="col-lg-7">
                <select name="' . $os . '" id="' . $os . '" class="uniform">
                    <option value="" ' .(empty(ZenView::$D['current'][$os])? 'selected' : '') . '>Chưa chọn</option>');
                foreach (ZenView::$D['templates'] as $key => $temp) {
                    echo '<option value="' . $key . '" ' . (ZenView::$D['current'][$os] == $key ? 'selected' : '') . '>' . $temp["name"] . '</option>';
                }
                echo '</select></div>';
                echo '<div class="col-lg-2">';
                $stick_action(ZenView::$D['current'][$os]);
                echo '</div>';
                echo '</div>';
            }
            echo('<div class="form-group">
            <div class="col-lg-9 col-lg-offset-2">
              <button type="submit" name="submit-os" class="btn btn-primary">Lưu thay đổi</button>
            </div>
            </div>');
            echo '</form>';
        });
    });
}, array('after' => $menu));