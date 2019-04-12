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

Class searchModel Extends ZenModel
{
    function like_result($key) {
        if (empty($key)) {
            return array();
        }
    }

    function count_tag($url) {
        $sql = "SELECT `id` FROM ".tb()."tags WHERE `url`='$url'";
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
        $sql = "SELECT `id` FROM ".tb()."tags WHERE `tag` != '$tag' AND `tag` LIKE '%$tag%'";
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

            $ro['full_url'] = HOME . '/' . $ro['url'] . '-' . $ro['id'] . '.html';

        }
        if (isset($ro['icon'])) {

            if (empty($ro['icon'])) {

                $ro['full_icon'] = _BASE_TEMPLATE . '/images/' . tplConfig('default_icon');

            } else {

                $ro['full_icon'] = HOME . '/files/posts/images/' . $ro['icon'];
            }
        }

        if (isset($ro['content'])) {

            $ro['sub_content'] = subWords(removeTag($ro['content']), 10);
        }

        return $ro;
    }
}