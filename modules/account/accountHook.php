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

    public function valid_data_post_nickname($nickname) {
        return h($nickname);
    }

    public function valid_data_post_username($username) {
        global $registry;
        $validation = load_library('validation');
        $security = load_library('security');
        if (!$validation->isValid('username', $username)) {
            ZenView::log_msg('valid_data_post_username', 'error', 'Tên tài khoản chỉ bao gồm a-z 0-9 @ _ -');
        } else {
            $setting = $registry->settings->get('account');
            $accConfig = $setting->config;
            $username_min_len = $accConfig['username']['min_length'];
            $username_max_len = $accConfig['username']['max_length'];
            $len = strlen($username);
            if ($len < $username_min_len or $len > $username_max_len) {
                ZenView::log_msg('valid_data_post_username', 'error', 'Tên tài khoản chỉ được phép trong khoảng ' . $username_min_len . ' đến ' . $username_max_len . ' kí tự');
            }
        }
        return h($security->cleanXSS(strtolower($username)));
    }

    public function valid_data_post_password($password) {
        global $registry;
        $setting = $registry->settings->get('account');
        $accConfig = $setting->config;
        $password_min_len = $accConfig['password']['min_length'];
        $password_max_len = $accConfig['password']['max_length'];
        $len = strlen($password);
        if ($len < $password_min_len or $len > $password_max_len) {
            ZenView::log_msg('valid_data_post_username', 'error', 'Mật khẩu chỉ được phép trong khoảng ' . $password_min_len . ' đến ' . $password_max_len . ' kí tự');
        }
        return $password;
    }

    public function valid_data_post_email($email) {
        $validation = load_library('validation');
        if (!$validation->isValid('email', $email)) {
            ZenView::log_msg('valid_data_post_username', 'error', 'Định dạng email không chính xác');
        }
        return h($email);
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
        return parse_smile($bbCode->parse($msg));
    }

    public function message_sub_before_display($msg) {
        return $msg;
    }
}