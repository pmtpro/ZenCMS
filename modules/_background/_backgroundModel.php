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

Class _backgroundModel Extends ZenModel
{

    /**
     * UPDATE CONFIG
     * @param array $arr
     * @param array $option
     * @return bool
     */
    public function _update_config($arr = array(), $option = array()) {
        $locate = '';
        $for = '';
        $funcImport = '';
        $funcExport = '';
        if (!empty($option['locate'])) {
            $locate = $option['locate'];
        }
        if (!empty($option['for'])) {
            $for = $option['for'];
        }
        if (!empty($option['func_import'])) {
            $funcImport = $option['func_import'];
        }
        if (!empty($option['func_export'])) {
            $funcExport = $option['func_export'];
        }
        $arr = $this->db->sqlQuote($arr);
        foreach ($arr as $key => $value) {
            $c = $this->db->num_row($this->db->query("SELECT `id` FROM " . tb() . "config where `key`='$key' and `locate`='$locate' and `for`='$for'"));
            if ($c == 0)
                $this->db->query("INSERT INTO " . tb() . "config SET `key`='$key', `locate`='$locate', `for`='$for'");
            $this->db->query("UPDATE " . tb() . "config SET `value`='" . $value . "', `func_import`='$funcImport', `func_export`='$funcExport' where `key`='$key' and `locate`='$locate' and `for`='$for'");
        }
        return true;
    }

    /**
     * get all widget of a widget group
     * @param $wg
     * @param string $template
     * @return array
     */
    function _get_widget_group($wg, $template = '') {
        if (empty($template)) {
            $template = TEMPLATE;
        }
        $out = array();
        if (!defined('MODULE_NAME')) {
            return $out;
        }
        $_sql = "SELECT * FROM ".tb()."widgets WHERE `wg` = '$wg' AND `template` = '$template' ORDER BY `weight` ASC";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;//return data if cache is exists
        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            $out[] = $this->db->sqlQuoteRm($row);
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $out, 7200);
        return $out;
    }
}
