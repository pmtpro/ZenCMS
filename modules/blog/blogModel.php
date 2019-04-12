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

Class blogModel Extends ZenModel
{
    public $blog_data = array();
    public $blog_insert_id = 0;
    public $accepted_h = array('name', 'title', 'des', 'keyword');
    public $total_result = 0;
    private $init_where = array('status' => "`status`='0'");
    private $status_list = array(0,1,2);

    public function set_filter($colName, $filter) {
        if (empty($colName)) return false;
        $arrQuery = array();
        if (!is_array($filter)) {
            $hash_filter = explode(',', $filter);
        } else {
            $hash_filter = $filter;
        }
        foreach ($hash_filter as $value) {
            if (in_array($value, $this->status_list)) {
                $arrQuery[] = "`$colName` = '" . trim($value) . "'";
            }
        }
        $statement = implode(' or ', $arrQuery);
        if (!empty($statement)) {
            $this->init_where[$colName] = '(' . $statement . ')';
        }
    }

    /**
     * check a blog is exists
     * @param int $id: blog id
     * @return boolean: true - blog is exists, false - blog does not exist
     */
    public function blog_exists($id = 0) {
        if ($id == 0) return true;
        if (!is_numeric($id)) return false;
        $_sql = "SELECT * FROM " . tb() . "blogs WHERE `id` = '$id'" . (!empty($this->init_where['status'])? 'AND ' . $this->init_where['status'] : '');
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query) != 1) {
            return false;
        }
        $this->blog_data = $this->db->fetch_array($query);
        return true;
    }

    /**
     * check a blog name is existed
     *
     * @param string $name: name to check
     * @param bool $without_id: ignore check this ID
     * @return bool
     */
    public function blog_name_exists($name = '', $without_id = false) {
        $name = $this->db->sqlQuote(h($name));
        $sql = "SELECT `id` FROM " . tb() . "blogs WHERE " . (!empty($this->init_where['status'])? $this->init_where['status'] . " AND ":"") . " `name` = '$name'";
        if (!empty($without_id) && is_numeric($without_id)) {
            $sql = $sql . " and `id` != '$without_id'";
        }
        $query = $this->db->query($sql);
        if ($this->db->num_row($query) != 0) {
            return true;
        }
        return false;
    }

    /**
     * retrieve data of a blog ID
     * @param int $sid: Blog ID
     * @param string $what_gets: fields want to get
     * @return array: returns an array is a list of fields that you want to get
     */
    public function get_blog_data($sid = null, $what_gets = '*') {
        if (empty($sid)) return array();
        $select_what = $this->explode_what_gets($what_gets);
        $_sql = "SELECT $select_what FROM " . tb() . "blogs where `id`='$sid'" . (!empty($this->init_where['status'])? 'AND ' . $this->init_where['status'] : '');
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;//return data if cache is exists
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query) != 1) return array();
        $data = $this->db->fetch_array($query);
        $out = $this->gdata($data);
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $out, 600);
        return $out;
    }

    /**
     * @param int $parent: blog ID
     * @param array $options
     * @return array|mixed|null
     */
    public function get_list_blog($parent = 0, $options = array(
        'get' => '*',
        'type' => NULL,
        'order' => array('weight' => 'ASC', 'time' => 'DESC'),
        'limit' => false,
        'both_child' => true
    )) {
        $cats = array();
        $where_arr = array();
        $select_order = "`weight` ASC, `time` DESC";
        $select_limit = '';
        /**
         * set select status
         */
        if (!empty($this->init_where['status'])) {
            $where_arr[] = $this->init_where['status'];
        }
        /**
         * set select parent
         */
        if ($parent !== null) {
            $select_parent = "`parent`='$parent'";
            if (!empty($options['both_child'])) {
                $list_parent = $this->list_folder_id($parent);
                if (!empty($list_parent)) {
                    $where_arr[] = '(' . $select_parent . ' OR `parent` IN (' . implode(',', $list_parent) . '))';
                } else $where_arr[] = $select_parent;
            } else $where_arr[] = $select_parent;
        }

        /**
         * set what get
         */
        if (empty($options['get'])) {
            $options['get'] = '*';
        }
        $get_what = $this->explode_what_gets($options['get'], array('type'));

        /**
         * Set select type
         */
        if (!empty($options['type'])) {
            if (!is_array($options['type'])) {
                $options['type'] = explode(',', $options['type']);
            }
            $select_list = array();
            foreach ($options['type'] as $type) {
                $type = trim($type);
                if (substr($type, 0, 1) == '!')
                    $select_list[] = "`type` != '" . str_replace('!', '', $type) . "'";
                else $select_list[] = "`type` = '$type'";
            }
            $where_arr[] = '(' . implode(' or ', $select_list) . ')';
        } else $options['type'] = array();

        /**
         * set order
         */
        if (!empty($options['order'])) {
            if ($options['order'] == 'RAND') {
                $options['order'] = array('RAND()' => '');
            }
            $order_by = array();
            foreach ($options['order'] as $key => $value) {
                if ($value)
                    $order_by[] = "`$key` $value";
                else $order_by[] = "$key";
            }
            $select_order = implode(', ', $order_by);
        }

        /**
         * set limit
         */
        if (!empty($options['limit'])) {
            $select_limit = 'LIMIT ' . $options['limit'];
        }

        $implode_where = implode(' AND ', $where_arr);
        $_sql_count = "SELECT `id` FROM " . tb() . "blogs " . (!empty($implode_where)? 'WHERE ' . $implode_where:'') . " ORDER BY $select_order";
        /**
         * get cache
         */
        $cache_total = ZenCaching::get($_sql_count);
        if ($cache_total != null) {
            $this->total_result = $cache_total;
        } else {
            $this->total_result = $this->db->num_row($this->db->query($_sql_count));
            /**
             * set the new cache
             */
            ZenCaching::set($_sql_count, $this->total_result, 300);
        }

        $_sql = "SELECT $get_what FROM " . tb() . "blogs " . (!empty($implode_where)? 'WHERE ' . $implode_where:'') . " ORDER BY $select_order $select_limit";

        /**
         * get cache
         */
        $cache_name = $_sql;
        $cache = ZenCaching::get($cache_name);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query)) {
            while ($ro = $this->db->fetch_array($query)) {
                $cats[$ro['id']] = $this->gdata($ro);
            }
            /**
             * set the new cache
             */
            ZenCaching::set($cache_name, $cats, 600);
            return $cats;
        }
        return $cats;
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
        $blogs = array();
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
            $select_where = $where . (!empty($this->init_where['status'])? " AND " . $this->init_where['status']:"");
        }
        $_sql_total = "SELECT $select_what FROM " . tb() . "blogs $select_where ORDER BY $select_order";
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
            $blogs[] = $this->gdata($this->db->fetch_array($re));
        } else {
            while ($row = $this->db->fetch_array($re)) {
                $blogs[] = $this->gdata($row);
            }
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $blogs, 600);
        return $blogs;
    }

    /**
     * @param $what_gets
     * @param array $check_add
     * @return string
     */
    private function explode_what_gets($what_gets, $check_add = array())
    {
        if (empty($what_gets) || trim($what_gets) == '*') {
            return '*';
        }
        if (!is_array($what_gets)) {
            $list = explode(',', $what_gets);
            if (!count($list)) $what_gets = array($what_gets);
            else $what_gets = $list;
        }
        foreach ($what_gets as $k => $what) {
            $what_gets[$k] = trim($what);
        }
        if (!in_array('id', $what_gets) && !in_array('`id`', $what_gets)) {
            $what_gets[] = 'id';
        }
        if (!empty($check_add) && is_array($check_add)) {
            foreach ($check_add as $col) {
                if (!in_array($col, $what_gets) && !in_array('`' . $col . '`', $what_gets)) {
                    $what_gets[] = $col;
                }
            }
        }
        $select_what = implode(', ', $what_gets);
        return $select_what;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function gdata($data = array())
    {
        global $registry;
        $hook = $registry->hook->get('blog');
        $ro = $this->db->sqlQuoteRm($data);
        if (isset($ro['url']) && isset($ro['id'])) {
            $ro['full_url'] = HOME . '/' . $ro['url'] . '-' . $ro['id'] . '.html';
        }
        if (isset($ro['icon'])) {
            $ro['full_icon'] = array();
            /**
             * full_icon hook*
             */
            $ro['full_icon'] =  $hook->loader('full_icon', $ro['full_icon'], array('var' => $ro));
            if (!$ro['full_icon']) $ro['full_icon'] = $this->full_icon($ro['icon']);
        }
        if (isset($ro['content'])) {
            if ($ro['type_data'] == 'html') {
                $ro['content'] = h_decode($ro['content']);
            }
            $ro['sub_content'] = subWords(removeTag($ro['content']), 10);
        }
        if (!empty($ro['time'])) {
            load_helper('time');
            $ro['display_time'] = m_timetostr($ro['time']);
        } else {
            $ro['display_time'] = 'N/A';
        }
        return $ro;
    }

    public function full_icon($icon) {
        return $icon ? _URL_FILES . '/posts/images/' . $icon : _URL_FILES_SYSTEMS . '/images/default/blog_icon.png';
    }

    public function count($sid = 0, $options = array(
                                      'type' => 'post',
                                      'where' => '',
                                      'both_child' => true
                                  )) {
        if (!empty ($sid)) {
            $blog = $this->get_blog_data($sid, 'type, parent');
            if (empty($blog) || $blog['type'] == 'post' || $blog['status'] != 0) return false;
            $parent = $sid;
        } else $parent = 0;

        if ($options['type'] != 'post' && $options['type'] != 'folder') return 0;

        if (empty($options['where'])) $options['where'] = '';
        else $options['where'] = 'and ' . $options['where'];

        if ($parent == 0) {
            $query = $this->db->query("SELECT `id` FROM ".tb()."blogs WHERE " . (!empty($this->init_where['status'])? $this->init_where['status'] . " AND ":"") . " `type` = '" . $options['type'] . "' " . $options['where']);
            $count = $this->db->num_row($query);
            return $count;
        }
        if ($options['both_child']) {
            if ($options['type'] == 'post') {
                $select_type = "and (`type` = 'post' or `type` = 'folder')";
            }
        } else $select_type = "and `type` = '" . $options['type'] . "'";

        $query = $this->db->query("SELECT `id` FROM ".tb()."blogs WHERE " . (!empty($this->init_where['status'])? $this->init_where['status'] . " AND ":"") . " `parent` = '$parent' $select_type " . $options['where']);
        $count = $this->db->num_row($query);
        if ($options['both_child'] == false) return $count;

        while ($row = $this->db->fetch_array($query)) {
            $count += $this->count($sid, $options['type'], $options['both_child']);
        }
        return $count;
    }
    //-------------------------------------------------------------------

    /**
     * get tag and data tag
     * @param int $sid
     * @return array
     */
    public function get_tags($sid)
    {
        $tags = array();
        $_sql = "SELECT * FROM " . tb() . "tags where `sid`='$sid' and `type` = 'blog' order by `id` DESC";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query)) {
            while ($row = $this->db->fetch_array($query)) {
                $tags[$row['id']] = trim($row['tag']);
            }
        }
        $out = $this->db->sqlQuoteRm($tags);
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $out, 600);
        return $out;
    }

    /**
     * get tag and data tag
     * @param int $sid
     * @return array
     */
    public function get_tags_blog($sid) {
        global $registry;
        $hook = $registry->hook->get('blog');
        $tags = array();
        $_sql = "SELECT * FROM " . tb() . "tags WHERE `sid`='$sid' AND `type` = 'blog' ORDER BY `id` DESC";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;
        $query = $this->db->query($_sql);
        if ($this->db->num_row($query)) {
            while ($row = $this->db->fetch_array($query)) {
                $row['full_url'] = HOME . '/search-' . $row['tag'];
                /**
                 * out_tag hook *
                 */
                $tags[] = $hook->loader('out_tag', $this->db->sqlQuoteRm($row));
            }
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $tags, 600);
        return $tags;
    }

    /**
     * @param array $tags
     * @param $sid
     * @return bool
     */
    public function add_tags($tags = array(), $sid)
    {
        if (!is_array($tags)) return false;
        /**
         * load seo library
         */
        $seo = load_library('seo');
        $old_tags = $this->get_tags($sid);
        $new_tags = $this->db->sqlQuote($tags);
        $del_tags = array();
        foreach ($new_tags as $nid => $new_tag) {
            $new_tags[$nid] = trim($new_tag);
        }
        foreach ($old_tags as $oid => $oldt) {
            if (!in_array($oldt, $new_tags)) {
                $del_tags[$oid] = $oldt;
            }
        }
        /**
         * delete old tag
         */
        foreach ($del_tags as $delid => $del_tag) {
            $this->db->query("DELETE FROM " . tb() . "tags where `id`='$delid'");
        }
        /**
         * insert new tag
         */
        foreach ($new_tags as $tag) {
            $tag = trim($tag);
            $query = "SELECT `id` FROM " . tb() . "tags where `sid` = '$sid' and `tag` = '$tag' and `type` = 'blog'";
            $re = $this->db->query($query);
            if (!$this->db->num_row($re)) {
                $url = $seo->url($tag);
                $this->db->query("INSERT INTO " . tb() . "tags SET `sid` = '$sid', `url` = '$url', `tag` = '$tag', `time` = '" . time() . "', `type` = 'blog'");
            }
        }
        /**
         * clean cache
         */
        ZenCaching::clean('get_tags_blog');
        ZenCaching::clean('get_tags');
    }

    /**
     * this function will delete all tag of a blog
     * @param $sid
     */
    public function delete_all_tags($sid) {
        $this->db->query("DELETE FROM " . tb() . "tags where `sid`='$sid' and `type` = 'blog'");
        ZenCaching::clean();
    }

    //----------------------------------------------------------

    public function insert_comment($data)
    {
        if (empty($data['sid'])) return false;
        if (empty($data['uid']) && empty($data['name'])) return false;
        if (empty($data['msg'])) return false;
        $data['time'] = time();
        $data = $this->db->sqlQuote($data);
        $sql = $this->db->_sql_insert(tb() . "blogs_comments", $data);
        $ok = $this->db->query($sql);
        ZenCaching::clean('get_comments');
        return $ok;
    }

    /**
     * Check cmt is exists
     * @param $cmtID
     * @return bool
     */
    public function comment_exists($cmtID) {
        $sql = "SELECT `id` FROM " . tb() . "blogs_comments WHERE `id`='$cmtID'";
        $query = $this->db->query($sql);
        if ($this->db->num_row($query)) {
            return true;
        } else return false;
    }

    /**
     * Get comment data
     * @param $cmtID
     * @param string $what_get
     * @return bool
     */
    public function get_comment_data($cmtID, $what_get = '*') {
        /**
         * check comment exists
         */
        if (!$this->comment_exists($cmtID)) return false;
        if (is_array($what_get)) {
            $list_get = implode(',', $what_get);
        } else $list_get = $what_get;
        $sql = "SELECT $list_get FROM " . tb() . "blogs_comments WHERE `id`='$cmtID'";
        /**
         * get cache
         */
        $cache = ZenCaching::get($sql);
        if ($cache != null) return $cache;
        $query = $this->db->query($sql);
        $out = $this->db->sqlQuoteRm($this->db->fetch_array($query));
        /**
         * set the new cache
         */
        ZenCaching::set($sql, $out, 900);
        return $out;
    }

    /**
     * @param $sid
     * @param bool $limit
     * @return array|mixed|null
     */
    public function get_comments($sid, $limit = false) {
        global $registry;
        load_helper('time');
        if (empty ($sid)) return array();
        /**
         * Get blog hook
         */
        $hook = $registry->hook->get('blog');
        if (empty($limit)) $select_limit = '';
        else $select_limit = 'limit ' . $limit;
        $out = array();
        $sql_total = "SELECT * FROM " . tb() . "blogs_comments WHERE `sid` = '$sid'";
        $this->total_result = $this->db->num_row($this->db->query($sql_total));
        $_sql = $sql_total . " ORDER BY id DESC $select_limit";

        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;

        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            if ($row['uid']) {
                $row['user'] = $this->get('account')->get_user_data($row['uid'], 'id, username, nickname, avatar, last_login, perm');
            }
            /**
             * out_comment_msg hook*
             */
            $row['msg'] = $hook->loader('out_comment_msg', $row['msg']);
            $row['display_time'] = m_timetostr($row['time']);
            $out[] = $this->db->sqlQuoteRm($row);
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $out, 300);
        return $out;
    }

    /**
     * Delete a comment
     * @param $cmtID
     * @return bool
     */
    public function delete_comment($cmtID) {
        if (!$this->comment_exists($cmtID)) return false;
        if ($this->db->query("DELETE FROM " . tb() . "blogs_comments WHERE `id`='$cmtID'")) {
            ZenCaching::clean('get_comments');
            ZenCaching::clean('get_comment_data');
            return true;
        } else return false;
    }

    /**
     * @param $sid
     */
    public function delete_all_comments($sid) {
        $this->db->query("DELETE FROM " . tb() . "blogs_comments WHERE `sid`='$sid'");
        ZenCaching::clean('get_comments');
        ZenCaching::clean('get_comment_data');
    }

    //-------------------------------------------------

    /**
     * @param $sid
     * @return mixed
     */
    public function get_like($sid) {
        $query = $this->db->query("SELECT `id` FROM " . tb() . "likes where `toid`='$sid' and `type` = 'blog'");
        $out = $this->db->num_row($query);
        return $out;
    }

    /**
     * @param $sid
     * @return mixed
     */
    public function get_dislike($sid) {
        $query = $this->db->query("SELECT `id` FROM " . tb() . "dislikes where `toid`='$sid' and `type` = 'blog'");
        return $this->db->num_row($query);
    }

    /**
     * @param $sid
     * @return bool
     */
    public function is_liked($sid) {
        if (isset($this->user['id']) && !empty($this->user['id'])) {
            $uid = $this->user['id'];
            $query = $this->db->query("SELECT `id` FROM " . tb() . "likes where `toid` = '$sid' and `fromid` = '$uid' and `type` = 'blog'");
        } else {
            $ip = client_ip();
            $query = $this->db->query("SELECT `id` FROM " . tb() . "likes where `toid` = '$sid' and `ip` = '$ip' and `fromid` = '0' and `type` = 'blog'");
        }
        if ($this->db->num_row($query)) {
            return true;
        }
        return false;
    }

    /**
     * @param $sid
     * @return bool
     */
    public function is_disliked($sid) {
        if (isset($this->user['id']) && !empty($this->user['id'])) {
            $uid = $this->user['id'];
            $query = $this->db->query("SELECT `id` FROM " . tb() . "dislikes where `toid`='$sid' and `fromid`='$uid' and `type` = 'blog'");
        } else {
            $ip = client_ip();
            $query = $this->db->query("SELECT `id` FROM " . tb() . "dislikes where `toid` = '$sid' and `ip` = '$ip' and `fromid` = '0' and `type` = 'blog'");
        }
        if ($this->db->num_row($query)) {
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @return bool
     */
    function do_like($data = array()) {
        if (!empty($data['fromid']) || !empty($data['ip'])) {
            if (empty($data['toid'])) {
                return false;
            } else {
                if (empty($data['fromid'])) {
                    $data['fromid'] = 0;
                }
                $data['type'] = 'blog';
                $data['time'] = time();
                $data['ip'] = client_ip();
                if ($this->is_disliked($data['toid'])) {
                    if (!empty($data['fromid'])) {
                        $this->db->query("DELETE FROM " . tb() . "dislikes where `fromid` = '" . $data['fromid'] . "' and `toid` = '" . $data['toid'] . "' and `type` = 'blog'");
                    }
                    if (!empty($data['ip'])) {
                        $this->db->query("DELETE FROM " . tb() . "dislikes where `ip` = '" . $data['ip'] . "' and `toid` = '" . $data['toid'] . "' and `fromid` = '0' and `type` = 'blog'");
                    }
                }
                $sql = $this->db->_sql_insert(tb() . 'likes', $data);
                if (!$this->db->query($sql)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $data
     * @return bool
     */
    function do_dislike($data = array()) {
        if (!empty($data['fromid']) || !empty($data['ip'])) {
            if (empty($data['toid'])) {
                return false;
            } else {
                if (empty($data['fromid'])) {
                    $data['fromid'] = 0;
                }
                $data['type'] = 'blog';
                $data['time'] = time();
                $data['ip'] = client_ip();
                if ($this->is_liked($data['toid'])) {
                    if (!empty($data['fromid'])) {
                        $this->db->query("DELETE FROM " . tb() . "likes where `fromid` = '" . $data['fromid'] . "' and `toid` = '" . $data['toid'] . "' and `type` = 'blog'");
                    }
                    if (!empty($data['ip'])) {
                        $this->db->query("DELETE FROM " . tb() . "likes where `ip` = '" . $data['ip'] . "' and `toid` = '" . $data['toid'] . "' and `fromid` = '0' and `type` = 'blog'");
                    }
                }
                $sql = $this->db->_sql_insert(tb() . 'dislikes', $data);
                if (!$this->db->query($sql)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param $sid
     */
    function delete_all_likes($sid) {
        $this->db->query("DELETE FROM " . tb() . "likes where `toid` = '$sid' and `type` = 'blog'");
        ZenCaching::clean();
    }

    /**
     * @param $sid
     */
    function delete_all_dislikes($sid) {
        $this->db->query("DELETE FROM " . tb() . "dislikes where `toid` = '$sid' and `type` = 'blog'");
        ZenCaching::clean();
    }

    //-------------------------------------------------

    /**
     *
     * @return boolean
     */
    public function anti_flood()
    {
        if (!isset($this->user['id'])) {
            $this->user['id'] = 0;
        }
        $uid = $this->user['id'];
        $sql = "SELECT `time` FROM " . tb() . "blogs where " . (!empty($this->init_where['status'])? $this->init_where['status'] . " and ":"") . " `uid`='$uid' order by `time` DESC limit 1";
        $data = $this->db->fetch_array($this->db->query($sql));
        if (time() - $data['time'] < 3) {
            return false;
        }
        return true;
    }

    // -----------------------------------------------------

    /**
     * insert blog data to the database
     * @param array $data
     * @return boolean
     */
    public function insert_blog($data = array())
    {
        global $registry;
        if ($this->anti_flood() == false) return false;
        $seo = load_library('seo');
        if (empty($data['uid'])) $data['uid'] = $registry->user['id'];
        if (empty($data['uid'])) return false;
        if (empty($data['url'])) $data['url'] = $seo->url($data['name']);
        if (empty($data['title'])) $data['title'] = $data['name'];
        if (empty($data['content'])) $data['content'] = '';
        if (empty($data['icon'])) $data['icon'] = '';
        if (empty($data['rel'])) $data['rel'] = '';
        if (empty($data['type'])) $data['type'] = 'post';
        if (empty($data['type_data'])) $data['type_data'] = 'html';
        if (empty($data['time'])) $data['time'] = 0;
        if (empty($data['weight'])) $data['weight'] = 0;
        $data_ins = $this->db->sqlQuote($data);

        $sql = $this->db->_sql_insert(tb() . "blogs", $data_ins);
        $insert_ok = $this->db->query($sql);
        if (!$insert_ok) {
            return false;
        }
        $this->blog_insert_id = $this->db->insert_id();
        $this->db->query("UPDATE " . tb() . "blogs SET `weight`=`weight`+1 where `id`!='" . $this->blog_insert_id . "' and `weight`>='" . $data_ins['weight'] . "'");
        /**
         * clean cache
         */
        ZenCaching::clean();
        return $this->blog_insert_id;
    }

    /**
     * get the last insert id
     * @return int
     */
    public function blog_insert_id()
    {
        return $this->blog_insert_id;
    }

    /**
     *
     * @param array $data
     * @param array or int id $where
     * @return boolean
     */
    public function update_blog($data = array(), $where)
    {
        if (empty($data)) {
            return false;
        }
        $updates = array();
        foreach ($data as $key => $value) {
            if (strlen($value) < 30) {
                if (preg_match('/^\{(.*)\}$/', $value)) {
                    $value = preg_replace('/^\{(.*)\}$/', '$1', $value);
                    $updates[] = "`$key` = $value";
                } else {
                    $value = $this->db->sqlQuote($value);
                    $updates[] = "`$key` = '$value'";
                }
            } else {
                $value = $this->db->sqlQuote($value);
                $updates[] = "`$key` = '$value'";
            }
        }
        $update = implode(',', $updates);
        if (is_numeric($where)) {
            $sid = $where;
            $ok = $this->db->query("UPDATE " . tb() . "blogs SET $update where `id` = '$sid'");
        } else {
            if (!empty($where)) {
                $where = "where $where";
            }
            $ok = $this->db->query("UPDATE " . tb() . "blogs SET $update $where ");
        }
        if ($ok) {
            /**
             * clean cache
             */
            ZenCaching::clean();
            return true;
        }
        return FALSE;
    }

    /**
     * update view blog
     *
     * @param $sid
     */
    public function update_view($sid)
    {
        $this->db->query("UPDATE " . tb() . "blogs SET `view` = `view` + 1 where `id` = '$sid'");
    }

    /**
     * get list parent of a blog
     * @param $sid: ID blog
     * @param string $find_what
     * @return array
     */
    public function list_parent($sid, $find_what = 'url')
    {
        $blog = $this->get_blog_data($sid, array('parent', 'url', 'title'));
        $parent = $blog['parent'];
        $list_parent[] = $blog[$find_what];
        while ($re_get_par = $this->db->query("SELECT `id`, `parent`, `$find_what` from " . tb() . "blogs where " . (!empty($this->init_where['status'])? $this->init_where['status'] . " and ":"") . " `id`='$parent' order by `id` ASC")) {
            $ro_get_parent = $this->db->fetch_array($re_get_par);
            if ($ro_get_parent[$find_what]) {
                $list_parent[] = $ro_get_parent[$find_what];
                $parent = $ro_get_parent['parent'];
            } else
                break;
        }
        asort($list_parent);
        return $list_parent;
    }

    //-------------------------------------------------------------------------------

    /**
     * remove blog to recycle bin
     * @param $sid
     * @return bool
     */
    public function remove_to_recycle_bin($sid)
    {
        /**
         * clean cache
         */
        ZenCaching::clean();
        $this->set_filter('status', array(0,1,2));
        if (!$this->blog_exists($sid)) return false;
        $row = $this->get_blog_data($sid, 'parent,type,id');
        $this->db->query("UPDATE " . tb() . "blogs SET `status` = '2' where `id` = '$sid'");
        if ($row['type'] == 'folder') {
            $query2 = $this->db->query("SELECT `parent`, `type`, `id` FROM " . tb() . "blogs where `parent` = '" . $row['id'] . "'");
            if (!$this->db->num_row($query2)) {
                return true;
            }
            while ($row2 = $this->db->fetch_array($query2)) {
                $this->remove_to_recycle_bin($row2['id']);
            }
        }
        return true;
    }

    //----------------------------------------------------------------

    /**
     * Check link is exists
     * @param int $id
     * @return bool
     */
    public function link_exists($id) {
        $_sql = "SELECT `id` FROM " . tb() . "blogs_links WHERE `id`='$id'";
        if ($this->db->num_row($this->db->query($_sql))) {
            return true;
        }
        return false;
    }

    /**
     * @param $sid
     * @return array|mixed|null
     */
    public function get_links($sid)
    {
        $_sql = "SELECT * FROM " . tb() . "blogs_links WHERE `sid`='$sid' ORDER BY `id` ASC";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return array();
        }
        $links = array();
        while ($row = $this->db->fetch_array($query)) {
            $row['real_link'] = $row['link'];
            $row['link'] = HOME . '/download-link-' . $row['id'];
            $row['short_link'] = 'download-link-' . $row['id'];
            $row['down'] = $row['click'];
            $links[] = $this->db->sqlQuoteRm($row);
        }
        /**
         * set cache
         */
        ZenCaching::set($_sql, $links, 1800);
        return $links;
    }

    /**
     * Get link data
     * @param $lid
     * @return bool|mixed|null
     */
    public function get_link_data($lid)
    {
        $_sql = "SELECT * FROM " . tb() . "blogs_links WHERE `id`='$lid'";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return false;
        }
        $row = $this->db->fetch_array($query);
        $link = $this->db->sqlQuoteRm($row);
        /**
         * set cache
         */
        ZenCaching::set($_sql, $link, 600);
        return $link;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function link_data_standardized($data)
    {
        $data['time'] = time();
        return $this->db->sqlQuote($data);
    }

    /**
     * Insert new link to database
     * @param $data
     * @return bool
     */
    public function insert_link($data) {
        if (empty($data['name']) || empty($data['link']) || empty($data['sid']) || empty($data['uid'])) {
            return false;
        }
        $data = $this->link_data_standardized($data);
        $_sql = $this->db->_sql_insert(tb() . "blogs_links", $data);
        if ($this->db->query($_sql)) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_links');
            ZenCaching::clean('get_link_data');
            return true;
        }
        return false;
    }

    /**
     * @param $lid
     * @param $data
     * @return bool
     */
    public function update_link($lid, $data)
    {
        if (empty($lid) || empty($data['name']) || empty($data['link'])) {
            return false;
        }
        $data = $this->link_data_standardized($data);
        $_sql = $this->db->_sql_update(tb() . "blogs_links", $data, array("`id` = '$lid'"));
        if ($this->db->query($_sql)) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_links');
            ZenCaching::clean('get_link_data');
            return true;
        }
        return false;
    }

    function update_down($fid) {
        $this->db->query("UPDATE " . tb() . "blogs_files SET `down` = `down` + 1 where `id` = '$fid'");
    }

    function update_click($lid) {
        $this->db->query("UPDATE " . tb() . "blogs_links SET `click` = `click` + 1 where `id` = '$lid'");
    }

    /**
     * @param $lid
     * @return bool
     */
    public function delete_link($lid) {
        if (empty($lid)) return false;
        if ($this->db->query("DELETE FROM " . tb() . "blogs_links WHERE `id` = '$lid'")) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_links');
            ZenCaching::clean('get_link_data');
            return true;
        }
        return false;
    }

    /**
     * @param $sid
     */
    public function delete_all_links($sid) {
        $this->db->query("DELETE FROM " . tb() . "blogs_links WHERE `sid` = '$sid'");
        ZenCaching::clean();
    }
    //---------------------------------------------------

    /**
     * Check file is exists from DB
     * @param int $id
     * @return bool
     */
    public function file_exists($id) {
        $_sql = "SELECT `id` FROM " . tb() . "blogs_files WHERE `id`='$id'";
        if ($this->db->num_row($this->db->query($_sql))) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function gfile($data = array()) {
        /**
         * remove quote
         */
        $data = $this->db->sqlQuoteRm($data);
        $dir = _URL_FILES_POSTS . '/files_upload';
        $path = __FILES_PATH . '/posts/files_upload';
        if (isset($data['url'])) {
            if (preg_match('/^https?:\/\//is', $data['url'])) {
                $data['full_url'] = $data['url'];
                $data['full_path'] = $data['url'];
            } else {
                $data['full_url'] = $dir . '/' . $data['url'];
                $data['full_path'] = $path . '/' . $data['url'];
            }
            $data['file_name'] = end(explode('/', $data['url']));
            $data['link'] = HOME . '/download-file-' . $data['id'] . '-' . $data['file_name'];
            $data['short_link'] = 'download-file-' . $data['id'] . '-' . $data['file_name'];
        }
        if (!empty($data['status'])) {
            $data['status'] = @unserialize($data['status']);
        }
        return $data;
    }

    public function get_files($sid) {
        $_sql = "SELECT * FROM " . tb() . "blogs_files WHERE `sid`='$sid' ORDER BY `id` ASC";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return array();
        }
        $files = array();
        while ($row = $this->db->fetch_array($query)) {
            $files[] = $this->gfile($row);
        }
        /**
         * set cache
         */
        ZenCaching::set($_sql, $files, 1800);
        return $files;
    }

    public function get_file_data($fid)
    {
        $_sql = "SELECT * FROM " . tb() . "blogs_files WHERE `id`='$fid'";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return false;
        }
        $row = $this->db->fetch_array($query);
        $file = $this->gfile($row);
        /**
         * set cache
         */
        ZenCaching::set($_sql, $file, 600);
        return $file;
    }

    public function file_data_standardized($data)
    {
        if (isset($data['status'])) {
            $str = serialize($data['status']);
            (string)$data['status'];
            $data['status'] = $str;
        }
        return $this->db->sqlQuote($data);
    }

    public function insert_file($data)
    {
        if (empty($data['name']) || empty($data['uid']) || empty($data['url']) || empty($data['sid']) || empty($data['size'])) {
            return false;
        }
        $data = $this->file_data_standardized($data);
        $data['time'] = time();
        $_sql = $this->db->_sql_insert(tb() . "blogs_files", $data);
        if ($this->db->query($_sql)) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_files');
            ZenCaching::clean('get_file_data');
            return true;
        }
        return false;
    }

    public function update_file($fid, $data)
    {
        if (empty($fid) || empty($data)) {
            return false;
        }
        $data = $this->file_data_standardized($data);
        $_sql = $this->db->_sql_update(tb() . "blogs_files", $data, array("`id` = '$fid'"));
        if ($this->db->query($_sql)) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_files');
            ZenCaching::clean('get_file_data');
            return true;
        }
        return false;
    }

    public function delete_file($fid)
    {
        if (empty($fid)) {
            return false;
        }
        if ($this->db->query("DELETE FROM " . tb() . "blogs_files WHERE `id` = '$fid'")) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_files');
            ZenCaching::clean('get_file_data');
            return true;
        }
        return false;
    }

    public function delete_all_files($sid) {
        $files = $this->get_files($sid);
        foreach($files as $f) {
            $this->delete_file($f['id']);
        }
    }

    //-------------- image -------------------------------------

    public function gimage($data = array())
    {
        $data = $this->db->sqlQuoteRm($data);
        $short = 'files/posts/images';
        $url = HOME . '/' . $short;
        $path = __FILES_PATH . '/posts/images';
        if (isset($data['url'])) {
            if (preg_match('/^https?:\/\//is', $data['url'])) {
                $data['full_url'] = $data['url'];
                $data['full_path'] = $data['url'];
                $data['short_url'] = $data['url'];
            } else {
                $data['full_url'] = $url . '/' . $data['url'];
                $data['full_path'] = $path . '/' . $data['url'];
                $data['short_url'] = $short . '/' . $data['url'];
            }
            $data['file_name'] = end(explode('/', $data['url']));
        }
        return $data;
    }

    public function get_images($sid, $type_get = '')
    {
        $_sql = "SELECT * FROM " . tb() . "blogs_images where `sid`='$sid' and `type` = '$type_get' order by `id` ASC";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        $images = array();
        while ($row = $this->db->fetch_array($query)) {
            $images[] = $this->gimage($row);
        }
        /**
         * set cache
         */
        ZenCaching::set($_sql, $images, 1800);
        return $images;
    }

    public function get_image_data($img_id)
    {
        $_sql = "SELECT * FROM " . tb() . "blogs_images where `id`='$img_id'";
        /**
         * load cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) {
            return $cache;
        }
        $query = $this->db->query($_sql);
        if (!$this->db->num_row($query)) {
            return false;
        }
        $row = $this->db->fetch_array($query);
        $image = $this->gimage($row);
        /**
         * set cache
         */
        ZenCaching::set($_sql, $image, 600);
        return $image;
    }

    public function insert_image($data)
    {
        if (empty($data['url']) || empty($data['sid'])) {
            return false;
        }
        $data = $this->db->sqlQuote($data);
        $data['time'] = time();
        $_sql = $this->db->_sql_insert(tb() . "blogs_images", $data);
        if ($this->db->query($_sql)) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_images');
            ZenCaching::clean('get_image_data');
            return true;
        }
        return false;
    }

    public function delete_image($imgid)
    {
        if (empty($imgid)) {
            return false;
        }
        if ($this->db->query("DELETE FROM " . tb() . "blogs_images where `id` = '$imgid'")) {
            /**
             * clean cache
             */
            ZenCaching::clean('get_images');
            ZenCaching::clean('get_image_data');
            return true;
        }
        return false;
    }

    public function delete_all_images($sid) {
        $images = array_merge($this->get_images($sid), $this->get_images($sid, 'content'));
        foreach ($images as $img) {
            $this->delete_image($img['id']);
        }
    }
    //-----------------------------------------------------------

    public function count_blog($sid = 0) {
        if (!empty($sid)) {
            if (!$this->blog_exists($sid)) {
                return 0;
            } else {
                $blog = $this->get_blog_data($sid, array('parent', 'type'));
                if ($blog['type'] == 'post') return 1;
                $chkID = $blog['id'];
                while ($row = $this->get_blog_data($chkID, array('parent', 'type'))) {
                    if ($row['type'] == 'folder') {
                        $chkID = $row['id'];
                        $this->count_blog();
                    }
                }
            }
        }
    }

    public function count_files($sid = 0)
    {
        if (!$this->blog_exists($sid)) {
            return false;
        }
        if (empty($sid)) {
            $where = '';
        } else {
            $where = "where `sid` = '$sid'";
        }
        $query = $this->db->query("SELECT `id` FROM " . tb() . "blogs_files $where");
        return $this->db->num_row($query);
    }

    public function count_links($sid)
    {
        if (!$this->blog_exists($sid)) {
            return false;
        }
        if (empty($sid)) {
            $where = '';
        } else {
            $where = "where `sid` = '$sid'";
        }
        $query = $this->db->query("SELECT `id` FROM " . tb() . "blogs_links $where");
        return $this->db->num_row($query);
    }

    //---------------------------------------------------

    public function update_type_view($sid, $type_view, $type = '')
    {
        /**
         * clean cache
         */
        ZenCaching::clean('get_blog_data');
        $blog = $this->get_blog_data($sid, 'type_view, type');
        $update['type_view'] = $type_view;
        if ($type == 'post') {
            if ($blog['type'] == 'post') {
                $this->update_blog($update, $sid);
                return true;
            } elseif ($blog['type'] == 'folder') {
                $this->update_blog($update, "`parent` = '$sid' and `type` = 'post'");
                return true;
            }
        } elseif ($type == 'post_and_post') {
            if ($blog['type'] == 'post') {
                $this->update_blog($update, $sid);
                return true;
            } elseif ($blog['type'] == 'folder') {
                $this->update_blog($update, "`parent` = '$sid' and `type` = 'post'");
                $query = $this->db->query("SELECT `id` FROM " . tb() . "blogs where `parent` = '$sid'  and `type` = 'folder'");
                if ($this->db->num_row($query)) {
                    while ($row = $this->db->fetch_array($query)) {
                        $this->update_type_view($row['id'], $type_view, $type);
                    }
                }
                return true;
            }
        } elseif ($type == 'folder') {
            if ($blog['type'] == 'post') {
                return false;
            } elseif ($blog['type'] == 'folder') {
                $this->update_blog($update, $sid);
            }
        } elseif ($type == 'folder_and_folder') {
            if ($blog['type'] == 'post') {
                return false;
            } elseif ($blog['type'] == 'folder') {
                $this->update_blog($update, $sid);
                $query = $this->db->query("SELECT `id` FROM " . tb() . "blogs where `parent` = '$sid'  and `type` = 'folder'");
                if ($this->db->num_row($query)) {
                    while ($row = $this->db->fetch_array($query)) {
                        $this->update_type_view($row['id'], $type_view, $type);
                    }
                }
            }
        }
    }

    public function blog_path_count_parent($parent = 0)
    {
        $count = $this->db->num_row($this->db->query("SELECT 'id' FROM " . tb() . "blogs where `id`='$parent'"));
        return $count;
    }

    public function blog_path_get_parent($parent = 0)
    {
        $row = $this->db->fetch_array($this->db->query("SELECT `parent`,`name`, `title`, `url`,`id`,`type` FROM " . tb() . "blogs where `id`='$parent'"));
        $row['name'] = $this->db->sqlQuoteRm($row['name']);
        $row['title'] = $this->db->sqlQuoteRm($row['title']);
        $row['full_url'] = HOME . '/' . $row['url'] . '-' . $row['id'] . '.html';
        return $row;
    }

    //-----------------------------------------------------------

    /**
     * Get all folder child.
     * Note: Delete cache list_folder_id when have new blog
     * @param int $start
     * @return array|mixed|null
     */
    public function list_folder_id($start = 0) {
        $name_cache = 'list_folder_id_' . $start;
        /**
         * load cache
         */
        $cache = ZenCaching::get($name_cache);
        if ($cache != null) {
            return $cache;
        }
        $out_tree_parent = array();
        $list = $this->get_list_blog($start, array('get' => 'id', 'type' => 'folder', 'both_child' => false));
        foreach ($list as $s) {
            $out_tree_parent[] = $s['id'];
            $out_tree_parent = array_merge($out_tree_parent, $this->list_folder_id($s['id']));
        }
        /**
         * set cache, hold in 20min
         */
        ZenCaching::set($name_cache, $out_tree_parent, 1200);
        return $out_tree_parent;
    }

    public function get_tree_folder($start = 0) {
        $name = 'get_tree_folder-'.$start;
        /**
         * load cache
         */
        $cache = ZenCaching::get($name);
        if ($cache != null) {
            return $cache;
        }
        static $out, $icr;
        if ($start == 0 ) {
            $icr .= '';
        } else {
            $icr .= '-----';
        }
        if (empty($out)) {
            $out[] = 'Chọn một thể loại';
        }
        $list = $this->get_list_blog($start, array('type' => 'folder', 'both_child' => false));
        foreach ($list as $s) {
            $out[$s['id']] = $icr. ' ' .$s['name'];
            $this->get_tree_folder($s['id']);
            $icr = preg_replace('/^\-\-\-\-\-/', '', $icr);
        }
        /**
         * set cache
         */
        ZenCaching::set($name, $out, 600);
        return $out;
    }


    //------------------------------------------------

    public function get_all_folder_recyclebin($limit = false) {
        if (!empty($limit)) {
            $select_limit = "LIMIT ".$limit;
        } else {
            $select_limit = "";
        }
        $out = array();
        $_sql_total = "SELECT * FROM ".tb()."blogs where `type` = 'folder' and `status` = '1' order by `time` DESC ";
        $this->total_result = $this->db->num_row($this->db->query($_sql_total));
        $_sql = $_sql_total . $select_limit;
        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            $row = $this->gdata($row);
            $out[] = $row;
        }
        return $out;
    }

    //------------------------------------------------

    public function delete($sid) {
        $this->only_filter_recycle_bin();
        if (!$this->blog_exists($sid)) {
            return false;
        }
        $blog = $this->get_blog_data($sid);
        if (isset($blog['full_path_icon'])) {
            @unlink($blog['full_path_icon']);
        }
        /**
         * delete tags
         */
        $this->delete_all_tags($sid);
        /**
         * delete comments
         */
        $this->delete_all_comments($sid);
        /**
         * delete likes
         */
        $this->delete_all_likes($sid);
        /**
         * delete dislikes
         */
        $this->delete_all_dislikes($sid);
        /**
         * delete links
         */
        $this->delete_all_links($sid);
        /**
         * delete files
         */
        $this->delete_all_files($sid);
        /**
         * delete images
         */
        $this->delete_all_images($sid);
        /**
         * delete complete
         */
        $this->db->query("DELETE FROM ".tb()."blogs WHERE `id` = '$sid'");
        if ($blog['type'] == 'folder') {
            $this->db->query("UPDATE ".tb()."blogs SET `status` = '1' WHERE `parent` = '$sid'");
        }
        /**
         * clean cache
         */
        ZenCaching::clean();
        return true;
    }

    public function restore($sid) {
        /**
         * clean cache
         */
        ZenCaching::clean();
        return $this->db->query("UPDATE ".tb()."blogs SET `status` = '0' where `id` = '$sid'");
    }
}
