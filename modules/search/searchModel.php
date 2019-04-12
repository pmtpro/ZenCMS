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

Class searchModel Extends ZenModel
{
    function like_result($key) {

        if (empty($key)) {

            return array();
        }


    }

    function count_tag($url) {

        $sql = "SELECT `id` FROM ".tb()."tags where `url`='$url'";

        $query = $this->db->query($sql);

        return $this->db->num_row($query);
    }

    function insert_tag($data) {

        if (empty($data['url'])) {
            return false;
        }

        $data['time'] = time();

        $_sql = $this->db->_sql_insert(tb() . "tags", $data);

        if ($this->db->query($_sql)) {

            return true;
        }
        return false;
    }

    function count_like_tag($tag) {

        $sql = "SELECT `id` FROM ".tb()."tags WHERE `tag` != '$tag' and `tag` LIKE '%$tag%'";

        $query = $this->db->query($sql);

        return $this->db->num_row($query);

    }

    function keyword_like_tag($tag, $limit = 10) {

        $out = array();

        $select_limit = '';

        if (!empty($limit)) {

            $select_limit = 'limit '.$limit;
        }
        $sql = "SELECT * FROM ".tb()."tags WHERE `tag` != '$tag' and `tag` LIKE '%$tag%' $select_limit";

        $query = $this->db->query($sql);

        while($row = $this->db->fetch_array($query)) {

            $out[] = $this->db->sqlQuoteRm($row['tag']);
        }
        return $out;
    }

    function count_blog_like_tag($data) {

        $sql = "SELECT `id` FROM ".tb()."blogs WHERE `type`='post'  and
								(`name` LIKE '%".$data['name']."%'
								or `content` LIKE '%".$data['content']."%'
								or `title` LIKE '%".$data['title']."%'
								or `url` LIKE '%".$data['url']."%')";

        $query = $this->db->query($sql);

        return $this->db->num_row($query);
    }

    function result_blog_like_tag($data, $limit = '') {

        $select_limit = '';
        $out = array();

        if (!empty($limit)) {

            $select_limit = 'limit '.$limit;
        }


        $sql = "SELECT * FROM ".tb()."blogs WHERE `type`='post'  and
								(`name` LIKE '%".$data['name']."%'
								or `content` LIKE '%".$data['content']."%'
								or `title` LIKE '%".$data['title']."%'
								or `url` LIKE '%".$data['url']."%') $select_limit";

        $query = $this->db->query($sql);

        while ($row = $this->db->fetch_array($query)) {

            $out[] = $this->gdata($row);
        }
        return $out;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function gdata($data = array())
    {

        $ro = $this->db->sqlQuoteRm($data);

        if (isset($ro['url'])) {

            $ro['full_url'] = _HOME . '/' . $ro['url'] . '-' . $ro['id'] . '.html';

        }
        if (isset($ro['icon'])) {

            if (empty($ro['icon'])) {

                $ro['full_icon'] = _HOME . '/templates/' . _TEMPLATE . '/images/' . tpl_config('default_icon');

            } else {

                $ro['full_icon'] = _HOME . '/files/posts/images/' . $ro['icon'];
            }
        }

        if (isset($ro['content'])) {

            $ro['sub_content'] = subwords(removeTag($ro['content']), 10);
        }

        return $ro;
    }
}