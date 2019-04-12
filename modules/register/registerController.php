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

Class registerController Extends ZenController
{
    function index()
    {
        /**
         * set page title
         */
        ZenView::set_title('Đăng kí thành viên');
        ZenView::noindex();

        /**
         * get module config from database
         */
        $registerConfig = $this->config->getModuleConfig('register');

        /**
         * get hook account
         */
        $accHook = $this->hook->get('account');
        /**
         * get account model
         */
        $accModel = $this->model->get('account');

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

        /**
         * get hook
         */
        $this->hook->get('register');
        /**
         * get register model
         */
        $model = $this->model->get('register');

        if (isset($_POST['submit-register'])) {
            if ($security->check_token('token_register')) {
                if (empty($_POST['username'])) {
                    ZenView::set_error('Bạn chưa nhập tài khoản');
                } else {
                    /**
                     * valid_data_post_username hook*
                     */
                    $username = $accHook->loader('valid_data_post_username', $_POST['username']);

                    if (ZenView::render_msg('valid_data_post_username')) {
                        /**
                         * valid_data_post_nickname hook*
                         */
                        $nickname = $accHook->loader('valid_data_post_nickname', $_POST['username']);

                        if (ZenView::render_msg('valid_data_post_nickname')) {
                            if (empty($_POST['password']) || empty($_POST['repassword'])) {
                                ZenView::set_error('Bạn chưa nhập mật khẩu');
                            } else {
                                /**
                                 * valid_data_post_password hook*
                                 */
                                $password = $accHook->loader('valid_data_post_password', $_POST['password']);
                                if (ZenView::render_msg('valid_data_post_password')) {
                                    if ($password != $_POST['repassword']) {
                                        ZenView::set_error('Xác nhận mật khẩu không chính xác');
                                    } else {
                                        $email_ok = true;
                                        if ($registerConfig['register_turn_on_authorized_email']) {
                                            if (empty($_POST['email'])) {
                                                ZenView::set_error('Bạn chưa nhập email');
                                            } else {
                                                /**
                                                 * valid_data_post_email hook*
                                                 */
                                                $email = $accHook->loader('valid_data_post_email', $_POST['email']);
                                                if (!ZenView::render_msg('valid_data_post_username')) {
                                                    $email_ok = false;
                                                }
                                            }
                                        }
                                        if ($email_ok) {
                                            if (!$security->check_token('captcha_code', 'POST')) {
                                                ZenView::set_error('Mã xác nhận không chính xác');
                                            } else {
                                                $data_user['nickname'] = $nickname;
                                                $data_user['username'] = $username;
                                                $data_user['password'] = $accModel->encrypt_password($password);
                                                $data_user['email'] = isset($email) ? $email : '';
                                                $data_user['perm'] = $registerConfig['register_turn_on_authorized_email'] ? PERM_USER_NEED_ACTIVE : PERM_USER_ACTIVED;
                                                $data_user['last_ip'] = client_ip();

                                                $username_exists = false;
                                                if ($accModel->user_is_exists($data_user['username'])) {
                                                    ZenView::set_error('Tài khoản này đã có người sử dụng');
                                                    $username_exists = true;
                                                }

                                                $email_exists = false;
                                                if ($data_user['email']) {
                                                    if ($accModel->email_is_exists($data_user['email'])) {
                                                        ZenView::set_error('Email này đã có người sử dụng');
                                                        $email_exists = true;
                                                    }
                                                }

                                                if (!$username_exists && !$email_exists) {
                                                    $code_confirm = randStr(10, 'num');
                                                    $code = md5($code_confirm);
                                                    /**
                                                     * insert new user
                                                     */
                                                    if (!$accModel->insert_user($data_user)) {
                                                        ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại');
                                                    } else {
                                                        $userID = $accModel->insert_id();
                                                        set_security_code($userID, 'register', $code);
                                                        $data_user = $accModel->get_user_data($userID);

                                                        /**
                                                         * load ZenSmarty library
                                                         */
                                                        $smarty = load_library('ZenSmarty');
                                                        $smarty->set(array(
                                                            'reg_nickname'  => $data_user['nickname'],
                                                            'reg_username'  => $data_user['username'],
                                                            'reg_email'     => $data_user['email'],
                                                        ));


                                                        if ($registerConfig['register_turn_on_authorized_email']) {
                                                            /**
                                                             * send mail active to account email
                                                             */
                                                            if (!$accModel->send_mail_register($data_user)) {
                                                                /**
                                                                 * msg_register_success_send_fail config*
                                                                 */
                                                                $msg_send_fail = $registerConfig['msg_register_success_send_fail'] ? $registerConfig['msg_register_success_send_fail'] : $accModel->defaultConfig['msg_register_success_send_fail'];
                                                                $msg_send_fail = $smarty->fetch_text($msg_send_fail);
                                                                ZenView::set_error($msg_send_fail);
                                                            } else {
                                                                /**
                                                                 * msg_register_success_send_success config*
                                                                 */
                                                                $msg_send_success = $registerConfig['msg_register_success_send_success'] ? $registerConfig['msg_register_success_send_success'] : $accModel->defaultConfig['msg_register_success_send_success'];
                                                                $msg_send_success = $smarty->fetch_text($msg_send_success);
                                                                ZenView::set_success($msg_send_success);
                                                            }
                                                        } else {
                                                            /**
                                                             * msg_register_success config*
                                                             */
                                                            $msg_register_success = $registerConfig['msg_register_success'] ? $registerConfig['msg_register_success'] : $accModel->defaultConfig['msg_register_success'];
                                                            $msg_register_success = $smarty->fetch_text($msg_register_success);
                                                            ZenView::set_success($msg_register_success);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
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
