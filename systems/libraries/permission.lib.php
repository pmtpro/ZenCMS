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

class permission
{
    public $user = array();
    public $registry;

    public function __construct() {}

    public function setRegistry($registry) {
        $this->registry = $registry;
    }

    public function get_perm($uid) {
        global $registry;
        $model = $registry->model->get('account');
        $userData = $model->get_user_data($uid, 'perm');
        return $userData['perm'];
    }

    /**
     * set user data
     * @param array $user
     */
    public function set_user($user = array()) {
        $this->user = $user;
    }


    public function is_lower_levels_of($uid) {
        $user = $this->user;
        $perm = sysConfig('user_perm');
        $perm_key = $perm['key'];
        if ($this->get_perm($uid) == false) {
            return false;
        }
        $user_perm_level = $perm_key[$user['perm']];
        $be_compare_perm_level = $perm_key[$this->get_perm($uid)];
        if ($user_perm_level < $be_compare_perm_level) {
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @param $args
     * @return bool
     */
    public function __call($name, $args) {
        global $zen;
        $user = $this->user;
        $only_this_perm = false;
        if (isset ($args[0])) {
            $only_this_perm = $args[0];
        }
        $check = preg_replace('/^is_/', '', $name);
        $all_perm = array_keys($zen['config']['user_perm']['key']);
        if (in_array($check, $all_perm)) {
            if ($only_this_perm == ONLY_THIS_PERM) {
                if ($user['perm'] == $check) {
                    return TRUE;
                }
                return false;
            } else {
                if ($zen['config']['user_perm']['key'][$user['perm']] >= $zen['config']['user_perm']['key'][$check]) {
                    return true;
                }
                return false;
            }
        }
        $all_role = array_keys($zen['config']['role']);
        if (in_array($check, $all_role)) {
            if (empty ($user)) {
                return false;
            }
            if ($zen['config']['user_perm']['key'][$user['perm']] >= $zen['config']['user_perm']['key'][$zen['config']['role'][$check]]) {
                return true;
            }
            return false;
        }
        return false;
    }
}