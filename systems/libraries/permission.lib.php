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

class permission
{

    public $user = array();
    public $registry;

    public function __construct()
    { }

    public function setRegistry($registry) {
        $this->registry = $registry;
    }

    public function get_perm($uid)
    {

        $db = $this->registry->db;

        $query = $db->query("SELECT `perm` FROM " . tb() . "users where `id` = '$uid'");

        if (!$db->num_row($query)) {
            return false;
        }
        $row = $db->fetch_array($query);
        return $row['perm'];
    }

    /**
     * set user data
     * @param array $user
     */
    public function set_user($user = array())
    {
        $this->user = $user;
    }


    public function is_lower_levels_of($uid)
    {

        $user = $this->user;

        $perm = sys_config('user_perm');
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
    public function __call($name, $args)
    {
        global $system_config;

        $user = $this->user;

        $only_this_perm = false;

        if (isset ($args[0])) {

            $only_this_perm = $args[0];
        }

        $check = preg_replace('/^is_/', '', $name);

        $all_perm = array_keys($system_config['user_perm']['key']);

        if (in_array($check, $all_perm)) {

            if ($only_this_perm == ONLY_THIS_PERM) {

                if ($user['perm'] == $check) {

                    return TRUE;
                }
                return false;

            } else {

                if ($system_config['user_perm']['key'][$user['perm']] >= $system_config['user_perm']['key'][$check]) {

                    return true;
                }

                return false;
            }
        }

        $all_role = array_keys($system_config['role']);

        if (in_array($check, $all_role)) {

            if (empty ($user)) {

                return false;
            }

            if ($system_config['user_perm']['key'][$user['perm']] >= $system_config['user_perm']['key'][$system_config['role'][$check]]) {

                return true;
            }
            return false;
        }
        return false;
    }


}

?>