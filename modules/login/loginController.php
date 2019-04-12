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

Class loginController Extends ZenController
{
    private $captcha_key;

    function index()
    {

        $security = load_library('security');
        $model = $this->model->get('login');


        $data_login['limit_login'] = FALSE;
        $data_login['confirm_login_fail'] = FALSE;

        $data['limit_login'] = FALSE;
        $data['page_title'] = 'Đăng nhập thành viên';

        if (isset($this->user['id'])) {
            redirect(_HOME);
            $this->view->data = $data;
            $this->view->show('login');
            return false;
        }

        if (isset($_SESSION['session_num_errors_login'])) {

            if ($_SESSION['session_num_errors_login'] >= 3) {
                $data_login['limit_login'] = TRUE;
            }
        } else {
            $_SESSION['session_num_errors_login'] = 0;
        }

        if (isset($_POST['sub'])) {

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

                    (int)$_SESSION['session_num_errors_login'];

                    $_SESSION['session_num_errors_login']++;


                    if ($_SESSION['session_num_errors_login'] >= 3) {
                        $data_login['limit_login'] = TRUE;
                    }

                    if ($data_login['confirm_login_fail'] == TRUE) {
                        $data['errors'][] = 'Sai mã xác nhận';
                    } else {
                        $data['errors'][] = 'Sai tên đăng nhập hoặc mật khẩu';
                    }
                } else {

                    $_SESSION['session_num_errors_login'] = 0;

                    $zen_token = base64_encode(md5($data_login['password']));

                    $ss_zen_login = base64_encode($_SERVER['HTTP_USER_AGENT'] . '-' . $_SERVER['REMOTE_ADDR']);

                    $update = $model->update_login($ss_zen_login);

                    if ($update) {

                        $data_user = $model->get_data_user();

                        $data_hash_id = base64_encode($data_user['id'] + ZEN_WORKID);

                        if (isset($_POST['remember_me'])) {
                            setcookie('ck_zen_token', $zen_token, time() + 3600 * 24 * 365, "/");
                            setcookie('ck_user_id', $data_hash_id, time() + 3600 * 24 * 365, "/");
                        }

                        $_SESSION['ss_user_id'] = $data_hash_id;
                        $_SESSION['ss_zen_token'] = $zen_token;

                        $data['success'] = wait_redirect(_HOME, 'Đăng nhập thành công!<br/>Bạn sẽ được chuyển đến trang chủ trong vòng {s} nữa', 3);
                    }
                }
            }
        }

        $data['token_login'] = $security->get_token('token_login');
        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = _HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';
        if ($data_login['limit_login'] == TRUE) {
            $data['limit_login'] = TRUE;
        }

        $this->view->data = $data;
        $this->view->show('login');
    }

}