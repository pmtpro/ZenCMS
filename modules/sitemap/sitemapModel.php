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

Class sitemapModel Extends ZenModel
{

    function get_last_update() {

        $sql = "SELECT * FROM " . tb() . "blogs where `type` = 'post' order by `time` DESC limit 1";

        $query = $this->db->query($sql);

        if (!$this->db->num_row($query)) {
            return time();
        }

        $row = $this->db->fetch_array($query);

        return $row;
    }

    public function get_folders() {

        $sql = "SELECT * FROM " . tb() . "blogs where `type` = 'folder' order by `time` DESC";

        $query = $this->db->query($sql);

        if (!$this->db->num_row($query)) {

            return array();
        }

        $out = array();

        while ($row = $this->db->fetch_array($query)) {

            $out[] = $this->gdata($row);
        }
        return $out;
    }

    public function get_posts() {

        $sql = "SELECT * FROM " . tb() . "blogs where `type` = 'post' order by `time` DESC";

        $query = $this->db->query($sql);

        if (!$this->db->num_row($query)) {

            return array();
        }

        $out = array();

        while ($row = $this->db->fetch_array($query)) {

            $out[] = $this->gdata($row);
        }
        return $out;
    }

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
?>