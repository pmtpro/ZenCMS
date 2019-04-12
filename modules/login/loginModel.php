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