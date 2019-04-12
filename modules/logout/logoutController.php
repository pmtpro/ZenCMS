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

Class logoutController Extends ZenController
{
    function index()
    {
        /**
         * load security library
         */
        $security = load_library('security');
        if (isset($_POST['submit-logout'])) {
            if ($security->check_token('token-logout')) {
                $_SESSION = array();
                if (session_destroy() && setcookie('ZENCK_USER_ID', '', time() - 3600, "/") && setcookie('ZENCK_LOGIN_TOKEN', '', time() - 3600, "/")) {
                    ZenView::set_success('Thoát thành công!', ZPUBLIC, HOME);
                    session_unset();
                } else ZenView::set_error('Lỗi trong khi thoát!');
            }
        }
        ZenView::set_title('Thoát');
        ZenView::set_url(HOME . '/logout');
        $data['token-logout'] = $security->get_token('token-logout');
        $this->view->data = $data;
        $this->view->show('logout');
    }
}