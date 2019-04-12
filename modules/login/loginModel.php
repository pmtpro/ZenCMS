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

Class loginModel Extends ZenModel
{

    public $data_user = array();

    public function check_login($data)
    {

        $username = $this->db->sqlQuote($data['username']);
        $password = $this->db->sqlQuote(md5(md5($data['password'])));

        $re = $this->db->query("SELECT * FROM " . tb() . "users where `username`='$username'");
        $count = $this->db->num_row($re);

        if ($count == 1) {

            $ro = $this->db->fetch_array($re);

            if ($ro['password'] == $password) {

                $this->data_user = $ro;

                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function update_login($ss_zen_login = false)
    {

        if (!empty($this->data_user)) {

            if (!empty($ss_zen_login)) {

                $user_id = $this->data_user['id'];
                $update = $this->db->query("UPDATE " . tb() . "users SET `last_ip`='" . client_ip() . "', 
                                                `last_login` = '" . time() . "', 
                                                `ss_zen_login`='$ss_zen_login' 
                                                 where id='$user_id'");
                if ($update) {
                    return TRUE;
                }
                return FALSE;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function get_data_user()
    {

        return $this->data_user;
    }

}