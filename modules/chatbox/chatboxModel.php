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

Class chatboxModel Extends ZenModel
{

    public $total_result;

    /**
     *
     * @param array $arr
     * @return bool
     */
    public function update_config($arr = array())
    {

        return $this->get()->_update_config($arr);
    }


    function insert($data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_insert(tb().'chatbox', $data);

        return $this->db->query($_sql);
    }

    function update($cid, $data) {

        $_sql = $this->db->_sql_update(tb()."chatbox", $data, "id = '$cid'");

        return $this->db->query($_sql);
    }

    function delete($cid) {

        $_sql = "DELETE FROM ".tb()."chatbox WHERE `id` = '$cid'";

        return $this->db->query($_sql);
    }

    function get_list($group = '', $limit) {

        $out = array();

        /**
         * load bbcode library
         */
        $bbcode = load_library('bbcode');

        /**
         * load hook
         */
        $this->registry->hook->get('chatbox');

        if ($limit) {

            $select_limit = "LIMIT ".$limit;
        } else {

            $select_limit = '';
        }

        $_sql_total = "SELECT * FROM ".tb()."chatbox where `group` = '$group' order by `id` DESC ";

        $this->total_result = $this->db->num_row($this->db->query($_sql_total));

        $_sql = $_sql_total.$select_limit;

        $query = $this->db->query($_sql);

        while ($row = $this->db->fetch_array($query)) {

            $row = $this->db->sqlQuoteRm($row);

            if ($row['uid']) {

                $row['user'] = $this->get()->_get_user_data($row['uid']);

            } else {

                $astract_user['id'] = $row['uid'];
                $astract_user['username'] = $row['name'];
                $astract_user['nickname'] = $row['name'];
                $astract_user['avatar'] = tpl_config('default_avatar');
                $astract_user['full_avatar'] = _BASE_TEMPLATE_IMG . '/' . tpl_config('default_avatar');
                $astract_user['last_login'] = 1;
                $astract_user['perm'] = PERM_GUEST;
                $row['user'] = $astract_user;
            }

            $row['content'] = scan_smiles($bbcode->parse($row['content']));

            if (is(ROLE_MANAGER)) {

                $row['manager_bar'] = $this->registry->hook->loader('manager_bar', $row['id'], true);

            } else {

                $row['manager_bar'] = array();
            }

            $out[] = $row;
        }

        return $out;
    }

    function cleanup($group = '') {

        $_sql = "DELETE FROM ".tb()."chatbox where `group` = '$group'";

        return $this->db->query($_sql);
    }

    function get_chat_data($cid) {

        $_sql = "SELECT * FROM ".tb()."chatbox where `id` = '$cid'";

        $query = $this->db->query($_sql);

        $row = $this->db->fetch_array($query);

        return $this->db->sqlQuoteRm($row);
    }
}