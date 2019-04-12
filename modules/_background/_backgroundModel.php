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

Class _backgroundModel Extends ZenModel
{

    public function _update_config($arr = array())
    {

        $arr = $this->db->sqlQuote($arr);

        foreach ($arr as $key => $value) {

            $c = $this->db->num_row($this->db->query("SELECT `id` FROM " . tb() . "config where `key`='$key'"));

            if ($c == 0)
                $this->db->query("INSERT INTO " . tb() . "config SET `key`='$key'");

            $this->db->query("UPDATE " . tb() . "config SET `value`='" . $value . "' where `key`='$key'");
        }
        return true;
    }
    /**
     * get user data
     *
     * @param $user
     * @param string $get_what
     * @return bool
     */
    public function _get_user_data($user, $get_what = '*')
    {

        $user = $this->db->sqlQuote($user);

        if (is_array($get_what)) {

            $select_what = implode(',', $get_what);

        } else {

            $select_what = $get_what;
        }
        if (is_numeric($user)) {

            $pos_where = 'id';

        } else {

            $pos_where = 'username';
        }

        $_sql = "SELECT $select_what FROM " . tb() . "users where `$pos_where` = '$user'";

        $query = $this->db->query($_sql);

        if (!$this->db->num_row($query)) {

            return false;
        }
        $data = $this->db->fetch_array($query);

        return _handle_user_data($this->db->sqlQuoteRm($data));
    }

    /**
     * @param $uid
     * @param $data
     * @return bool
     */
    function _update_user($uid, $data)
    {
        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_update(tb() . "users", $data, array("`id` = '$uid'"));

        if ($this->db->query($_sql)) {

            return true;
        }
        return false;
    }

    /**
     * @param string $type
     * @return array|mixed|null
     */
    function _get_link_list($type = '')
    {
        if (!defined('__MODULE_NAME') || __MODULE_NAME == 'update') {

            return array();
        }

        $_sql = "SELECT * FROM " . tb() . "link_list where `type` = '$type' order by `id` DESC";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);

        if ($cache != null) {

            return $cache;
        }

        $data = array();

        $query = $this->db->query($_sql);

        while ($row = $this->db->fetch_array($query)) {

            $row = $this->db->sqlQuoteRm($row);

            $row['tags'] = unserialize($row['tags']);

            $row['tag_start'] = h_decode($row['tags']['tag_start']);

            $row['tag_end'] = h_decode($row['tags']['tag_end']);

            $data[] = $row;
        }

        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $data, 86400 * 7);

        return $data;
    }

    /**
     * @return bool
     */
    function _count_new_message() {

        if (empty( $this->registry->user['id'] ) ) {

            return false;
        }

        $user = $this->registry->user;

        $_sql = "SELECT MAX(id) as cid FROM " . tb() . "messages  where `readed` = 0 and `to` = '" . $user['username'] . "' GROUP BY `from` order by `time` DESC";

        $count = $this->db->num_row($this->db->query($_sql));

        return $count;
    }

    /**
     * get all widget of a widget group
     *
     * @param $wg
     * @return array
     */
    function _get_widget_group($wg) {

        global $registry;

        $out = array();

        if (!defined('__MODULE_NAME') || __MODULE_NAME == 'update') {

            return $out;
        }

        $_sql = "SELECT * FROM ".tb()."widgets where `wg` = '$wg' order by `weight` ASC";

        $query = $registry->db->query($_sql);

        while ($row = $registry->db->fetch_array($query)) {

            $out[] = $registry->db->sqlQuoteRm($row);
        }
        return $out;
    }
}