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

Class accountController Extends ZenController
{

    public function always_run() {
        $model = $this->model->get('account');
        $new_msg = $model->count_new_message();
        run_hook('account', 'user_detail_box_after_name', function($data) use ($new_msg) {
            if ($new_msg) {
                $data .= ' <a href="' . HOME . '/account/messages" class="label label-success" title="' . $new_msg . ' tin nhắn mới">' . $new_msg . '</a>';
            }
            return $data;
        });
    }

    public function menu() {
        $model = $this->model->get('account');
        $new_msg = $model->count_new_message();
        ZenView::set_menu(array(
            'name' => 'Tài khoản',
            'pos' => 'main',
            'menu' => array(
                array(
                    'name' => 'Tường',
                    'full_url' => HOME . '/account/wall/' . $this->user['username'],
                    'icon' => 'glyphicon glyphicon-dashboard'
                ),
                array(
                    'name' => 'Tin nhắn',
                    'full_url' => HOME . '/account/messages',
                    'icon' => 'glyphicon glyphicon-comment',
                    'badge' => $new_msg
                ),
                array(
                    'name' => 'Chỉnh sửa hồ sơ',
                    'full_url' => HOME . '/account',
                    'icon' => 'glyphicon glyphicon-user'
                ),
                array(
                    'name' => 'Cài đặt tài khoản',
                    'full_url' => HOME . '/account/settings',
                    'icon' => 'glyphicon glyphicon-cog'
                )
            )
        ));
    }

    function index() {
        load_helper('user');
        load_helper('time');
        $security = load_library('security');
        $model = $this->model->get('account');
        $hook = $this->hook->get('account');
        $data['birth_config'] = array(
            'day' => array(
                'start' => 1,
                'end' => 31
            ),
            'month' => array(
                'start' => 1,
                'end' => 12
            ),
            'year' => array(
                'start' => 1945,
                'end' => date("Y")
            )
        );

        if (isset($_POST['submit-save'])) {
            /**
             * valid_data_post_fullname hook*
             */
            $_POST['fullname'] = $hook->loader('valid_data_post_fullname', $_POST['fullname']);
            if (ZenView::is_success('fullname')) {
                $update['fullname'] = h($security->cleanXSS($_POST['fullname']));
            }

            /**
             * valid_data_post_status hook*
             */
            $_POST['status'] = $hook->loader('valid_data_post_status', $_POST['status']);
            if (ZenView::is_success('status')) {
                $update['status'] = h($security->cleanXSS($_POST['status']));
            }

            /**
             * valid_data_post_sex hook*
             */
            $_POST['sex'] = $hook->loader('valid_data_post_sex', $_POST['sex']);
            if (ZenView::is_success('sex')) {
                $update['sex'] = h($security->cleanXSS($_POST['sex']));
            }

            if ($_POST['birth-day'] >= $data['birth_config']['day']['start'] && $_POST['birth-day'] <= $data['birth_config']['day']['end']
            && $_POST['birth-month'] >= $data['birth_config']['month']['start'] && $_POST['birth-month'] <= $data['birth_config']['month']['end']
            && $_POST['birth-year'] >= $data['birth_config']['year']['start'] && $_POST['birth-month'] <= $data['birth_config']['year']['end']) {
                $update['birth'] = strtotime($_POST['birth-month'] . '/' . $_POST['birth-day'] . '/' . $_POST['birth-year']);
            } else {
                ZenView::set_error('Không đúng định dạnh ngày sinh', 'birth');
            }

            /**
             * valid_data_post_sign hook*
             */
            $_POST['sign'] = $hook->loader('valid_data_post_sign', $_POST['sign']);
            if (ZenView::is_success('sign')) {
                $update['sign'] = h($security->cleanXSS($_POST['sign']));
            }

            if (!empty($_FILES['avatar']['name'])) {
                /**
                 * valid_data_post_avatar hook*
                 */
                $_FILES['avatar'] = $hook->loader('valid_data_post_avatar', $_FILES['avatar']);
                $upload = load_library('upload', array('init_data' => $_FILES['avatar']));
            } elseif (!empty($_POST['avatar-url'])) {
                /**
                 * valid_data_post_avatar_url hook*
                 */
                $_POST['avatar-url'] = $hook->loader('valid_data_post_avatar_url', $_POST['avatar-url']);
                $upload = load_library('upload', array('init_data' => $_POST['avatar-url']));
            }
            if (isset($upload)) {
                if ($upload->uploaded) {
                    /**
                     * set filename
                     */
                    $upload->file_new_name_body = $this->user['username'] . '-avatar';
                    $upload->allowed = array('image/*');
                    $upload->image_resize = true;
                    $upload->image_x = 150;
                    $upload->image_y = 150;
                    $upload->image_ratio = true;
                    /**
                     * config-upload-avatar hook*
                     */
                    $upload = $hook->loader('config-upload-avatar', $upload);
                    /**
                     * set directory upload icon
                     */
                    $imageUploadDir = __FILES_PATH . '/account/avatars';
                    /**
                     * auto make directory by month-year
                     */
                    $subDir = autoMkSubDir($imageUploadDir);
                    $upload->process($imageUploadDir . '/' . $subDir);
                    if ($upload->processed) {
                        if (!empty($this->user['avatar'])) {
                            $old_avatar = __FILES_PATH . '/account/avatars/' . $this->user['avatar'];
                            unlink($old_avatar);
                        }
                        $dataUp = $upload->data();
                        $update['avatar'] = $subDir . '/' . $dataUp['file_name'];
                        $upload->clean();
                    } else {
                        ZenView::set_error($upload->error, 'avatar');
                    }
                }
            }

            if ($model->update_user($this->user['id'], $update) && ZenView::is_success(null)) {
                ZenView::set_success(1);
                $this->user = _reload_user_data();
            } else {
                ZenView::set_error('Có lỗi. Vui lòng sửa lỗi!');
            }
        }

        ZenView::set_title('Trang cá nhân');
        $data['user'] = $this->user;
        $this->view->data = $data;
        $this->view->show('account/index');
    }

    function wall($arg = array()) {
        /**
         * load helper
         */
        load_helper('user');
        load_helper('gadget');
        /**
         * load library
         */
        $security = load_library('security');
        $paging = load_library('pagination');
        $permission = load_library('permission');
        /**
         * get account model
         */
        $model = $this->model->get('account');
        /**
         * get account hook
         */
        $hook = $this->hook->get('account');

        $permission->set_user($this->user);
        $user = $this->user;

        $u = isset($arg[0]) ? $security->cleanXSS($arg[0]) : 0;

        /**
         * get user data
         */
        $wall = $model->get_user_data($u);
        if (empty($wall)) {
            ZenView::set_title('Không tồn tại người dùng này');
            ZenView::set_error('Người dùng này không tồn tại');
            $this->view->show('account/error');
        } else {

            /**
             * get wall setting
             */
            $wall_set = $model->get_user_setting($wall['id']);

            if (IS_MEMBER == true) {
                if ($wall_set['allow_wall_comment'] || $user['id'] == $wall['id'] || ($permission->is_manager() && !$permission->is_lower_levels_of($wall['id']))) {
                    $data['set']['allow_wall_comment'] = true;
                    /**
                     * config_ckeditor_wall_comment hook*
                     */
                    $ck_set = $hook->loader('config_ckeditor_wall_comment', array('type' => 'mini-bbcode'));
                    /**
                     * load gadget for comment form
                     */
                    gadget_ckeditor('wall-comment', $ck_set);
                } else {
                    $data['set']['allow_wall_comment'] = false;
                    ZenView::set_error('Bạn không thể bình luận trong trang này!', 'wall-comment');
                }
            } else {
                $data['set']['allow_wall_comment'] = false;
                ZenView::set_tip('<a href="' . HOME . '/login">Đăng nhập</a> hoặc <a href="' . HOME . '/register">Đăng kí</a> để bình luận trong trang cá nhân này', 'wall-comment');
            }

            /**
             * allow_wall_comment hook*
             */
            $data['set']['allow_wall_comment'] = $hook->loader('allow_wall_comment', $data['set']['allow_wall_comment'], array('var' => array('user' => $user, 'wall' => $wall)));

            if ($data['set']['allow_wall_comment'] == true) {
                if (isset($_POST['submit-comment'])) {
                    $message = $security->cleanXSS($_POST['wall-comment']);
                    if (empty($message)) {
                        ZenView::set_error('Bạn chưa nhập nội dung tin nhắn', 'wall-comment');
                    } else {
                        if (ZenView::is_success('wall-comment')) {
                            /**
                             * wall_message_before_save hook*
                             */
                            $insertData['msg'] = h($hook->loader('wall_message_before_save', $message));
                            $insertData['from'] = $user['username'];
                            $insertData['to'] = $wall['username'];
                            $insertData['type'] = 'wall';
                            $insertData['time'] = time();
                            if (!$model->insert_message($insertData)) {
                                ZenView::set_error('Không thể comment! Vui lòng thử lại', 'wall-comment');
                            }
                        }
                    }
                }
            }

            $data['set']['allow_view_wall_comment'] = $wall_set['allow_view_wall_comment'];

            if ($user['id'] == $wall['id'] || ($permission->is_manager() && !$permission->is_lower_levels_of($wall['id']))) {
                $data['set']['allow_view_wall_comment'] = true;
            }

            /**
             * allow_view_wall_comment hook*
             */
            $data['set']['allow_view_wall_comment'] = $hook->loader('allow_view_wall_comment', $data['set']['allow_view_wall_comment'], array('var' => array('user' => $user, 'wall' => $wall)));

            if ($data['set']['allow_view_wall_comment']) {
                /**
                 * paging list comment
                 */
                $limit = 10;
                $paging->setLimit($limit);
                $paging->SetGetPage('page');
                $start = $paging->getStart();
                $sql_limit = $start.','.$limit;
                $data['list_message'] = $model->get_message(array('to' => $wall['username']), $sql_limit);
                $total = $model->total_result;
                $paging->setTotal($total);
                ZenView::set_paging($paging->navi_page(), 'list-comment');

                if (empty($data['list_message'])) {
                    ZenView::set_notice('Chưa có thảo luận nào. Hãy là người đầu tiên!', 'view-comment');
                }
            } else {
                ZenView::set_notice('Không thể xem những thảo luận này!', 'view-comment');
            }

            if ($user['id'] != $wall['id']) {
                /**
                 * run user_detail_box_after_name hook*
                 */
                run_hook('account', 'user_detail_box_after_name', function($data, $stream = array()) {
                    if ($stream['user']['id'] != $stream['wall']['id']) {
                        $data .= '<a href="' . HOME . '/account/messages/conversation/' . $stream['wall']['username'] . '" title="Gủi tin nhắn" class="label label-success">PM</a>';
                    }
                    return $data;
                });
            }

            if (!$permission->is_lower_levels_of($wall['id'])) {
                /**
                 * run user_detail_box_action hook*
                 */
                run_hook('account', 'user_detail_box_action', function($data, $stream) {
                    if ($stream['user']['id'] != $stream['wall']['id']) {
                        $data .= ' <a href="' . HOME . '/admin/members/editor&id=' . $stream['wall']['id'] . '" class="label label-success">Chỉnh sửa</a>';
                    }
                    return $data;
                });
            }

            /**
             * run wall_comment_private_control hook*
             */
            run_hook('account', 'wall_comment_private_control', function($data, $stream) use ($permission) {
                global $registry;
                $user = $registry->user;
                $msgData = $stream['msg'];
                if (($user['username'] != $msgData['to'] && !$permission->is_manager()) || $permission->is_lower_levels_of($msgData['from']) || $permission->is_lower_levels_of($msgData['to'])) {
                    return $data;
                } else {
                    return $data . '<a href="' . HOME . '/account/manager/message?act=delete&id=' . $msgData['id'] . '">Xóa</a>';
                }
            });

            ZenView::set_title($wall['nickname']);
            $data['wall'] = $wall;
            $data['user'] = $user;
            $this->view->data = $data;
            $this->view->show('account/wall');
        }
    }

    function forgot_password($arg = array()) {
        $model = $this->model->get('account');
        $hook = $this->hook->get('account');
        $security = load_library('security');
        $act = '';
        $uid = 0;
        $code = '';
        $data = array();

        if (isset($arg[0])) {
            $act = $security->cleanXSS($arg[0]);
        }
        if (isset($arg[1])) {
            $uid = (int) $security->removeSQLI($arg[1]);
        }
        if (isset($arg[2])) {
            $code = $security->cleanXSS($arg[2]);
        }

        switch ($act) {
            default:
                if (isset($_POST['submit-get-password'])) {
                    $username = h($_POST['username']);
                    $data_user = $model->get_user_data($username);
                    if (!$data_user) {
                        ZenView::set_error('Không tồn tại tài khoản này');
                    } else {
                        if ($data_user['perm'] == 'user_need_active') {
                            ZenView::set_notice('Xin lỗi tài khoản này chưa kích hoạt email nên hệ thống không thể lấy lại mật khẩu cho tài khoản này');
                        } elseif ($data_user['perm'] == 'user_lock') {
                            ZenView::set_error('Tài khoản đang bị cấm!');
                        } else {
                            $code = md5(randStr());
                            if (!set_security_code($data_user['id'], 'forgot_password', $code, '2d')) {
                                ZenView::set_notice('Có lỗi, vui lòng thử lại');
                            } else {
                                $data_user = $model->get_user_data($username);
                                $ok = $hook->loader('send_mail_forgot_password', $data_user);
                                if ($ok) {
                                    ZenView::set_success('Hệ thống đã gửi thư đến email được đăng kí trên tài khoản <b>' . $data_user['nickname'] . '</b>. <br/>
                                    Vui lòng kiểm tra mail và làm theo hướng dẫn để lấy lại mật khẩu');
                                } else {
                                    ZenView::set_error('Không thể gửi mail lấy lại mật khẩu<br/>' . get_global_msg('send_mail'));
                                }
                            }
                        }
                    }
                }

                ZenView::set_title('Quên mật khẩu');
                $this->view->data = $data;
                $this->view->show('account/forgot_password/index');
                break;

            case 'reset_password':
                if (!empty($this->user)) {
                    ZenView::set_notice('Trước tiên bạn hãy thoát tài khoản hiện tại. Sau đó thực hiện lại', ZPUBLIC, HOME . '/logout');
                    exit;
                }
                if (empty($uid)) {
                    redirect(HOME);
                }

                $user = $model->get_user_data($uid);
                $data['user'] = $user;

                if (!$user) {
                    ZenView::set_error('Lỗi dữ liệu');
                } else {
                    if (!still_valid_security_code($uid, 'forgot_password') || $user['security_code']['forgot_password']['code'] != $code) {
                        ZenView::set_error('Lỗi dữ liệu');
                    } else {
                        if (isset($_POST['sub_reset_password'])) {
                            if ($security->check_token('token_continous')) {
                                $new_password = randStr(8);
                                $encode_pass = md5(md5($new_password));
                                $new_security_forgot_password = md5(rand());
                                $update['password'] = $encode_pass;
                                if (set_security_code($uid, 'forgot_password', $new_security_forgot_password, '2d')) {
                                    if (!$model->update_user($uid, $update)) {
                                        ZenView::set_error('Đã xảy ra lỗi');
                                    } else {
                                        $data['new_password'] = $new_password;
                                        ZenView::set_success(1);
                                    }
                                }
                            }
                        }
                    }
                }

                ZenView::set_title('Khôi phục mật khẩu');
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
            $data['notices'][] = 'Trước tiên bạn hãy <b><a href="' . HOME . '/logout">thoát</a></b> tài khoản hiện tại và thực hiện lại';
            $this->view->data = $data;
            $this->view->show('account/active_account');
            return;
        }

        if (empty($uid) || empty($code)) {
            redirect(HOME);
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

        $new_code = md5(randStr());

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
        ZenView::set_menu(array(
            'pos' => 'page',
            'name' => 'Tin nhắn',
            'menu' => array(
                array(
                    'full_url' => HOME . '/account/messages/conversation',
                    'icon' =>  'fa fa-plus',
                    'name' => 'Cuộc trò chuyện mới'
                )
            )
        ));
        load_apps('account/apps/messages', $app);
    }

    function settings($app = array('index')) {
        /**
         * set sub menu
         */
        ZenView::set_menu(array(
            'pos' => 'page',
            'name' => 'Cài đặt tài khoản',
            'menu' => get_apps('account/apps/settings', 'account/settings', true)
        ));
        load_apps('account/apps/settings', $app);
    }

    function manager($app = array()) {
        load_apps('account/apps/manager', $app);
    }
}

