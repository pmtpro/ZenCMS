<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

class ZenConfig
{
    private static $instance;
    public $db;
    /**
     * construct
     */
    function __construct($registry) {
        $this->db = $registry->db;
    }

    /**
     * @param $registry
     * @return ZenConfig
     */
    public static function getInstance($registry) {
        if (!self::$instance) {
            self::$instance = new ZenConfig($registry);
        }
        return self::$instance;
    }

    /**
     * load config from database
     * @return string array boolean
     */
    public function loader() {
        global $zen, $registry;
        $tmpConfig = array();
        $table_prefix = $zen['config']['table_prefix'];
        $re_sysConfig = $registry->db->query("SELECT * FROM " . $table_prefix . "config WHERE `for`='' and `locate`=''");
        while ($ro_sysConfig = $registry->db->fetch_array($re_sysConfig)) {
            /**
             * check if export function is existing
             */
            if (!empty($ro_sysConfig['func_export']) && function_exists($ro_sysConfig['func_export'])) {
                $ro_sysConfig['value'] = call_user_func_array($ro_sysConfig['func_export'], array($ro_sysConfig['value']));
            }
            $tmpConfig[$ro_sysConfig['key']] = $ro_sysConfig['value'];
        }
        return $tmpConfig;
    }

    /**
     * reload config when changed
     */
    public function reload() {
        global $zen;
        $zen['config']['fromDB'] = $this->loader();
    }

    public function getValue($key, $for = '', $locate = '') {
        global $zen, $registry;
        $selectKey = '';
        if (!empty($key)) {
            $selectKey = "`key`='$key' and";
        }
        $table_prefix = $zen['config']['table_prefix'];
        $sql = "SELECT * FROM " . $table_prefix . "config WHERE $selectKey `for`='$for' and `locate`='$locate'";
        $query = $registry->db->query($sql);
        $numRow = $registry->db->num_row($query);
        if (!$numRow) {
            return false;
        }

        if ($numRow == 1 && !empty($key)) {
            $row = $registry->db->fetch_array($query);
            if (!empty($row['func_export']) && function_exists($row['func_export'])) {
                $row['value'] = call_user_func_array($row['func_export'], array($row['value']));
            }
            return $row['value'];
        }

        $outArr = array();
        while ($row = $registry->db->fetch_array($query)) {
            /**
             * check if export function is existing
             */
            if (!empty($row['func_export']) && function_exists($row['func_export'])) {
                $row['value'] = call_user_func_array($row['func_export'], array($row['value']));
            }
            $outArr[$row['key']] = $row['value'];
        }
        return $outArr;
    }

    /**
     * update system config.
     * @param array $updateData
     * @param string $funcExport
     * @param string $funcImport
     * @return mixed
     */
    public function updateConfig($updateData, $funcExport = '', $funcImport = '') {
        return $this->_update($updateData, array('func_import'=>$funcImport, 'func_export'=>$funcExport));
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function getConfig($key) {
        return $this->getValue($key);
    }

    /**
     * UPDATE CONFIG
     * @param array $arr
     * @param array $option
     * @return bool
     */
    public function _update($arr = array(), $option = array()) {
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
            if (is_array($value)) {
                if (isset($value['data'])) {
                    if (isset($value['func_export'])) $funcExport = $value['func_export'];
                    if (isset($value['func_import'])) $funcImport = $value['func_import'];
                    $value = $value['data'];
                }
            }

            $c = $this->db->num_row($this->db->query("SELECT `id` FROM " . tb() . "config where `key`='$key' and `locate`='$locate' and `for`='$for'"));
            if ($c == 0)
                $this->db->query("INSERT INTO " . tb() . "config SET `key`='$key', `locate`='$locate', `for`='$for'");
            $this->db->query("UPDATE " . tb() . "config SET `value`='" . $value . "', `func_import`='$funcImport', `func_export`='$funcExport' where `key`='$key' and `locate`='$locate' and `for`='$for'");
        }
        return true;
    }

    /**
     * update module config. Save as data in db
     * @param string $module
     * @param array $updateData
     * @param string $funcExport
     * @param string $funcImport
     * @return mixed
     */
    public function updateModuleConfig($module, $updateData, $funcExport = '', $funcImport = '') {
        return $this->_update($updateData, array('for' => 'module', 'locate'=> $module,'func_import'=>$funcImport, 'func_export'=>$funcExport));
    }

    /**
     * @param string $module
     * @param string $key
     * @return bool|mixed
     */
    public function getModuleConfig($module, $key = '') {
        return $this->getValue($key, 'module', $module);
    }

    /**
     * update template config. Save as data in DB
     * @param string $template
     * @param array $updateData
     * @param string $funcExport
     * @param string $funcImport
     * @return mixed
     */
    public function updateTemplateConfig($template, $updateData, $funcExport = '', $funcImport = '') {
        global $registry;
        return $this->_update($updateData, array('for' => 'template', 'locate'=> $template,'func_import'=>$funcImport, 'func_export'=>$funcExport));
    }

    /**
     * @param string $template
     * @param string $key
     * @return bool|mixed
     */
    public function getTemplateConfig($template, $key = '') {
        return $this->getValue($key, 'template', $template);
    }
}
