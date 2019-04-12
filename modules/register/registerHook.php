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

class registerHook extends ZenHook
{

    function send_mail($data)
    {

        send_mail($data['email'], 'Kích hoạt tài khoản', 'Cảm ơn bạn đã đăng kí tài khoản <b>' . $data['nickname'] . '</b> trên <a href="' . _HOME . '">' . _HOME . '</a>.<br/>
        Vui lòng click link sau để kích hoạt! <br/>
        <a href="' . _HOME . '/account/active_account/' . $data['id'] . '/' . $data['security_code']['register']['code'] . '">' . _HOME . '/account/active_account/' . $data['id'] . '/' . $data['security_code']['register']['code'] . '</a>');
    }

}