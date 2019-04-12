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

class accountHook extends ZenHook
{

    function send_mail_forgot_password($data)
    {

        $sent = send_mail($data['email'],
            'Lấy lại mật khẩu',
            'Bạn hay ai đó đã muốn lấy lại mật khẩu trên <a href="' . _HOME . '">' . _HOME . '</a> cho tài khoản <b>' . $data['nickname'] . '</b>.<br/>
                        Nếu người đó là bạn vui lòng click link dưới để reset mật khẩu! Nếu không bạn hãy bỏ qua thư này <br/>
                        <a href="' . _HOME . '/account/forgot_password/reset_password/' . $data['id'] . '/' . $data['security_code']['forgot_password']['code'] . '">' . _HOME . '/account/forgot_password/reset_password/' . $data['id'] . '/' . $data['security_code']['forgot_password']['code'] . '</a>');
        return $sent;
    }
}