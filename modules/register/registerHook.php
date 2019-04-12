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

class registerHook extends ZenHook
{
    function send_mail($data)
    {
        send_mail($data['email'], 'Kích hoạt tài khoản', 'Cảm ơn bạn đã đăng kí tài khoản <b>' . $data['nickname'] . '</b> trên <a href="' . HOME . '">' . HOME . '</a>.<br/>
        Vui lòng click link sau để kích hoạt! <br/>
        <a href="' . HOME . '/account/active_account/' . $data['id'] . '/' . $data['security_code']['register']['code'] . '">' . HOME . '/account/active_account/' . $data['id'] . '/' . $data['security_code']['register']['code'] . '</a>');
    }
}