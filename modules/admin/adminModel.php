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

Class adminModel Extends ZenModel
{

    public $total_result;
    public $server_host = 'http://zencms.vn';
    public $server_router_feed = 'blog/api/list?is-ajax-request';
    public $server_router_addon_check_update = 'addons/api/check_update';
    public $server_router_version_check_update = 'ZenCmsVersionManager/api/get_last_version?is-ajax-request';

    public function get_token_api() {
        $syncAcc = dbConfig('zencmsvnSync-user');
        if (empty($syncAcc) || empty($syncAcc['token'])) {
            return false;
        }
        return $syncAcc['token'];
    }

    public function load_new_feed_post($catID = 0, $num = 10, $page = 1)
    {
        $url = $this->server_host . '/' . $this->server_router_feed . '&type=post&catID=' . $catID . '&num=' . $num . '&page=' . $page;
        $cache = ZenCaching::get($url);
        if ($cache != null) {
            return $cache;
        }
        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if ($error) {
            return false;
        }
        $result_decoded = json_decode($result);
        ZenCaching::set($url, $result_decoded->data, 3600*12);//12 hour
        return $result_decoded->data;
    }

    public function load_new_feed_cat($catID = 0, $num = 10, $page = 1)
    {
        $url = $this->server_host . '/' . $this->server_router_feed . '&type=cat&catID=' . $catID . '&num=' . $num . '&page=' . $page;
        $cache = ZenCaching::get($url);
        if ($cache != null) {
            return $cache;
        }
        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if ($error) {
            return false;
        }
        $result_decoded = json_decode($result);
        ZenCaching::set($url, $result_decoded->data, 3600*24);//1 day
        return $result_decoded->data;
    }

    public function addon_check_update($pid, $type, $ver) {
        $url = $this->server_host . '/' . $this->server_router_addon_check_update . '?package=' . $pid . '&type=' . $type . '&version=' . $ver . '&token=' . urlencode($this->get_token_api());
        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if ($error) {
            return false;
        }
        $result_decoded = json_decode($result);
        return $result_decoded;
    }

    public function version_check_update() {
        $url = $this->server_host . '/' . $this->server_router_version_check_update;
        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if ($error) {
            return false;
        }
        $result_decoded = json_decode($result);
        return $result_decoded;
    }

    /**
     * Delete module config from table config
     * @param string $module
     * @return bool
     */
    public function uninstall_module($module)
    {
        $sql = "DELETE FROM " . tb() . "config WHERE `for`='module' AND `locate`='$module'";
        return $this->db->query($sql);
    }

    /**
     * Delete template config from table config
     * @param $template
     * @return bool
     */
    public function uninstall_template($template)
    {
        $sql = "DELETE FROM " . tb() . "config WHERE `for`='template' AND `locate`='$template'";
        return $this->db->query($sql);
    }

    public function read_package($full_path)
    {
        /**
         * load pclzip library
         */
        $zip = load_library('pclzip');
        /**
         * init PclZip
         */
        $zip->PclZip($full_path);
        return $zip;
    }

    public function read_package_name($packageObj)
    {
        $list = $packageObj->listContent();
        $hash = explode('/', $list[0]['filename']);
        return trim($hash[0], '/');
    }

    public function read_package_info($moduleObj)
    {
        $parse = load_library('parse');
        $packageName = $this->read_package_name($moduleObj);
        $handle = $moduleObj->extract(PCLZIP_OPT_BY_NAME, $packageName . '/' . $packageName . '.info',
            PCLZIP_OPT_EXTRACT_AS_STRING);
        if ($handle != 0 && !empty($handle[0]['content'])) {
            $info = $parse->ini_string($handle[0]['content']);
            return $info;
        }
        return false;
    }

    public function read_package_struct($moduleObj)
    {
        $packageName = $this->read_package_name($moduleObj);
        $handle = $moduleObj->extract(PCLZIP_OPT_BY_NAME, $packageName . '/' . $packageName . '.struct',
            PCLZIP_OPT_EXTRACT_AS_STRING);
        if ($handle != 0 && !empty($handle[0]['content'])) {
            $line = explode("\n", $handle[0]['content']);
            $line = array_map(function ($i) {
                return trim($i);
            }, $line);
            return $line;
        }
        return false;
    }

    public function make_struct_from_list($list) {
        $contentLine = array();
        foreach ($list as $item) {
            $line = ltrim($item['filename'], '/');
            /**
             * skip for struct and update file
             */
            if (!preg_match('/\.struct$/i', $line) && !preg_match('/\.update$/i', $line)) {
                if (!in_array($line, $contentLine)) {
                    $contentLine[] = $line;
                }
            }
        }
        return $contentLine;
    }

    public function read_package_update($moduleObj, $old_version, $update_version)
    {
        $packageName = $this->read_package_name($moduleObj);
        $handle = $moduleObj->extract(PCLZIP_OPT_BY_NAME, $packageName . '/' . $packageName . '-' . $old_version . '-' . $update_version . '.update',
            PCLZIP_OPT_EXTRACT_AS_STRING);
        if ($handle != 0 && !empty($handle[0]['content'])) {
            $line = explode("\n", $handle[0]['content']);
            $out = array();
            foreach ($line as $l) {
                $l = trim($l);
                $hash = explode(':', $l);
                $out[trim($hash[1])] = $hash[0];
            }
            return $out;
        }
        return false;
    }

    /**
     * Module
     */

    public function get_available_module_info($module_name)
    {
        $module_list = scan_modules();
        if (isset($module_list[$module_name])) {
            return array(
                'name' => $module_list[$module_name]['name'],
                'version' => $module_list[$module_name]['version'],
                'author' => $module_list[$module_name]['author'],
                'des' => $module_list[$module_name]['des'],
            );
        } else return false;
    }

    public function get_available_module_struct($module_name)
    {
        $module_list = scan_modules();
        $modules = array_keys($module_list);
        if (!in_array($module_name, $modules)) {
            return false;
        } else {
            return $module_list[$module_name]['struct'];
        }
    }

    public function module_exists($moduleName)
    {
        $module_list = scan_modules();
        if (in_array($moduleName, array_keys($module_list))) {
            return true;
        }
        return false;
    }

    public function module_dir_exists($module_name)
    {
        $module_dir = __MODULES_PATH . '/' . $module_name;
        if (file_exists($module_dir)) {
            return $module_dir;
        }
        return false;
    }

    public function list_file_valid_module($module_name)
    {
        $file[] = $module_name . '/' . $module_name . '.info';
        //$file[] = $module_name . '/' . $module_name . 'Controller.php';
        $file[] = $module_name . '/' . $module_name . 'Settings.php';
        return $file;
    }

    public function is_valid_module($moduleObj)
    {
        $zipListContent = $moduleObj->listContent();
        $packageName = $this->read_package_name($moduleObj);
        /**
         * List file to check
         */
        $check = $this->list_file_valid_module($packageName);

        $fail = FALSE;
        foreach ($check as $checkFile) {
            $found = false;
            foreach ($zipListContent as $item) {
                if ($checkFile == $item['filename']) {
                    $found = true;
                    break;
                }
            }
            if ($found == false) {
                $fail = TRUE;
                break;
            }
        }
        if ($fail == false) {
            return true;
        }
        return false;
    }

    /**
     * Install module with module object
     * @param $moduleObj
     * @return bool|string
     */
    public function install_module($moduleObj)
    {
        $list = $moduleObj->listContent();
        $package_struct = $this->read_package_struct($moduleObj);
        if ($package_struct === false) {
            $package_struct = $this->make_struct_from_list($list);
        }
        $perm_read_dir = 0755;
        $perm_read_file = 0644;
        /**
         * get old permission of modules dir
         */
        $old_perm = fileperms(__MODULES_PATH);
        /**
         * make readable modules dir
         */
        changeMod(__MODULES_PATH, $perm_read_dir);
        /**
         * backup module
         */
        $module_name = $this->read_package_name($moduleObj);
        $backup_link = __MODULES_PATH . '/' . $module_name;
        $backup = backup_file($backup_link);

        foreach ($list as $item) {
            $file = ltrim($item['filename'], '/');
            if (in_array($file, $package_struct) || preg_match('/\.struct$/i', $file) || preg_match('/\.update$/i', $file)) {
                $extract_file_path = __MODULES_PATH . '/' . $file;
                $extract_continuous = true;
                if ($item['folder']) {
                    if (!file_exists($extract_file_path) || !is_dir($extract_file_path)) {
                        /**
                         * make dir if not existed
                         */
                        mkdir($extract_file_path);
                    } else {
                        /**
                         * stop extract if folder is existed
                         */
                        $extract_continuous = false;
                    }
                } else {
                    if (file_exists($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    }
                }
                if ($extract_continuous) {
                    $extract = $moduleObj->extract(
                        PCLZIP_OPT_PATH, __MODULES_PATH,
                        PCLZIP_OPT_BY_NAME, $item['filename']
                    );
                    if ($extract == 0) {
                        $error = "ERROR : " . $moduleObj->errorInfo(true);
                        break;
                    }
                }
            }
        }
        /**
         * change to old perm for module dir
         */
        changeMod(__MODULES_PATH, $old_perm);
        if (!empty($error)) {
            /**
             * restore module
             */
            restore_file($backup, $backup_link);
            return $error;
        }
        return true;
    }

    public function update_module($moduleObj, $updateInfo)
    {
        $module_name = $this->read_package_name($moduleObj);
        /**
         * read package structure
         */
        $package_struct = $this->read_package_struct($moduleObj);
        if ($package_struct === false) {
            $list = $moduleObj->listContent();
            $package_struct = $this->make_struct_from_list($list);
        }
        $perm_read_dir = 0755;
        $perm_read_file = 0644;
        /**
         * get old permission of modules dir
         */
        $old_perm = fileperms(__MODULES_PATH);
        /**
         * make readable modules dir
         */
        changeMod(__MODULES_PATH, $perm_read_dir);

        /**
         * backup module
         */
        $backup_link = __MODULES_PATH . '/' . $module_name;
        $backup = backup_file($backup_link);

        foreach ($updateInfo as $file => $stt) {
            $file = ltrim($file, '/');
            if (in_array($file, $package_struct) || preg_match('/\.struct$/i', $file) || preg_match('/\.update$/i', $file)) {
                $extract_file_path = __MODULES_PATH . '/' . $file;
                if ($stt == 'delete') {
                    if (file_exists($extract_file_path) && is_dir($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        rrmdir($extract_file_path);
                    } elseif (file_exists($extract_file_path) && is_file($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    }
                } elseif ($stt == 'update') {
                    if (file_exists($extract_file_path) && is_file($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    } elseif (file_exists($extract_file_path) && is_dir($extract_file_path)) {
                        /**
                         * do nothing
                         */
                    } elseif (!file_exists($extract_file_path) && preg_match('/\/$/i', $file)) {
                        /**
                         * if is dir. Make it
                         */
                        mkdir($extract_file_path);
                    }
                    $extract = $moduleObj->extract(
                        PCLZIP_OPT_PATH, __MODULES_PATH,
                        PCLZIP_OPT_BY_NAME, $file
                    );
                    if ($extract == 0) {
                        $error[] = "Lỗi giải nén: " . $moduleObj->errorInfo(true);
                    }
                } elseif ($stt == 'keep') {
                    /**
                     * do nothing
                     */
                }
            } else {
                $error[] = 'Khôn tồn tại cấu trúc: ' . $file;
            }
        }
        /**
         * change to old perm for module dir
         */
        changeMod(__MODULES_PATH, $old_perm);
        if (!empty($error)) {
            /**
             * restore module
             */
            restore_file($backup, $backup_link);
            return $error;
        }
        return true;
    }

    /***
     * Template
     */

    public function get_available_template_info($template_name)
    {
        $template_list = scan_templates();
        if (isset($template_list[$template_name])) {
            return array(
                'name' => $template_list[$template_name]['name'],
                'version' => $template_list[$template_name]['version'],
                'author' => $template_list[$template_name]['author'],
                'des' => $template_list[$template_name]['des'],
            );
        } else return false;
    }

    public function get_available_template_struct($template_name)
    {
        $template_list = scan_templates();
        $template_list_key = array_keys($template_list);
        if (!in_array($template_name, $template_list_key)) {
            return false;
        } else {
            return $template_list[$template_name]['struct'];
        }
    }

    public function template_exists($template_name)
    {
        $template_list = scan_templates();
        if (in_array($template_name, array_keys($template_list))) {
            return true;
        }
        return false;
    }

    public function template_dir_exists($template_name)
    {
        $template_dir = __TEMPLATES_PATH . '/' . $template_name;
        if (file_exists($template_dir)) {
            return $template_dir;
        }
        return false;
    }

    public function list_file_valid_template($template_name)
    {
        $file[] = $template_name . '/' . $template_name . '.info';
        $file[] = $template_name . '/config.php';
        $file[] = $template_name . '/run.php';
        $file[] = $template_name . '/page.php';
        return $file;
    }

    public function is_valid_template($moduleObj)
    {
        $zipListContent = $moduleObj->listContent();
        $packageName = $this->read_package_name($moduleObj);
        /**
         * List file to check
         */
        $check = $this->list_file_valid_template($packageName);

        $fail = FALSE;
        foreach ($check as $checkFile) {
            $found = false;
            foreach ($zipListContent as $item) {
                if ($checkFile == $item['filename']) {
                    $found = true;
                    break;
                }
            }
            if ($found == false) {
                $fail = TRUE;
                break;
            }
        }
        if ($fail == false) {
            return true;
        }
        return false;
    }

    /**
     * Install template with template object
     * @param $templateObj
     * @return bool|string
     */
    public function install_template($templateObj)
    {
        $list = $templateObj->listContent();
        $package_struct = $this->read_package_struct($templateObj);
        if ($package_struct === false) {
            $package_struct = $this->make_struct_from_list($list);
        }
        $perm_read_dir = 0755;
        $perm_read_file = 0644;
        /**
         * get old permission of template dir
         */
        $old_perm = fileperms(__TEMPLATES_PATH);
        /**
         * make readable templates dir
         */
        changeMod(__TEMPLATES_PATH, $perm_read_dir);
        /**
         * backup template
         */
        $template_name = $this->read_package_name($templateObj);
        $backup_link = __MODULES_PATH . '/' . $template_name;
        $backup = backup_file($backup_link);

        foreach ($list as $item) {
            $file = ltrim($item['filename'], '/');
            if (in_array($file, $package_struct) || preg_match('/\.struct$/i', $file) || preg_match('/\.update$/i', $file)) {
                $extract_file_path = __TEMPLATES_PATH . '/' . $file;
                $extract_continuous = true;
                if ($item['folder']) {
                    if (!file_exists($extract_file_path) || !is_dir($extract_file_path)) {
                        /**
                         * make dir if not existed
                         */
                        mkdir($extract_file_path);
                    } else {
                        /**
                         * stop extract if folder is existed
                         */
                        $extract_continuous = false;
                    }
                } else {
                    if (file_exists($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    }
                }
                if ($extract_continuous) {
                    $extract = $templateObj->extract(
                        PCLZIP_OPT_PATH, __TEMPLATES_PATH,
                        PCLZIP_OPT_BY_NAME, $item['filename']
                    );
                    if ($extract == 0) {
                        $error = "ERROR : " . $templateObj->errorInfo(true);
                        break;
                    }
                }
            }
        }
        /**
         * change to old perm for template dir
         */
        changeMod(__TEMPLATES_PATH, $old_perm);
        if (!empty($error)) {
            /**
             * restore template
             */
            restore_file($backup, $backup_link);
            return $error;
        }
        return true;
    }

    public function update_template($templateObj, $updateInfo)
    {
        $template_name = $this->read_package_name($templateObj);
        /**
         * read package structure
         */
        $package_struct = $this->read_package_struct($templateObj);
        if ($package_struct === false) {
            $list = $templateObj->listContent();
            $package_struct = $this->make_struct_from_list($list);
        }
        $perm_read_dir = 0755;
        $perm_read_file = 0644;
        /**
         * get old permission of templates dir
         */
        $old_perm = fileperms(__TEMPLATES_PATH);
        /**
         * make readable templates dir
         */
        changeMod(__TEMPLATES_PATH, $perm_read_dir);

        /**
         * backup template
         */
        $backup_link = __TEMPLATES_PATH . '/' . $template_name;
        $backup = backup_file($backup_link);

        foreach ($updateInfo as $file => $stt) {
            $file = ltrim($file, '/');
            if (in_array($file, $package_struct) || preg_match('/\.struct$/i', $file) || preg_match('/\.update$/i', $file)) {
                $extract_file_path = __TEMPLATES_PATH . '/' . $file;
                if ($stt == 'delete') {
                    if (file_exists($extract_file_path) && is_dir($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        rrmdir($extract_file_path);
                    } elseif (file_exists($extract_file_path) && is_file($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    }
                } elseif ($stt == 'update') {
                    if (file_exists($extract_file_path) && is_file($extract_file_path)) {
                        /**
                         * delete file if existed
                         */
                        changeMod($extract_file_path, $perm_read_file);
                        unlink($extract_file_path);
                    } elseif (file_exists($extract_file_path) && is_dir($extract_file_path)) {
                        /**
                         * do nothing
                         */
                    } elseif (!file_exists($extract_file_path) && preg_match('/\/$/i', $file)) {
                        /**
                         * if is dir. Make it
                         */
                        mkdir($extract_file_path);
                    }
                    $extract = $templateObj->extract(
                        PCLZIP_OPT_PATH, __TEMPLATES_PATH,
                        PCLZIP_OPT_BY_NAME, $file
                    );
                    if ($extract == 0) {
                        $error[] = "Lỗi giải nén: " . $templateObj->errorInfo(true);
                    }
                } elseif ($stt == 'keep') {
                    /**
                     * do nothing
                     */
                }
            } else {
                $error[] = 'Khôn tồn tại cấu trúc: ' . $file;
            }
        }
        /**
         * change to old perm for templates dir
         */
        changeMod(__TEMPLATES_PATH, $old_perm);
        if (!empty($error)) {
            /**
             * restore template
             */
            restore_file($backup, $backup_link);
            return $error;
        }
        return true;
    }

    public function check_package($packageObj)
    {
        $list = $packageObj->listContent();
        $package_error = false;
        $error = array();
        $php = load_library('phpcodechecker');
        foreach ($list as $item) {
            if (!$item['folder']) {
                if (preg_match('/\.php$/i', $item['filename']) && !preg_match('/\.map\.php$/i', $item['filename']) && !preg_match('/\.tpl\.php$/i', $item['filename'])) {
                    $listFile = $packageObj->extract(PCLZIP_OPT_BY_NAME, $item['filename'],
                        PCLZIP_OPT_EXTRACT_AS_STRING);
                    if ($listFile != 0) {
                        $php->set_code($listFile[0]['content']);
                        if ($php->load_api()) {
                            if (!$php->checker()) {
                                $package_error = true;
                                $error[] = 'FILE: ' . $item['filename'] . ' - ' . $php->get_error();
                            }
                        } else {
                            $error[] = 'FILE: ' . $item['filename'] . ' - ' . $php->get_error();
                        }
                    }
                }
            }
        }
        if (!$package_error) {
            return true;
        } else return $error;
    }

    public function count_module($onlyActivate = false) {
        load_helper('fhandle');
        $handle_list = scan_modules($onlyActivate ? false : true);
        return count($handle_list);
    }

    public function count_template() {
        load_helper('fhandle');
        $handle_list = scan_templates();
        return count($handle_list);
    }

    public function get_page_content($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            return curl_exec($ch);
        } else return file_get_contents($url);
    }
}
