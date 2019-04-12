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

Class adminModel Extends ZenModel
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

    /**
     * @param string $perm
     * @param bool $limit
     * @return array
     */
    public function get_users($perm = '', $limit = false) {

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

        $this->total_result = $this->db->num_row($this->db->query($sql_total));

        $sql = $sql_total. " $select_limit";

        $query = $this->db->query($sql);

        if ($this->db->num_row($query)) {

            while ($row = $this->db->fetch_array($query)) {

                $row = $this->db->sqlQuoteRm($row);

                $row = _handle_user_data($row);

                $users[] = $row;
            }
        }
        return $users;
    }

    /**
     * @param $user
     * @param string $what_get
     * @return bool
     */
    function get_user_data($user, $what_get = '*') {

        return $this->get()->_get_user_data($user, $what_get);
    }

    function get_widget_group($wg) {

        return $this->get()->_get_widget_group($wg);
    }

    /**
     * get widget data by ids widget
     *
     * @param $wi
     * @return mixed
     */
    function get_widget_data($wi = false) {

        $_sql = "SELECT * FROM ".tb()."widgets where `id` = '$wi'";

        $query = $this->db->query($_sql);

        $row = $this->db->fetch_array($query);

        return $this->db->sqlQuoteRm($row);
    }

    function update_widget($wid, $data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_update(tb().'widgets', $data, "id = '$wid'");

        if ($this->db->query($_sql) ) {

            return true;
        }
        return false;
    }

    function insert_widget($data) {

        if(empty($data['content'])) {

            return false;
        }

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_insert(tb().'widgets', $data);

        if ($this->db->query($_sql) ) {

            return true;
        }
        return false;
    }

    function delete_widget($wid) {

        $_sql = "DELETE FROM ".tb()."widgets where `id` = '$wid'";

        return $this->db->query($_sql);
    }

    function get_link_list($type = '') {

        return $this->get()->_get_link_list($type);
    }

    function get_link_data($lid) {

        $_sql = "SELECT * FROM ".tb()."link_list where `id` = '$lid'";

        $query = $this->db->query($_sql);

        $row = $this->db->fetch_array($query);

        $row['tags'] = unserialize($row['tags']);

        $row['tag_start'] = h_decode($row['tags']['tag_start']);

        $row['tag_end'] = h_decode($row['tags']['tag_end']);

        return $this->db->sqlQuoteRm($row);
    }

    function update_link($lid, $data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_update(tb().'link_list', $data, "`id` = '$lid'");

        /**
         * clean cache
         */
        ZenCaching::clean();

        return $this->db->query($_sql);
    }
    function insert_link($data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_insert(tb().'link_list', $data);

        /**
         * clean cache
         */
        ZenCaching::clean();

        return $this->db->query($_sql);
    }

    function delete_link($lid) {

        $_sql = "DELETE FROM ".tb()."link_list WHERE `id` = '$lid'";

        return $this->db->query($_sql);
    }

    function gproduct($data) {

        if (empty($data['url'])) {

            return $data;
        }

        $data['full_path'] = __FILES_PATH . '/products/' . $data['url'];
        $data['full_url'] = _URL_FILES . '/products/' . $data['url'];

        if (isset($data['des'])) {

            $data['des'] = h_decode($data['des']);
        }

        return $data;
    }

    function get_product($id) {

        $_sql = "SELECT * FROM ".tb()."products WHERE `id` = '$id'";

        $query = $this->db->query($_sql);

        if ($this->db->num_row($query)) {

            $row = $this->db->fetch_array($query);

            $row = $this->db->sqlQuoteRm($row);

            $row = $this->gproduct($row);

            return $row;
        }
        return false;
    }

    function get_list_products($limit = false) {

        $out = array();

        if ($limit) {

            $select_limit = "LIMIT " . $limit;

        } else {

            $select_limit = '';
        }

        $_sql = "SELECT * FROM ".tb()."products order by `id` DESC, `time` DESC " . $select_limit;

        $query = $this->db->query($_sql);

        while($row = $this->db->fetch_array($query)) {

            $row = $this->db->sqlQuoteRm($row);

            $row = $this->gproduct($row);

            $out[] = $row;
        }
        return $out;
    }

    function insert_product($data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_insert(tb().'products', $data);

        return $this->db->query($_sql);
    }

    function update_product($id, $data) {

        $data = $this->db->sqlQuote($data);

        $_sql = $this->db->_sql_update(tb().'products', $data, "`id` = '$id'");

        return $this->db->query($_sql);

    }

    function delete_product($id) {

        $_sql = "DELETE FROM ".tb()."products where `id` = '$id'";

        return $this->db->query($_sql);
    }
}
