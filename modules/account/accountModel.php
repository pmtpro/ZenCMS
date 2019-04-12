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

Class accountModel Extends ZenModel
{
    public $msg_conversation_partner;
    public $msg_from;
    public $msg_list_user_partner = array();
    public $msg_total_result;
    public $table = 'users';
    public $total_result;

    /**
     * account table
     * @return string
     */
    public function table() {
        return tb() . $this->table;
    }

    /**
     * return total result of a query
     * @return number mixed
     */
    public function get_total_result() {
        return $this->total_result;
    }

    /**
     * Update user setting
     * @param $uid
     * @param array $update
     * @param array $option
     * @return bool
     */
    public function update_user_setting($uid, $update, $option = array()) {
        if (!$this->user_is_exists($uid)) {
            return false;
        }
        $funcImport = '';
        $funcExport = '';
        if (!empty($option['func_import'])) {
            $funcImport = $option['func_import'];
        }
        if (!empty($option['func_export'])) {
            $funcExport = $option['func_export'];
        }
        $update = $this->db->sqlQuote($update);
        foreach ($update as $key => $value) {
            $c = $this->db->num_row($this->db->query("SELECT `id` FROM " . tb() . "users_set where `uid`='$uid' and `key`='$key'"));
            if ($c == 0)
                $this->db->query("INSERT INTO " . tb() . "users_set SET `uid`='$uid', `key`='$key'");
            $this->db->query("UPDATE " . tb() . "users_set SET `value`='" . $value . "', `func_import`='$funcImport', `func_export`='$funcExport' where `key`='$key' and `uid`='$uid'");
        }
        return true;
    }

    /**
     * Update user data
     * @param $uid
     * @param $data
     * @return mixed
     */
    public function update_user($uid, $data) {
        $data = $this->db->sqlQuote($data);
        $_sql = $this->db->_sql_update($this->table(), $data, array("`id` = '$uid'"));
        return $this->db->query($_sql);
    }

    /**
     * Get user setting
     * if empty $key, this method will return list user set from table zen_cms_users_set
     * @param $uid
     * @param string $key
     * @return array|mixed
     */
    public function get_user_setting($uid, $key = '') {
        $select_key = '';
        if (!empty($key)) {
            $select_key = " and `key`='$key'";
        }
        $sql = "SELECT * FROM ". tb() ."users_set WHERE `uid`='$uid'" . $select_key;
        $query = $this->db->query($sql);
        /**
         * check setting is exists
         */
        if (!$this->db->num_row($query)) {
            return;
        }
        if (!empty($key)) {
            $row = $this->db->fetch_array($query);
            $row = $this->db->sqlQuoteRm($row);
            if (!empty($row['func_export']) && function_exists($row['func_export'])) {
                $out = call_user_func($row['func_export'], $row['value']);
            } else $out = $row['value'];
        } else {
            $out = array();
            while ($row = $this->db->fetch_array($query)) {
                $row = $this->db->sqlQuoteRm($row);
                if (!empty($row['func_export']) && function_exists($row['func_export'])) {
                    $out[$row['key']] = call_user_func($row['func_export'], $row['value']);
                } else $out[$row['key']] = $row['value'];
            }
        }
        return $out;
    }

    /**
     * Check user is exists.
     * @param string|int $user
     * @return bool
     */
    public function user_is_exists($user) {
        if (is_numeric($user)) {
            $pos_where = 'id';
        } else $pos_where = 'username';
        $_sql = "SELECT `id` FROM ".$this->table(). " WHERE `$pos_where`='$user'";
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query)) {
            return true;
        }
        return false;
    }

    /**
     * Get user data
     * @param string|int $user
     * @param string $get_what
     * @return array bool
     */
    public function get_user_data($user, $get_what = '*')
    {
        $user = $this->db->sqlQuote($user);
        if (is_array($get_what)) {
            $select_what = implode(',', $get_what);
        } else $select_what = $get_what;

        if (is_numeric($user)) {
            $pos_where = 'id';
        } else $pos_where = 'username';

        $_sql = "SELECT $select_what FROM " . $this->table() . " WHERE `$pos_where` = '$user'";
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return false;
        }
        $data = $this->db->fetch_array($query);
        return $this->gdata($this->db->sqlQuoteRm($data));
    }

    /**
     * Get list users by permission
     * @param string $perm
     * @param bool $limit
     * @return array
     */
    public function get_user_by_perm($perm = '', $limit = false) {
        $select_perm  = '';
        $users = array();
        $select_limit = '';
        if (!empty($perm)) {
            $select_perm = "where `perm` = '$perm'";
        }
        if (!empty($limit)) {
            $select_limit = 'limit '.$limit;
        }
        $sql_total = "SELECT * FROM ".tb()."users $select_perm order by `time_reg` DESC";
        /**
         * count member
         */
        $this->total_result = $this->db->num_row($this->db->query($sql_total));
        $sql = $sql_total. " $select_limit";
        $query = $this->db->query($sql);
        if ($this->db->num_row($query)) {
            while ($row = $this->db->fetch_array($query)) {
                $row = $this->db->sqlQuoteRm($row);
                $row = $this->gdata($row);
                $users[] = $row;
            }
        }
        return $users;
    }

    /**
     * @param string|array $what_gets
     * @param string $where
     * @param array $order
     * @param bool $limit
     * @return array|mixed|null
     */
    public function gets($what_gets, $where = '', $order = array(), $limit = false)
    {
        $users = array();
        $select_what = $this->explode_what_gets($what_gets);
        $select_limit = '';
        $select_where = '';
        foreach ($order as $key => $value) {
            if ($value) {
                $order_by[] = "`$key` $value";
            } else {
                $order_by[] = "$key";
            }
        }
        $select_order = implode(', ', $order_by);
        if (!$select_order) {
            $select_order = "`weight` ASC, `time` DESC";
        }
        if (!empty($limit)) {
            $select_limit = "LIMIT " . $limit;
        }
        if (!empty($where)) {
            $select_where = $where;
        }
        $_sql_total = "SELECT $select_what FROM " . $this->tb() . " $select_where order by $select_order";
        $this->total_result = $this->db->num_row($this->db->query($_sql_total));
        $_sql = $_sql_total . " $select_limit";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $re = $this->db->query($_sql);
        if ($this->db->num_row($re) == 1) {
            $users[] = $this->gdata($this->db->fetch_array($re));
        } else {
            while ($row = $this->db->fetch_array($re)) {
                $users[] = $this->gdata($row);
            }
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $users, 600);
        return $users;
    }

    /**
     * @param $what_gets
     * @return string
     */
    private function explode_what_gets($what_gets)
    {
        if (empty($what_gets)) {
            $what_gets = '*';
        }
        if (!is_array($what_gets)) {
            $list = explode(',', $what_gets);
            if (!count($list)) $what_gets = array($what_gets);
            else $what_gets = $list;
        }
        foreach ($what_gets as $k => $what) {
            $what_gets[$k] = trim($what);
        }
        $select_what = implode(', ', $what_gets);
        return $select_what;
    }

    /**
     * @param array $inArr
     * @return array
     */
    public function gdata($inArr = array()) {
        $data = _handle_user_data($inArr);
        load_helper('time');
        if (!empty($data['birth'])) {
            $data['display_birth'] = m_timetostr($data['birth']);
        }
        if (!empty($data['time_reg'])) {
            $data['display_time_reg'] = m_timetostr($data['time_reg']);
        }
        if (!empty($data['last_login'])) {
            $data['display_last_login'] = m_timetostr($data['last_login']);
        }
        if (isset($data['username'])) {
            $data['full_url'] = HOME . '/account/wall/' . $data['username'];
        }
        if (isset($data['avatar'])) {
            $data['full_avatar'] = $this->full_avatar($data['avatar']);
        }
        return $data;
    }

    /**
     * Get full avatar url from user avatar
     * @param string $avatar
     * @return string
     */
    private function full_avatar($avatar) {
        return $avatar ? _URL_FILES . '/account/avatars/' . $avatar : _URL_FILES_SYSTEMS . '/images/default/avatar.png';
    }

    /**
     * Get list conversation to a user
     * @param $username
     * @param bool $limit
     * @return array
     */
    public function get_list_conversation($username, $limit = false) {
        $user = $this->user;
        /**
         * load time helper
         */
        load_helper('time');
        /**
         * get hook
         */
        $hook = $this->registry->hook->get('account');

        $select_limit = empty($limit)?'':' LIMIT ' . $limit;
        /**
         * total conversation query
         */
        $_sql_total = "SELECT * FROM " . tb() . "messages as msgTb WHERE (`to` = '" . $username . "' OR `from` = '" . $username . "') AND `type`='message' AND id = (SELECT MAX(id) FROM " . tb() . "messages WHERE (`from`=msgTb.from AND `to`=msgTb.to) OR (`from`=msgTb.to AND `to`=msgTb.from)) ORDER BY `time` DESC, `readed` ASC";
        /**
         * get conversation with limit query
         */
        $sql = $_sql_total . $select_limit;
        /**
         * Count all conversation
         */
        $this->total_result = $this->db->num_row($this->db->query($_sql_total));
        $query = $this->db->query($sql);
        $arr = array();
        while ($row = $this->db->fetch_array($query)) {
            /**
             * remove quote
             */
            $row = $this->db->sqlQuoteRm($row);

            /**
             * check:
             * if last message created by current user, renew data username, nickname, avatar, perm with new user (messages.to)
             */
            if ($row['from'] == $user['username']) {
                $choose_use = $row['to'];
                $row['readed'] = 1;
            }
            else $choose_use = $row['from'];
            $rowUser = $this->get_user_data($choose_use, 'username, nickname, avatar, perm');
            $row['username'] = $rowUser['username'];
            $row['nickname'] = $rowUser['nickname'];
            $row['avatar'] = $rowUser['avatar'];
            $row['perm'] = $rowUser['perm'];

            $row['full_avatar'] = $this->full_avatar($row['avatar']);

            /**
             * num_word_sub_message hook*
             */
            $numWord = $hook->loader('number_word_sub_message', 15);

            $row['sub_msg'] = subWords($row['msg'], $numWord);

            /**
             * message_sub_before_display hook *
             */
            $row['sub_msg'] = $hook->loader('message_sub_before_display', $row['sub_msg']);
            $row['display_time'] = m_timetostr($row['time']);
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * Get list message of a conversation
     * @param string $partner: username
     * @param bool|int $limit
     * @return array
     */
    public function get_conversation($partner, $limit = false) {
        /**
         * load time helper
         */
        load_helper('time');
        /**
         * get hook account
         */
        $hook = $this->registry->hook->get('account');

        $me = $this->user['username'];
        $out = array();
        $select_limit = (!empty($limit))?' LIMIT ' . $limit : '';

        $_sql_total = "SELECT " . tb() . "messages.*, " . tb() . "users.nickname, " . tb() . "users.avatar, " . tb() . "users.perm FROM " . tb() . "messages INNER JOIN " . tb() . "users ON " . tb() . "messages.from = " . tb() . "users.username WHERE ((" . tb() . "messages.to = '" . $me . "' AND " . tb() . "messages.from = '" . $partner . "') OR (" . tb() . "messages.to = '" . $partner . "' AND " . tb() . "messages.from = '" . $me . "')) AND `type`='message' ORDER BY " . tb() . "messages.time DESC";
        $_sql = $_sql_total . $select_limit;
        /**
         * count all conversation
         */
        $this->total_result = $this->db->num_row($this->db->query($_sql_total));
        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            /**
             * message_before_display hook*
             */
            $row['msg'] = $hook->loader('message_before_display', $row['msg']);
            $row['full_avatar'] = $this->full_avatar($row['avatar']);
            $row['display_time'] = m_timetostr($row['time']);
            $out[$row['id']] = $this->db->sqlQuoteRm($row);
        }
        return $out;
    }


    /**
     * Count all message in a conversation
     * @param string $u1: username
     * @param $u2: username
     * @return mixed
     */
    public function count_conversation_msg($u1, $u2) {
        $query = $this->db->query("SELECT `id` FROM "  . tb() . "messages WHERE (`from`='$u1' AND `to`='$u2') OR (`from`='$u2' AND `to`='$u1')");
        return $this->db->num_row($query);
    }

    public function count_new_message() {
        if (empty($this->user['id'])) {
            return false;
        }
        $user = $this->user;
        $_sql = "SELECT MAX(id) as cid FROM " . tb() . "messages WHERE `readed` = 0 AND `to` = '" . $user['username'] . "' GROUP BY `from` ORDER BY `time` DESC";
        $count = $this->db->num_row($this->db->query($_sql));
        return $count;
    }

    /**
     * Get data of a message by ID
     * @param int $id
     * @param string $get_what
     * @return array
     */
    public function get_message_data($id, $get_what = '*') {
        $sql = "SELECT $get_what FROM " . tb() . "messages WHERE id='$id'";
        $query = $this->db->query($sql);
        return $this->db->fetch_array($query);
    }

    /**
     * Get message
     * @param array $config
     * @param int $limit
     * @return array
     */
    public function get_message($config, $limit = 20) {
        global $registry;
        /**
         * load time helper
         */
        load_helper('time');
        /**
         * get hook account
         */
        $hook = $registry->hook->get('account');
        $where_list = array();
        $out = array();
        if (isset($config['from'])) {
            $where_list[] = tb() . "messages.from='" . $config['from'] . "'";
        }
        if (isset($config['to'])) {
            $where_list[] = tb() . "messages.to='" . $config['to'] . "'";
        }
        if (isset($config['readed'])) {
            $where_list[] = tb() . "messages.readed='" . $config['readed'] . "'";
        }
        if (isset($config['type'])) {
            $where_list[] = tb() . "messages.type='" . $config['type'] . "'";
        }
        if (!empty($limit)) {
            $select_limit = "LIMIT " . $limit;
        }
        $where = implode(' and ', $where_list);
        /**
         * count all message in profile
         */
        $sql_total = "SELECT " . tb() . "messages.id FROM " . tb() . "messages INNER JOIN " . tb() . "users ON " . tb() . "messages.from = " . tb() . "users.username" . ($where?' WHERE ' . $where:'') . " ORDER BY " . tb() . "messages.id DESC, " . tb() . "messages.time DESC";
        $countQuery = $this->db->query($sql_total);
        $this->total_result = $this->db->num_row($countQuery);

        $sql = "SELECT " . tb() . "messages.*, " . tb() . "users.nickname, " . tb() . "users.avatar, " . tb() . "users.perm FROM " . tb() . "messages INNER JOIN " . tb() . "users ON " . tb() . "messages.from = " . tb() . "users.username" . ($where?' WHERE ' . $where:'') . " ORDER BY " . tb() . "messages.id DESC, " . tb() . "messages.time DESC" . (isset($select_limit)? " " . $select_limit : '');
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $row = $this->gdata($this->db->sqlQuoteRm($row));
            $row['display_msg'] = $hook->loader('message_before_display', $row['msg']);
            $row['display_time'] = m_timetostr($row['time']);
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Check message is exists
     * @param int $id
     * @return bool
     */
    public function message_is_exists($id) {
        $sql = "SELECT `id` FROM " . tb() . "messages WHERE id='$id'";
        $query = $this->db->query($sql);
        if ($this->db->num_row($query)) {
            return true;
        }
        return false;
    }

    /**
     * Delete a message by id
     * @param int $id
     * @return boot
     */
    public function delete_message($id) {
        return $this->db->query("DELETE FROM " . tb() . "messages WHERE id='$id'");
    }

    /**
     * Delete a conversation
     * @param string $u1: username
     * @param string $u2: username
     * @return bool
     */
    public function delete_conversation($u1, $u2) {
        return $this->db->query("DELETE FROM " . tb() . "messages WHERE (`from`='$u1' AND `to`='$u2') OR (`from`='$u2' AND `to`='$u1')");
    }

    /**
     * mark a conversation is read
     * @param string $partner: username
     */
    public function mark_read_conversation($partner) {
        $user = $this->user;
        $this->db->query("UPDATE ".tb()."messages SET `readed` = '1' where `from` = '$partner' and `to` = '".$user['username']."'");
    }

    /**
     * insert new message
     * @param array $data
     * @return bool
     */
    public function insert_message($data) {
        $data = $this->db->sqlQuote($data);
        if (isset($data['from'])) {
            $anti_where['from'] = $data['from'];
        }
        if (isset($data['to'])) {
            $anti_where['to'] = $data['to'];
        }
        if (isset($data['type'])) {
            $anti_where['type'] = $data['type'];
        }
        if ($this->anti_flood_message($anti_where)) {
            $sql = $this->db->_sql_insert(tb() . 'messages', $data);
            return $this->db->query($sql);
        }
        return true;
    }

    /**
     * anti flood when insert message
     * @param array $dataType
     * @return bool
     */
    public function anti_flood_message($dataType) {
        $whereList = array();
        if (isset($dataType['type'])) {
            $whereList[] = "`type`='" . $dataType['type'] . "'";
        }
        if (isset($dataType['from'])) {
            $whereList[] = "`from`='" . $dataType['from'] . "'";
        }
        if (isset($dataType['to'])) {
            $whereList[] = "`to`='" . $dataType['to'] . "'";
        }
        $select_where = (!empty($whereList)) ? " WHERE " . implode(' and ', $whereList) : '';
        $sql = "SELECT `time` FROM " .tb(). "messages" . $select_where . " ORDER BY `id` DESC, `time` DESC LIMIT 1";
        $query = $this->db->query($sql);
        $row = $this->db->fetch_array($query);
        if (time()-$row['time']<=1) {
            return false;
        }
        return true;
    }

    /**
     * Get last insert ID
     * @return mixed
     */
    public function insert_id() {
        return $this->db->insert_id();
    }
}
