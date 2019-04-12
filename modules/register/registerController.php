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

Class registerController Extends ZenController
{
    function index()
    {
        global $zen;
        /**
         * set page title
         */
        ZenView::set_title('Đăng kí thành viên');

        /**
         * get module config from database
         */
        $registerConfig = $this->config->getModuleConfig('register');

        /**
         * turn off register
         */
        if ($registerConfig['register_turn_off']) {
            $data['register_message'] = h_decode($registerConfig['register_message']);
            $this->view->data = $data;
            $this->view->show('register/stop');
            return;
        }

        /**
         * load libraries
         */
        $security = load_library('security');
        $validation = load_library('validation');
        /**
         * get hook
         */
        $this->hook->get('register');
        /**
         * get register model
         */
        $model = $this->model->get('register');
        
        $username_min_len = $zen['config']['user']['username']['min_length'];
        $username_max_len = $zen['config']['user']['username']['max_length'];
        $password_min_len = $zen['config']['user']['password']['min_length'];
        $password_max_len = $zen['config']['user']['password']['max_length'];

        if (isset($_POST['submit-register'])) {
            if ($security->check_token('token_register')) {
                $password_is_ok = FALSE;
                if (empty($_POST['username'])) {
                    ZenView::set_error('Bạn chưa nhập tài khoản');
                } else {
                    if (!$validation->isValid('username', $_POST['username'])) {
                        ZenView::set_error('Tên tài khoản chỉ bao gồm a-z 0-9 @ _ - .');
                    } else {
                        if (strlen($_POST['username']) < $username_min_len or strlen($_POST['username']) > $username_max_len) {
                            ZenView::set_error('Tên tài khoản chỉ được phép trong khoảng ' . $username_min_len . ' đến ' . $username_max_len . ' kí tự');
                        }
                    }
                }

                if (empty($_POST['password'])) {
                    ZenView::set_error('Bạn chưa nhập mật khẩu');
                } else {
                    if (strlen($_POST['password']) < $password_min_len or strlen($_POST['password']) > $password_max_len) {
                        ZenView::set_error('Mật khẩu chỉ được phép trong khoảng ' . $password_min_len . ' đến ' . $password_max_len . ' kí tự');
                    } else $password_is_ok = true;
                }

                if (!empty($_POST['repassword'])) {
                    if ($password_is_ok) {
                        if ($_POST['password'] != $_POST['repassword']) {
                            ZenView::set_error('Xác nhận mật khẩu không chính xác');
                        }
                    }
                } else ZenView::set_error('Xác nhận mật khẩu không chính xác');

                if ($registerConfig['register_turn_on_authorized_email']) {
                    if (empty($_POST['email'])) {
                        ZenView::set_error('Bạn chưa nhập email');
                    } else {
                        if (!$validation->isValid('email', $_POST['email'])) {
                            ZenView::set_error('Định dạng email không chính xác');
                        }
                    }
                }

                if (!isset($data['errors'])) {
                    if (!$security->check_token('captcha_code', 'POST')) {
                        ZenView::set_error('Mã xác nhận không chính xác');
                    }
                }

                $data_user['nickname'] = h($security->cleanXSS($_POST['username']));
                $data_user['username'] = h($security->cleanXSS(strtolower($_POST['username'])));
                $data_user['password'] = md5(md5($_POST['password']));
                if (isset($_POST['email'])) {
                    $data_user['email'] = h($security->cleanXSS($_POST['email']));
                }
                if ($registerConfig['register_turn_on_authorized_email']) {
                    $data_user['perm'] = PERM_USER_NEED_ACTIVE;
                } else {
                    $data_user['perm'] = PERM_USER_ACTIVED;
                }
                $data_user['last_ip'] = client_ip();

                if (ZenView::is_success()) {
                    if ($model->account_exists($data_user, 'username')) {
                        ZenView::set_error('Tài khoản này đã có người sử dụng');
                    }
                    if ($registerConfig['register_turn_on_authorized_email']) {
                        if ($model->account_exists($data_user, 'email')) {
                            ZenView::set_error('Email này đã có người sử dụng');
                        }
                    }
                }
                if (ZenView::is_success()) {
                    $code_confirm = randStr(10, 'num');
                    $code = md5($code_confirm);
                    $reg = $model->register_user($data_user);
                    if ($reg) {
                        $khid = $model->insert_id();
                        set_security_code($khid, 'register', $code);
                        $data_user = $this->model->get('account')->get_user_data($khid);
                        if ($registerConfig['register_turn_on_authorized_email']) {
                            ZenView::set_success('Chúc mừng bạn đã đăng kí thành công!<br/>
                        Chúng tôi đã gửi mail xác nhận đến <b>' . $data_user['email'] . '</b> để kích hoạt tài khoản!<br/>
                        Vui lòng check mail và làm theo hướng dẫn!<br/>
                        Nếu không nhận được mail kích hoạt. Bạn nên kiểm tra là hòm thư rác hoặc vui lòng <b><a href="' . HOME . '/login">đăng nhập</a></b> và thực hiện gửi lại mail kích hoạt');
                            $this->hook->loader('send_mail', $data_user, true);
                        } else {
                            ZenView::set_success('Đăng kí thành công! Hãy <b><a href="' . HOME . '/login">đăng nhập</a></b> và tham gia cũng chúng tôi');
                        }
                    }
                }
            }
        }

        $data['token_register'] = $security->get_token('token_register');
        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';
        $this->view->data = $data;
        $this->view->show('register');
    }

    public function settings($app = array('index')) {
        load_apps('register/apps/settings', $app);
    }
}
