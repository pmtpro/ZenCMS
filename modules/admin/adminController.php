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

Class adminController Extends ZenController
{
    protected $protected;

    /**
     * this method will auto start when router is activated
     * auto load menu
     */
    public function _launcher() {
        ZenView::set_menu(array(
            'pos' => 'application',
            'menu' => get_apps('admin/apps', 'admin')
        ));
    }
    /**
     * admin dashboard
     */
    public function index() {
        ZenView::set_title('ZenCMS - Admin control panel');
        $model = $this->model->get('admin');
        $new_feeds = $model->load_new_feed_post(0, 5);
        if (!$new_feeds) {
            ZenView::set_error('Không thể tải tin tức', 'new_feed');
        }

        $list_cat = $model->load_new_feed_cat(0, 0);

        $this->view->data['new_feeds'] = $new_feeds;
        $this->view->data['list_cat'] = $list_cat;
        $this->view->show('admin');
    }

    public function login() {
        /**
         * set the page title
         */
        ZenView::set_title('Đăng nhập tài khoản');
        $security = load_library('security');
        $model = $this->model->get('login');
        $data_login['limit_login'] = FALSE;
        $data_login['confirm_login_fail'] = FALSE;
        $data['limit_login'] = FALSE;

        /**
         * if user is logged, redirect to admin CP
         */
        if ($model->logged()) {
            redirect(HOME . '/admin');
        }
        if ($model->login_flood()) {
            $data_login['limit_login'] = TRUE;
        }
        /**
         * check login
         */
        if (isset($_POST['submit-login'])) {
            if (empty($_POST['username']) || empty($_POST['password'])) {
                ZenView::set_notice('Bạn cần nhập tài khoản và mật khẩu');
            } else {
                if ($security->check_token('token_login')) {
                    $data_login['username'] = $security->cleanXSS($_POST['username']);
                    $data_login['password'] = $_POST['password'];
                    if ($data_login['limit_login']) {
                        if (!$security->check_token('captcha_code')) {
                            $data_login['confirm_login_fail'] = TRUE;
                        }
                    }
                    if (!$model->check_login($data_login) || $data_login['confirm_login_fail']) {
                        $model->update_login_flood();
                        if ($model->login_flood()) {
                            $data_login['limit_login'] = TRUE;
                        }
                        if ($data_login['confirm_login_fail']) {
                            ZenView::set_error('Sai mã xác nhận');
                        } else ZenView::set_error('Sai tên đăng nhập hoặc mật khẩu');
                    } else {
                        $model->reset_login_flood();
                        $update = $model->update_login(_gen_login_token());
                        if ($update) {
                            $data_user = $model->get_data_user();
                            $save_cookie = 0;
                            if (!empty($_POST['remember_me'])) $save_cookie = $model->cookie_time_hold();
                            _gen_session_login($data_user, $save_cookie);
                            $get_urlBack = ZenInput::get('urlBack', true);
                            ZenView::set_success('Đăng nhập thành công!', ZPUBLIC,$get_urlBack ? urldecode($get_urlBack) : HOME . '/admin');
                        }
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

        ZenView::add_meta(array('name' => 'keywords', 'content' => 'abc, cde'));
        ZenView::add_meta('viewport', 'width=device-width, initial-scale=1');
        ZenView::add_meta('X-UA-Compatible', 'IE=Edge,chrome=1', 'http-equiv');

        $this->view->data = $data;
        $this->view->show('page:admin-login');
    }

    public function logout() {
        $model = $this->model->get('logout');
        if (isset($_POST['submit-logout'])) {
            if ($model->logout()) {
                $get_back = ZenInput::get('back', true);
                if ($get_back) {
                    $back = urlencode($get_back);
                } else $back = HOME;
                ZenView::set_success('Thoát thành công!', ZPUBLIC, $back);
            } else ZenView::set_error('Lỗi trong khi thoát!');
        }
        ZenView::set_title('Thoát tài khoản');
        $this->view->show('admin/logout');
    }

    /**
     * @param array $app
     */
    public function general($app = array('index')) {
        load_apps('admin/apps/general', $app);
    }

    public function members($app = array('index'))
    {
        load_apps('admin/apps/members', $app);
    }

    public function tools($app = array('index'))
    {
        load_apps('admin/apps/tools', $app);
    }

    public function caches($app = array('index'))
    {
        load_apps('admin/apps/caches', $app);
    }

    public function ajax_check_update() {
        $model = $this->model->get('admin');
        $update = $model->version_check_update();
        if ($update->status == 1 && !empty($update->data)) {
            if ((float)ZENCMS_VERSION < (float)$update->data->version) {
                ZenView::ajax_json_response($update->data, true);
            }
        }
    }

    public function check_update_js() {
        ZenView::add_js(_URL_MODULES . '/admin/js/check_update.js?v=1', 'foot');
    }
}
