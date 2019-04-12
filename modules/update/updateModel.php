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

Class updateModel Extends ZenModel
{

    function alter_table_users()
    {
        $ok = $this->db->query("ALTER TABLE `zen_cms_users` MODIFY `perm` VARCHAR(50)");
        if ($ok) {
            return TRUE;
        }
        return FALSE;
    }

    function change_user_perm()
    {
        $ok = array();

        $user_perm = array(1 => 'user_lock',
            2 => 'user_need_active',
            3 => 'user_actived',
            4 => 'mod',
            5 => 'admin'
        );
        $query = $this->db->query("SELECT `id`, `perm` FROM " . tb() . "users order by `id` ASC");

        if ($this->db->num_row($query)) {
            while ($user = $this->db->fetch_array($query)) {
                if (isset($user_perm[$user['perm']])) {
                    $new_perm = $user_perm[$user['perm']];
                    $ok[$user['id']] = $this->db->query("UPDATE " . tb() . "users SET `perm`='$new_perm' where `id`='" . $user['id'] . "'");
                }
            }

            if (in_array(FALSE, $ok)) {
                return false;
            }
            return TRUE;
        }
        return TRUE;
    }

    function check_store_title()
    {
        $ok = array();

        $query = $this->db->query("SELECT `id`, `title`, `name` FROM " . tb() . "stores order by `id` ASC");

        if ($this->db->num_row($query)) {
            while ($store = $this->db->fetch_array($query)) {
                if (empty($store['title'])) {
                    $ok[$store['id']] = $this->db->query("UPDATE " . tb() . "stores SET `title`='" . $store['name'] . "' where `id`='" . $store['id'] . "'");
                }
            }

            if (in_array(FALSE, $ok)) {
                return false;
            }
            return TRUE;
        }
        return TRUE;
    }

    function change_ref_to_rel()
    {
        if ($this->check_colum_is_exists(tb() . 'stores', 'ref') == true) {
            if (!$this->db->query("ALTER  TABLE " . tb() . "stores CHANGE  `ref`  `rel` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")) {
                return false;
            }
        }
        return true;
    }

    function change_zen_confirm_to_ss_zen_token()
    {
        if ($this->check_colum_is_exists(tb() . 'users', 'zen_confirm') == true) {
            if (!$this->db->query("ALTER TABLE  " . tb() . "users CHANGE  `zen_confirm`  `ss_zen_login` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")) {
                return false;
            }
        }
        return true;
    }

    function change_code_confirm_to_security_code()
    {
        if ($this->check_colum_is_exists(tb() . 'users', 'code_confirm') == true) {
            if (!$this->db->query("ALTER TABLE  `zen_cms_users` CHANGE  `code_confirm`  `security_code` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")) {
                return false;
            }
        }
        return true;
    }

    function change_recycle_bin()
    {

        if ($this->check_colum_is_exists(tb() . 'stores', 'recycle_bin') == false) {

            if (!$this->db->query("ALTER TABLE " . tb() . "stores ADD `recycle_bin` INT NOT NULL AFTER  `color`")) {
                return false;
            }
        }
        $this->db->query("UPDATE " . tb() . "stores SET `type`='folder', `recycle_bin`='1' where `type`='recycle_bin' and `content`=''");
        $this->db->query("UPDATE " . tb() . "stores SET `type`='post', `recycle_bin`='1' where `type`='recycle_bin' and `content`!=''");
        return true;
    }

    function rename_table_links()
    {

        if (!$this->table_exist("zen_cms_links")) {
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `zen_cms_links` TO `zen_cms_stores_links` ")) {
            return false;
        }
        return true;
    }

    function rename_table_files()
    {

        if (!$this->table_exist("zen_cms_files")) {
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `zen_cms_files` TO `zen_cms_stores_files` ")) {
            return false;
        }
        return true;
    }

    function add_colum_uid_links()
    {

        if ($this->check_colum_is_exists(tb() . 'stores_links', 'uid')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_stores_links` ADD  `uid` INT NOT NULL AFTER  `id`")) {
            return true;
        }
        return false;
    }



    function add_colum_uid_files()
    {

        if (!$this->check_colum_is_exists(tb() . 'stores_files', 'uid')) {

            $this->db->query("ALTER TABLE  `zen_cms_stores_files` ADD  `uid` INT NOT NULL AFTER  `id`");
        }
        if(!$this->check_colum_is_exists(tb() . 'stores_files', 'status')) {

            $this->db->query("ALTER TABLE  `zen_cms_stores_files` ADD  `status` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `down`");

        }
        if(!$this->check_colum_is_exists(tb() . 'stores_files', 'type')) {

            $this->db->query("ALTER TABLE  `zen_cms_stores_files` ADD  `type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `down`");

        }
        $query = $this->db->query("SELECT `url`, `id` FROM `zen_cms_stores_files` order by `id` ASC ");

        while($row = $this->db->fetch_array($query)) {

            $ext = get_ext($row['url']);
            $this->db->query("UPDATE `zen_cms_stores_files` SET `type` = '$ext' where `id` = '".$row['id']."'");
        }

        return true;
    }

    function update_uid_links_files()
    {
        $uadmin = $this->get_id_admin();
        $ok_link = $this->db->query("UPDATE " . tb() . "stores_links SET `uid` = '$uadmin'");
        $ok_file = $this->db->query("UPDATE " . tb() . "stores_files SET `uid` = '$uadmin'");
        if ($ok_link && $ok_file) {
            return true;
        }
        return false;
    }

    function add_colum_ip_likes()
    {

        if ($this->check_colum_is_exists(tb() . 'likes', 'ip')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_likes` ADD  `ip` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `type`")) {
            return true;
        }
        return false;
    }

    function add_colum_ip_dislikes()
    {

        if ($this->check_colum_is_exists(tb() . 'dislikes', 'ip')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_dislikes` ADD  `ip` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `type`")) {
            return true;
        }
        return false;
    }


    function add_colum_smiles_to_users()
    {

        if ($this->check_colum_is_exists(tb() . 'users', 'smiles')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_users` ADD  `smiles` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `level`")) {
            return true;
        }
        return false;
    }

    function rename_table_images()
    {

        if (!$this->table_exist("zen_cms_images")) {
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `zen_cms_images` TO `zen_cms_stores_images` ")) {
            return false;
        }
        return true;
    }

    function add_colum_uid_to_stores_images()
    {

        if ($this->check_colum_is_exists(tb() . 'stores_images', 'uid')) {
            $this->db->query("UPDATE `zen_cms_stores_images` SET `uid` = '".$this->get_id_admin()."'");
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_stores_images` ADD  `uid` INT NOT NULL AFTER  `id`")) {
            $this->db->query("UPDATE `zen_cms_stores_images` SET `uid` = '".$this->get_id_admin()."'");
            return true;
        }
        return false;
    }

    function add_row_templates_to_config() {

        if (!get_config('templates')) {

            if ($this->db->query("INSERT INTO ".tb()."config SET `key` = 'templates'")) {
                return true;
            }
            return false;
        }
        return false;

    }

    function rename_comments_to_stores_comments()
    {

        if (!$this->table_exist("zen_cms_comments")) {
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `zen_cms_comments` TO `zen_cms_stores_comments` ")) {
            return false;
        }
        return true;
    }

    function add_colum_wgid_to_widgets()
    {

        if ($this->check_colum_is_exists(tb() . 'widgets', 'name') == true) {

            $this->db->query("ALTER TABLE  `zen_cms_widgets` CHANGE  `name`  `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
        }

        if ($this->check_colum_is_exists(tb() . 'widgets', 'code') == true) {

            $this->db->query("ALTER TABLE  `zen_cms_widgets` CHANGE  `code`  `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
        }
        if ($this->check_colum_is_exists(tb() . 'widgets', 'pos') == true) {

            $this->db->query("ALTER TABLE  `zen_cms_widgets` CHANGE  `pos`  `weight` INT NOT NULL");
        }

        if ($this->check_colum_is_exists(tb() . 'widgets', 'wg')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_widgets` ADD  `wg` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id`")) {
            return true;
        }
        return false;
    }

    function rename_table_hot_link()
    {

        if (!$this->table_exist("zen_cms_hot_link")) {
            $this->change_ref_link_to_rel();
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `zen_cms_hot_link` TO `zen_cms_link_list` ")) {
            return false;
        }
        $this->change_ref_link_to_rel();
        return true;
    }

    function change_ref_link_to_rel()
    {
        if ($this->check_colum_is_exists(tb() . 'link_list', 'ref') == true) {
            if (!$this->db->query("ALTER  TABLE " . tb() . "link_list CHANGE  `ref`  `rel` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")) {
                return false;
            }
        }
        return true;
    }

    function add_time_to_link_list() {

        if ($this->check_colum_is_exists(tb() . 'link_list', 'time')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_link_list` ADD  `time` INT NOT NULL AFTER  `type`")) {
            return true;
        }
        return false;

    }

    function add_style_to_link_list() {

        if ($this->check_colum_is_exists(tb() . 'link_list', 'style')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_link_list` ADD  `style`  VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `type`")) {
            return true;
        }
        return false;
    }

    function add_tags_to_link_list() {

        if ($this->check_colum_is_exists(tb() . 'link_list', 'tags')) {
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_link_list` ADD  `tags`  TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `type`")) {
            return true;
        }
        return false;
    }

    function add_type_to_tags() {

        if ($this->check_colum_is_exists(tb() . 'tags', 'type')) {
            $this->update_type_tags();
            return true;
        }
        if ($this->db->query("ALTER TABLE  `zen_cms_tags` ADD  `type`  VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")) {
            $this->update_type_tags();
            return true;
        }
        return false;
    }

    function rename_table($name, $to)
    {

        if (!$this->table_exist("$name")) {
            return true;
        }
        if (!$this->db->query("RENAME TABLE  `$name` TO `$to` ")) {
            return false;
        }
        return true;
    }

    function update_type_tags() {
        $this->db->query("UPDATE ".tb()."tags SET `type` = 'store'");
    }


    function get_id_admin()
    {

        $query = $this->db->query("SELECT `id` FROM " . tb() . "users where `perm` = 'admin' order by `id` ASC limit 1");
        if ($this->db->num_row($query)) {
            $user = $this->db->fetch_array($query);
            return $user['id'];
        }
        return false;
    }

    function table_exist($table){

        $sql = "show tables like '".$table."'";
        $res = $this->db->query($sql);
        return ($this->db->num_rows($res) > 0);
    }

    function check_colum_is_exists($table, $colum)
    {
        $exists = false;
        $columns = $this->db->query("show columns from $table");
        while ($c = $this->db->fetch_assoc($columns)) {
            if ($c['Field'] == $colum) {
                $exists = true;
                break;
            }
        }
        return $exists;
    }

}

?>