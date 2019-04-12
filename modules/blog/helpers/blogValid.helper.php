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

if (!function_exists('preg_match_url')) {

    function preg_match_url($url)
    {
        preg_match_all('/^(.*)-([0-9]+)\.html$/is', $url, $matchs);

        if (isset($matchs[2][0])) {

            $sid = $matchs[2][0];

        } else {

            $sid = 0;
        }
        return $sid;
    }

}
