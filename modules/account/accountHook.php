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

class accountHook extends ZenHook
{
    function send_mail_forgot_password($data)
    {
        $sent = send_mail($data['email'],
            'Lấy lại mật khẩu',
            'Bạn hay ai đó đã muốn lấy lại mật khẩu trên <a href="' . HOME . '">' . HOME . '</a> cho tài khoản <b>' . $data['nickname'] . '</b>.<br/>
                        Nếu người đó là bạn vui lòng click link dưới để reset mật khẩu! Nếu không bạn hãy bỏ qua thư này <br/>
                        <a href="' . HOME . '/account/forgot_password/reset_password/' . $data['id'] . '/' . $data['security_code']['forgot_password']['code'] . '">' . HOME . '/account/forgot_password/reset_password/' . $data['id'] . '/' . $data['security_code']['forgot_password']['code'] . '</a>');
        return $sent;
    }
    public function valid_data_post_fullname($data) {
        $sign_len = 30;
        if (strlen($data)>$sign_len) {
            ZenView::set_error('Tên đầy đủ không được quá ' . $sign_len . ' kí tự', 'fullname');
        }
        return $data;
    }
    public function valid_data_post_status($data) {
        $sign_len = 20;
        if (strlen($data)>$sign_len) {
            ZenView::set_error('Trạng thái không được quá ' . $sign_len . ' kí tự', 'status');
        }
        return $data;
    }
    public function valid_data_post_sex($data) {
        if (!in_array($data, array('male', 'female', ''))) {
            ZenView::set_error('Giới tính không chính xác', 'sex');
        }
        return $data;
    }
    public function valid_data_post_sign($data) {
        $sign_len = 100;
        if (strlen($data)>$sign_len) {
            ZenView::set_error('Chữ kí không được quá ' . $sign_len . ' kí tự', 'sign');
        }
        return $data;
    }

    public function message_before_display($msg) {
        $bbCode = load_library('bbcode');
        return $bbCode->parse(parse_smile($msg));
    }

    public function message_sub_before_display($msg) {
        return $msg;
    }
}