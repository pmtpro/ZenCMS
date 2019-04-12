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
