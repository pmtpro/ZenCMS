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

if (!function_exists('show_nick')) {

    function show_nick($var, $link = false, $show_color = true)
    {

        $perm = sysConfig('user_perm');

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
            return '<a href="' . HOME . '/account/wall/' . $user_data['username'] . '" '.$link_color.' '.$add.'>' . $out . '</a>';
        }
    }

}

if (!function_exists('show_perm_sign')) {

    function show_perm_sign($var) {

        $user = get_user_data($var);
        $cfg_perm = sysConfig('user_perm');
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

            $user_data = model('account')->get_user_data($u, array('id', 'username', 'nickname', 'perm'));
        }
        return $user_data;
    }
}