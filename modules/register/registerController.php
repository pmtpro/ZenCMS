<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

Class registerController Extends ZenController
{

    function index()
    {
        global $system_config;

        $data['page_title'] = 'Đăng kí thành viên';

        /**
         * turn off register
         */
        if (get_config('register_turn_off')) {

            $data['register_message'] = h_decode(get_config('register_message'));
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

        $username_min_len = $system_config['user']['username']['min_length'];
        $username_max_len = $system_config['user']['username']['max_length'];

        $password_min_len = $system_config['user']['password']['min_length'];
        $password_max_len = $system_config['user']['password']['max_length'];

        if (isset($_POST['sub'])) {

            if ($security->check_token('token_register')) {

                $password_is_ok = FALSE;

                if (empty($_POST['username'])) {

                    $data['errors'][] = 'Bạn chưa nhập tài khoản';

                } else {

                    if (!$validation->isValid('username', $_POST['username'])) {

                        $data['errors'][] = 'Tên tài khoản chỉ bao gồm a-z 0-9 @ _ - .';

                    } else {

                        if (strlen($_POST['username']) < $username_min_len or strlen($_POST['username']) > $username_max_len) {

                            $data['errors'][] = 'Tên tài khoản chỉ được phép trong khoảng ' . $username_min_len . ' đến ' . $username_max_len . ' kí tự';
                        }
                    }
                }

                if (empty($_POST['password'])) {

                    $data['errors'][] = 'Bạn chưa nhập mật khẩu';
                } else {

                    if (strlen($_POST['password']) < $password_min_len or strlen($_POST['password']) > $password_max_len) {

                        $data['errors'][] = 'Mật khẩu chỉ được phép trong khoảng ' . $password_min_len . ' đến ' . $password_max_len . ' kí tự';
                    } else {

                        $password_is_ok = true;
                    }
                }

                if (!empty($_POST['repassword'])) {

                    if ($password_is_ok) {

                        if ($_POST['password'] != $_POST['repassword']) {

                            $data['errors'][] = 'Xác nhận mật khẩu không chính xác';
                        }
                    }
                } else {

                    $data['errors'][] = 'Xác nhận mật khẩu không chính xác';
                }

                if (get_config('register_turn_on_authorized_email')) {

                    if (empty($_POST['email'])) {

                        $data['errors'][] = 'Bạn chưa nhập email';

                    } else {

                        if (!$validation->isValid('email', $_POST['email'])) {

                            $data['errors'][] = 'Định dạng email không chính xác';
                        }
                    }
                }

                if (!isset($data['errors'])) {

                    if (!$security->check_token('captcha_code', 'POST')) {

                        $data['errors'][] = 'Mã xác nhận không chính xác';
                    }
                }
                $data_user['nickname'] = h($_POST['username']);
                $data_user['username'] = h(strtolower($_POST['username']));
                $data_user['password'] = md5(md5($_POST['password']));
                if (isset($_POST['email'])) {

                    $data_user['email'] = h($_POST['email']);
                }

                if (get_config('register_turn_on_authorized_email')) {

                    $data_user['perm'] = PERM_USER_NEED_ACTIVE;
                } else {

                    $data_user['perm'] = PERM_USER_ACTIVED;
                }

                $data_user['last_ip'] = client_ip();

                if (!isset($data['errors'])) {

                    if ($model->account_exists($data_user, 'username')) {

                        $data['errors'][] = 'Tài khoản này đã có người sử dụng';
                    }

                    if (get_config('register_turn_on_authorized_email')) {

                        if ($model->account_exists($data_user, 'email')) {

                            $data['errors'][] = 'Email này đã có người sử dụng';
                        }
                    }
                }
                if (!isset($data['errors'])) {

                    $code_confirm = rand_str(10, 'num');

                    $code = md5($code_confirm);

                    $reg = $model->register_user($data_user);

                    if ($reg) {

                        $khid = $model->insert_id();

                        set_security_code($khid, 'register', $code);

                        $data_user = $this->model->_get_user_data($khid);

                        if (get_config('register_turn_on_authorized_email')) {


                            $data['success'] = 'Chúc mừng bạn đã đăng kí thành công!<br/>
                        Chúng tôi đã gửi mail xác nhận đến <b>' . $data_user['email'] . '</b> để kích hoạt tài khoản!<br/>
                        Vui lòng check mail và làm theo hướng dẫn!<br/>
                        Nếu không nhận được mail kích hoạt. Bạn nên kiểm tra là hòm thư rác hoặc vui lòng <b><a href="' . _HOME . '/login">đăng nhập</a></b> và thực hiện gửi lại mail kích hoạt';

                            $this->hook->loader('send_mail', $data_user, true);

                        } else {

                            $data['success'] = 'Đăng kí thành công! Hãy <b><a href="' . _HOME . '/login">đăng nhập</a></b> và tham gia cũng chúng tôi';
                        }
                    }
                }
            }
        }

        $data['token_register'] = $security->get_token('token_register');
        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = _HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';

        $this->view->data = $data;
        $this->view->show('register');
    }

    public function settings($app = array('index'))
    {
        load_apps(__MODULES_PATH . '/register/apps/settings', $app);
    }

}