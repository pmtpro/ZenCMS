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

Class downloadModel Extends ZenModel
{

    function get_file_data($fid)
    {

        $query = $this->db->query("SELECT * FROM " . tb() . "blogs_files where `id` = '$fid'");

        if (!$this->db->num_row($query)) {
            return false;
        }

        $row = $this->db->fetch_array($query);

        $row = $this->db->sqlQuoteRm($row);

        $row['full_url'] = _URL_FILES_POSTS . '/files_upload/' . $row['url'];

        $row['full_path'] = __FILES_PATH . '/posts/files_upload/' . $row['url'];

        $row['file_name'] = end(explode('/', $row['url']));

        return $row;
    }

    function get_link_data($lid)
    {

        $query = $this->db->query("SELECT * FROM " . tb() . "blogs_links where `id` = '$lid'");

        if (!$this->db->num_row($query)) {
            return false;
        }

        $row = $this->db->fetch_array($query);

        $row = $this->db->sqlQuoteRm($row);

        return $row;
    }

    function update_down($fid)
    {
        $this->db->query("UPDATE " . tb() . "blogs_files SET `down` = `down` + 1 where `id` = '$fid'");
    }

    function update_click($lid)
    {
        $this->db->query("UPDATE " . tb() . "blogs_links SET `click` = `click` + 1 where `id` = '$lid'");
    }
}
