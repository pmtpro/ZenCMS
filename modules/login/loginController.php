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

Class loginController Extends ZenController
{
    function index()
    {
        /**
         * set the page title
         */
        ZenView::set_title('Đăng nhập thành viên');
        $security = load_library('security');
        $model = $this->model->get('login');
        $data_login['limit_login'] = FALSE;
        $data_login['confirm_login_fail'] = FALSE;
        $data['limit_login'] = FALSE;

        /**
         * if user is logged, redirect to home page
         */
        if (isset($this->user['id'])) {
            redirect(HOME);
            exit;
        }
        if (isset($_SESSION['session_num_errors_login'])) {
            if ($_SESSION['session_num_errors_login'] >= 3) {
                $data_login['limit_login'] = TRUE;
            }
        } else $_SESSION['session_num_errors_login'] = 0;

        /**
         * check login
         */
        if (isset($_POST['submit-login'])) {
            if ($security->check_token('token_login')) {
                $data_login['username'] = $security->cleanXSS($_POST['username']);
                $data_login['password'] = $_POST['password'];
                if ($data_login['limit_login'] == TRUE) {
                    if ($security->check_token('captcha_code') == FALSE) {
                        $data_login['confirm_login_fail'] = TRUE;
                    }
                }
                if ($model->check_login($data_login) == FALSE or $data_login['confirm_login_fail'] == TRUE) {
                    if (!isset($_SESSION['session_num_errors_login'])) {
                        $_SESSION['session_num_errors_login'] = 0;
                    }
                    $_SESSION['session_num_errors_login'] = (int)$_SESSION['session_num_errors_login'];
                    $_SESSION['session_num_errors_login']++;
                    if ($_SESSION['session_num_errors_login'] >= 3) {
                        $data_login['limit_login'] = TRUE;
                    }
                    if ($data_login['confirm_login_fail'] == TRUE) {
                        ZenView::set_error('Sai mã xác nhận');
                    } else ZenView::set_error('Sai tên đăng nhập hoặc mật khẩu');
                } else {
                    $_SESSION['session_num_errors_login'] = 0;
                    $update = $model->update_login(_gen_login_token());
                    if ($update) {
                        $data_user = $model->get_data_user();
                        if (!empty($_POST['remember_me'])) {
                            $save_cookie = 3600 * 24 * 365;
                        } else $save_cookie = 0;
                        _gen_session_login($data_user, $save_cookie);
                        ZenView::set_success('Đăng nhập thành công!', ZPUBLIC, HOME);
                    }
                }
            }
        }

        $data['token_login'] = $security->get_token('token_login');
        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';
        if ($data_login['limit_login'] == TRUE) {
            $data['limit_login'] = TRUE;
        }
        $this->view->data = $data;
        $this->view->show('login');
    }

}
