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

class ZenConfig
{
    private static $instance;

    /**
     * construct
     */
    function __construct() {}

    /**
     * get instance
     * @return object
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ZenConfig();
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
        global $registry;
        return $registry->model->get('_background')->_update_config($updateData, array('func_import'=>$funcImport, 'func_export'=>$funcExport));
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function getConfig($key) {
        return $this->getValue($key);
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
        global $registry;
        return $registry->model->get('_background')->_update_config($updateData, array('for' => 'module', 'locate'=> $module,'func_import'=>$funcImport, 'func_export'=>$funcExport));
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
        return $registry->model->get('_background')->_update_config($updateData, array('for' => 'template', 'locate'=> $template,'func_import'=>$funcImport, 'func_export'=>$funcExport));
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
