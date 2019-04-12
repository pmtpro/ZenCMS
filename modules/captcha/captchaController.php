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

Class captchaController Extends ZenController
{
    function image($arg = array())
    {
        if (isset($arg[0]) && preg_match('/\.jpg$/', $arg[0])) {

            $name = $arg[0];
            $name = preg_replace('/\.jpg$/', '', $name);
            $token = preg_replace('/^image_/', '', $name);

            $security = load_library('security');

            if ($security->isValid('captcha_security_key', $token)) {

                $cap = load_library('captcha');
                $cap->image();

            }
        }
    }
}
?>