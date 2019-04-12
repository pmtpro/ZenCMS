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

Class chatboxController Extends ZenController
{

    function index() {

        $user = $this->user;
        /**
         * get chatbox model
         */
        $model = $this->model->get('chatbox');
        /**
         * load helper
         */
        load_helper('gadget');
        load_helper('time');
        load_helper('formCache');

        /**
         * load library
         */
        $security = load_library('security');
        $p = load_library('pagination');

        /**
         * load hook
         */
        $this->hook->get('chatbox');

        if (isset($_POST['sub_chat']) && get_config('chatbox_allow_guest_chat')) {

            if ($security->check_token('token_chat')) {

                if (!$security->check_token('captcha_code') && !IS_MEMBER) {

                    $data['errors'][] = 'Mã xác nhận không chính xác';

                } else {

                    if (!IS_MEMBER && strlen($_POST['name'] > 50)) {

                            $data['notices'][] = 'Tên phải nhỏ hơn 50 kí tự';

                    } else {

                        if (empty($_POST['name'])) {

                            $_POST['name'] = 'Khách';
                        }

                        if (!empty($_POST['content'])) {

                            if (IS_MEMBER) {
                                $ins['uid'] = $user['id'];
                                $ins['name'] = $user['username'];
                            } else {
                                $ins['uid'] = 0;
                                $ins['name'] = h($_POST['name']);
                            }
                            $ins['content'] = h($_POST['content']);

                            $ins['user_agent'] = client_user_agent();

                            $ins['time'] = time();

                            if (!$model->insert($ins)) {

                                $data['notices'][] = 'Lỗi dữ liệu';
                            }
                        }
                    }
                }
            }
        }

        $limit = get_config('chatbox_num_item_per_page');

        $p->setLimit($limit);
        $p->SetGetPage('page');
        $start = $p->getStart();
        $sql_limit = $start.','.$limit;

        $data['list'] = $model->get_list(false, $sql_limit);

        $total = $model->total_result;
        $p->setTotal($total);
        $data['chat_pagination'] = $p->navi_page();

        /**
         * set page title
         */
        $data['page_title'] = "Chatbox";
        $data['page_more'][] = gadget_TinymceEditer('bbcode_mini', true);

        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = _HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';

        /**
         * get token chat
         */
        $token = $security->get_token('token_chat');
        $data['token'] = $token;

        $this->view->data = $data;
        $this->view->show('chatbox/index');
    }

    public function manager($app = array('index'))
    {
        load_apps(__MODULES_PATH . '/chatbox/apps/manager', $app);
    }

}