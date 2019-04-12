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

Class accountModel Extends ZenModel
{
    public $msg_conversation_partner;
    public $msg_from;
    public $msg_list_user_partner = array();
    public $msg_total_result;

    function get_user_data($user, $what_get = '*')
    {
        $data = $this->get()->_get_user_data($user, $what_get);
        return $data;
    }

    function update_user($uid, $data)
    {
        return $this->get()->_update_user($uid, $data);
    }

    function get_msg_data($msgid)
    {

        $query = $this->db->query("SELECT * FROM " . tb() . "messages where `id` = '$msgid'");

        if (!$this->db->num_row($query)) {
            return false;
        }
        $row = $this->db->fetch_array($query);

        return $this->db->sqlQuoteRm($row);
    }

    function get_conversation_partner()
    {
        return $this->msg_conversation_partner;
    }

    function list_user_partner()
    {
        return $this->msg_list_user_partner;
    }

    function get_conversations($msgid, $limit = false)
    {
        $this->registry->hook->get('account');

        $msg_data = $this->get_msg_data($msgid);

        if (!$msg_data) {

            return array();
        }
        $bbcode = load_library('bbcode');

        $this->msg_conversation_partner = $this->get_user_data($msg_data['from']);
        $this->msg_list_user_partner[] = $msg_data['to'];
        $this->msg_list_user_partner[] = $msg_data['from'];

        $out = array();
        $select_limit = '';

        if ($limit) {

            $select_limit = 'limit ' . $limit;
        }

        $_sql = "SELECT * FROM " . tb() . "messages where
                                        (`to` = '" . $msg_data['to'] . "' and `from` = '" . $msg_data['from'] . "') or
                                        (`to` = '" . $msg_data['from'] . "' and `from` = '" . $msg_data['to'] . "')";

        $this->msg_total_result = $this->db->num_row($this->db->query($_sql));

        $query = $this->db->query($_sql . " order by `time` DESC $select_limit");

        while ($row = $this->db->fetch_array($query)) {

            $row['msg'] = scan_smiles($bbcode->parse($row['msg']));

            $row['msg'] = $this->registry->hook->loader('out_message', $row['msg']);

            $row['user'] = $this->get_user_data($row['from']);

            $out[$row['id']] = $this->db->sqlQuoteRm($row);
        }

        asort($out);
        return $out;
    }

    function get_inboxs($limit = false)
    {

        $this->registry->hook->get('account');

        $user = $this->user;

        $select_limit = '';
        $out = array();

        if (!empty($limit)) {

            $select_limit = 'limit ' . $limit;
        }

        $_sql = "SELECT MAX(id) as cid FROM " . tb() . "messages  where `to` = '" . $user['username'] . "' GROUP BY `from` order by `readed` ASC, `cid` DESC";

        $this->msg_total_result = $this->db->num_row($this->db->query($_sql));

        $query = $this->db->query($_sql." $select_limit");

        while ($inboxs = $this->db->fetch_array($query)) {

            $cid = $inboxs['cid'];

            $re = $this->db->query("SELECT * FROM " . tb() . "messages where `id` = '$cid'");

            $row = $this->db->fetch_array($re);

            $numword = 15;

            /**
             * num_word_sub_message hook *
             */
            $numword = $this->registry->hook->loader('num_word_sub_message', $numword);

            $row['sub_msg'] = scan_smiles(subwords($row['msg'], $numword));

            /**
             * out_sub_message hook *
             */
            $row['sub_msg'] = $this->registry->hook->loader('out_sub_message', $row['sub_msg']);

            $row['user'] = $this->get_user_data($row['from']);

            $out[] = $this->db->sqlQuoteRm($row);

        }
        return $out;
    }

    function mark_read_conversation($partner) {

        $user = $this->user;

        $this->db->query("UPDATE ".tb()."messages SET `readed` = '1' where `from` = '$partner' and `to` = '".$user['username']."'");

    }

    function insert_message($data)
    {

        $data = $this->db->sqlQuote($data);

        $sql = $this->db->_sql_insert(tb() . 'messages', $data);

        return $this->db->query($sql);

    }

    function insert_id()
    {
        return $this->db->insert_id();
    }
}
