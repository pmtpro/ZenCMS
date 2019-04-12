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

Class accountController Extends ZenController
{

    function index()
    {
        load_helper('user');
        $data['page_title'] = 'Trang cá nhân';
        $data['user'] = $this->user;
        $this->view->data = $data;
        $this->view->show('account/index');
    }

    function wall($arg = array()) {

        load_helper('user');
        $secutiry = load_library('security');
        $model = $this->model->get('account');
        $permission = load_library('permission');
        $permission->set_user($this->user);
        $user = $this->user;


        if (isset($arg[0])) {
            $u = $secutiry->cleanXSS($arg[0]);
        } else {
            $u = 0;
        }

        $wall = $model->get_user_data($u);

        if (empty($wall)) {

            show_error(3000);
        }

        if ($user['id'] != $wall['id']) {
            $data['actions'][] = url(_HOME.'/account/messages/compose/'.$wall['username'], 'Gửi tin nhắn');
        } else {
            $data['actions'] = array();
        }
        if (!$permission->is_lower_levels_of($wall['id'])) {

            $data['actions'][] = url(_HOME.'/account/manager/permission/'.$wall['username'], 'Quản lí thành viên này');
        }
        $data['page_title'] = $wall['nickname'];
        $data['wall'] = $wall;
        $data['user'] = $user;
        $this->view->data = $data;
        $this->view->show('account/wall');
    }

    function forgot_password($arg = array())
    {
        $model = $this->model->get('account');
        $this->hook->get('account');
        $security = load_library('security');

        $act = '';
        $uid = 0;
        $code = '';

        if (isset($arg[0])) {
            $act = $security->cleanXSS($arg[0]);
        }
        if (isset($arg[1])) {
            $uid = $security->removeSQLI($arg[1]);
        }
        if (isset($arg[2])) {
            $code = $security->cleanXSS($arg[2]);
        }

        switch ($act) {

            default:

                if (isset($_POST['sub'])) {

                    $username = h($_POST['username']);
                    $data_user = $model->get_user_data($username);

                    if (!$data_user) {

                        $data['errors'] = 'Không tồn tại tài khoản này';

                    } else {

                        if ($data_user['perm'] == 'user_need_active') {

                            $data['notices'] = 'Xin lỗi tài khoản này chưa kích hoạt email. Nên hệ thống không thể lấy lại mật khẩu cho tài khoản này';

                        } elseif ($data_user['perm'] == 'user_lock') {

                            $data['notices'] = 'Tài khoản đang trong tình trạng cấm!';

                        } else {

                            $code = md5(rand_str());

                            if (!set_security_code($data_user['id'], 'forgot_password', $code, '2d')) {

                                $data['notices'][] = 'Xảy ra lỗi';

                            } else {

                                $data_user = $model->get_user_data($username);

                                $ok = $this->hook->loader('send_mail_forgot_password', $data_user);

                                if ($ok) {
                                    $data['success'] = 'Hệ thống đã gửi thư đến email được đăng kí trên tài khoản <b>' . $data_user['nickname'] . '</b>. <br/>
                    Vui lòng check mail và làm theo hướng dẫn để lấy lại mật khẩu';
                                } else {
                                    $data['notices'][] = 'Không thể gửi mail lấy lại mật khẩu<br/>' . get_global_msg('send_mail');
                                }
                            }
                        }
                    }

                }

                $data['page_title'] = 'Quên mật khẩu';
                $this->view->data = $data;
                $this->view->show('account/forgot_password');
                break;

            case 'reset_password':
                $data['page_title'] = 'Khôi phục mật khẩu';
                if (!empty($this->user)) {
                    $data['notices'][] = 'Trước tiên bạn hãy <b><a href="' . _HOME . '/logout" target="_blank">thoát</a></b> tài khoản hiện tại. Sau đó thực hiện lại';
                }
                if (empty($uid)) {
                    redirect(_HOME);
                }

                $user = $model->get_user_data($uid);
                $data['user'] = $user;

                if (!$user) {
                    $data['errors'][] = 'Lỗi dữ liệu';
                } else {

                    if (!still_valid_security_code($uid, 'forgot_password') || $user['security_code']['forgot_password']['code'] != $code) {

                        $data['errors'][] = 'Lỗi dữ liệu';

                    } else {

                        if (isset($_POST['sub_reset_password'])) {

                            if ($security->check_token('token_continous')) {

                                $new_password = rand_str(8);

                                $encode_pass = md5(md5($new_password));

                                $new_security_forgot_password = md5(rand());

                                $update['password'] = $encode_pass;

                                if (set_security_code($uid, 'forgot_password', $new_security_forgot_password, '2d')) {

                                    if (!$model->update_user($uid, $update)) {

                                        $data['errors'] = 'Đã xảy ra lỗi';

                                    } else {

                                        $data['new_password'] = $new_password;
                                        $data['success'] = 'Thành công';

                                    }
                                }
                            }
                        }
                    }

                }

                $data['token_continous'] = $security->get_token('token_continous');
                $this->view->data = $data;
                $this->view->show('account/forgot_password/reset_password');
                break;
        }
    }

    function active_account($arg = array())
    {

        $model = $this->model->get('account');
        $security = load_library('security');
        $data['page_title'] = 'Kích hoạt tài khoản';

        $uid = 0;
        $code = '';
        if (isset($arg[0])) {
            $uid = $security->removeSQLI($arg[0]);
        }
        if (isset($arg[1])) {
            $code = $security->cleanXSS($arg[1]);
        }

        if (!empty($this->user)) {
            $data['notices'][] = 'Trước tiên bạn hãy <b><a href="' . _HOME . '/logout">thoát</a></b> tài khoản hiện tại và thực hiện lại';
            $this->view->data = $data;
            $this->view->show('account/active_account');
            return;
        }

        if (empty($uid) || empty($code)) {
            redirect(_HOME);
        }

        $user = $model->get_user_data($uid);

        if (empty($user)) {
            $data['errors'][] = 'Lỗi dữ liệu';
            $this->view->data = $data;
            $this->view->show('account/active_account');
            return;
        }

        if (!still_valid_security_code($uid, 'register') || $user['security_code']['register']['code'] != $code) {
            $data['errors'][] = 'Lỗi dữ liệu';
            $this->view->data = $data;
            $this->view->show('account/active_account');
            return;
        }

        $new_code = md5(rand_str());

        $ok = set_security_code($uid, 'register', $new_code);
        if (!$ok) {
            $data['errors'][] = 'Lỗi dữ liệu';
        } else {
            $update['perm'] = 'user_actived';
            $model->update_user($uid, $update);
            $data['success'] = 'Chúc mừng bạn đã kích hoạt tài khoản thành công! <br/>
        Hãy đăng nhập và bắt đầu tham gia cùng chúng tôi';
        }
        $this->view->data = $data;
        $this->view->show('account/active_account');
    }

    function messages($app = array('index')) {

        load_apps(__MODULES_PATH . '/account/apps/messages', $app, $this);
    }

    function settings($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/account/apps/settings', $app, $this);

    }

    function manager($app = array()) {

        load_apps(__MODULES_PATH . '/account/apps/manager', $app, $this);
    }
}

