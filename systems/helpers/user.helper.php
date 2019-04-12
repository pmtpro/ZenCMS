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

if (!function_exists('show_nick')) {

    function show_nick($var, $link = false, $show_color = true)
    {

        $perm = sys_config('user_perm');

        $user_data = get_user_data($var);

        if ($show_color) {

            $color = $perm['color'][$user_data['perm']];
            $link_color = 'style="color: ' . $color . '"';
            $out = '<span style="font-weight: bold; color: ' . $color . '">' . $user_data['nickname'] . '</span>';

        } else {

            $link_color = '';
            $out = $user_data['nickname'];
        }

        if ($link == false) {

            return $out;

        } else {

            if (!is_numeric($link) && !is_bool($link)) {

                $add = $link;

            } else {

                $add = '';
            }
            return '<a href="' . _HOME . '/account/wall/' . $user_data['username'] . '" '.$link_color.' '.$add.'>' . $out . '</a>';
        }
    }

}

if (!function_exists('show_perm_sign')) {

    function show_perm_sign($var) {

        $user = get_user_data($var);
        $cfg_perm = sys_config('user_perm');
        $cfg_sign = $cfg_perm['sign'];
        return $cfg_sign[$user['perm']];
    }
}

if (!function_exists('get_user_data')) {

    function get_user_data($var) {

        $user = $var;

        if(is_array($user) && isset($user['id']) && isset($user['username']) && isset($user['nickname']) && isset($user['perm']))  {

            $user_data = $user;
        } else {

            if(is_array($user)) {

                if(isset($user['id'])) {
                    $u = $user['id'];
                }
                if(isset($user['username'])) {
                    $u = $user['username'];
                }
            } else {
                $u = $user;
            }

            $user_data = model()->_get_user_data($u, array('id', 'username', 'nickname', 'perm'));
        }
        return $user_data;
    }
}

?>
