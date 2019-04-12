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

Class logoutController Extends ZenController
{

    function index()
    {
        /**
         * load security library
         */
        $security = load_library('security');

        /**
         * set title
         */
        $data['page_title'] = 'Thoát';

        if (isset($_POST['sub'])) {

            if ($security->check_token('token_logout')) {

                $_SESSION = array();

                if (session_destroy() && setcookie('ck_user_id', '', time() - 3600, "/") && setcookie('ck_zen_token', '', time() - 3600, "/")) {

                    $data['success'] = wait_redirect(_HOME, 'Bạn đã thoát thành công!<br/>Bạn sẽ được chuyển đến trang chủ trong vòng {s} nữa', 3);

                    session_unset();

                } else {

                    $data['errors'][] = 'Lỗi trong khi thoát!';
                }
            }
        }

        $data['token_logout'] = $security->get_token('token_logout');
        $this->view->data = $data;
        $this->view->show('logout');
    }

}