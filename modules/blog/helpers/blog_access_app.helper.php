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

if (!function_exists('is_allow_access_blog_app')) {

    function is_allow_access_blog_app($file)
    {
        global $obj;

        if (!file_exists($file)) {
            return false;
        }

        $parse = load_library('parse');
        $permission = load_library('permission');

        $str_parse = $parse->ini_php_file_comment($file);
        if (isset($str_parse['allow_access'])) {

            $lists_access = explode(',', $str_parse['allow_access']);

            foreach ($lists_access as $key => $perm) {
                $lists_access[$key] = trim($perm);
            }

            if (!in_array($obj->user['perm'], $lists_access) && !$permission->is_admin()) {
                return false;
            }
            return true;
        }
        return true;
    }

}
?>
