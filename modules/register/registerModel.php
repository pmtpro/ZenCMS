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

Class registerModel Extends ZenModel
{

    public function account_exists($data, $check_what = 'username')
    {
        $sql = '';

        $data = $this->db->sqlQuote($data);

        if (empty($check_what))
            $check_what = 'username';

        if ($check_what == 'username') {

            $sql = "SELECT `id` FROM " . tb() . "users where `username`='".$data['username']."'";

        } elseif ($check_what == 'email') {

            $sql = "SELECT `id` FROM " . tb() . "users where `email`='".$data['email']."'";
        }

        $count = $this->db->num_row($this->db->query($sql));

        if ($count == 0)
            return FALSE;
        else
            return TRUE;
    }

    public function register_user($data)
    {

        $data = $this->db->sqlQuote($data);

        $data['time_reg'] = time();

        $sql = $this->db->_sql_insert(tb() . "users", $data);

        $insert = $this->db->query($sql);

        if ($insert) {

            return TRUE;

        } else {

            return FALSE;
        }
    }

    public function insert_id()
    {
        return $this->db->insert_id();
    }

}