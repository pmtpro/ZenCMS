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

if (!function_exists('scan_modules')) {

    function scan_modules() {
        global $registry;
        static $_static_function;
        if (!empty($_static_function['scan_modules'])) {
            return $_static_function['scan_modules'];
        }
        $parse = load_library('parse');

        $base_path = __MODULES_PATH;
        $old_perm = fileperms($base_path);
        $perm_read = 0755;
        changeMod($base_path, $perm_read);
        if (!is_readable($base_path)) {
            return array();
        }

        $list_protected = sysConfig('modules_protected');
        $protected = $list_protected;

        $out = array();
        $lists = glob(__MODULES_PATH . '/*', GLOB_ONLYDIR);
        changeMod($base_path, $old_perm);

        foreach ($lists as $module_path) {
            $mods = explode('/', $module_path);
            $module_name = end($mods);
            $controller_file = $module_path . '/' . $module_name . 'Controller.php';
            $settings_file = $module_path . '/' . $module_name . 'Settings.php';
            $info_file = $module_path . '/' . $module_name . '.info';
            $readme_file = $module_path . '/readme.txt';

            if (file_exists($controller_file) && file_exists($settings_file) && file_exists($info_file)) {
                $set = $registry->settings->get($module_name);
                $info = $parse->ini_file($info_file);
                if (empty($info['name'])) {
                    $info['name'] = 'Unknown';
                }
                if (empty($info['id'])) {
                    $info['id'] = 'NO ID';
                }
                $info['url'] = $module_name;
                $info['full_path'] = $module_path;
                if (empty($info['version'])) {
                    $info['version'] = '0.0';
                }
                if (empty($info['author'])) {
                    $info['author'] = 'Unknown';
                }
                if (empty($info['des'])) {
                    $info['des'] = 'none';
                }
                if (in_array($module_name, $protected)) {
                    $info['protected'] = true;
                } else {
                    $info['protected'] = false;
                }
                if (file_exists($readme_file) && is_readable($readme_file)) {
                    $info['readme_file'] = $readme_file;
                } else {
                    $info['readme_file'] = false;
                }

                if (isset($set->setting['extends']) && !is_null($set->setting['extends']) && !empty($set->setting['extends']) && is_array($set->setting['extends'])) {
                    $option_list = array();
                    $i = 0;
                    foreach ($set->setting['extends'] as $app => $extends_set) {
                        if (!empty($extends_set['router'])) {
                            $i++;
                            if (empty($extends_set['name'])) {
                                $extends_set['name'] = 'Tùy chọn ' . $i;
                            }
                            if (empty($extends_set['title'])) {
                                $extends_set['title'] = $extends_set['name'];
                            }
                            //$option_url = HOME . '/' . trim($extends_set['router'], '/') . '?appFollow=' . $module_name . '/' . $app;
                            $option_url = genUrlAppFollow($module_name . '/' . $app);
                            $extends_set['full_url'] = $option_url;
                            if (empty($extends_set['icon'])) {
                                $extends_set['icon'] = 'icon-flag';
                            }
                            $option_list[$module_name . '/' . $app] = $extends_set;
                        }
                    }
                    $info['option'] = $option_list;
                } else $info['option'] = false;
                $info['setting'] = $set->setting;
                $out[$module_name] = $info;
            }
        }
        clearstatcache();
        $_static_function['scan_modules'] = $out;
        return $out;
    }
}

if (!function_exists('scan_templates')) {

    function scan_templates()
    {
        global $registry;
        static $_static_function;
        if (!empty($_static_function['scan_templates'])) {
            return $_static_function['scan_templates'];
        }
        $hook = $registry->hook->get('admin');
        $path = __TEMPLATES_PATH;
        $list = array();
        $temp = glob($path . '/*', GLOB_ONLYDIR);
        foreach ($temp as $k => $t) {
            $name = end(explode('/', $t));
            if (!file_exists($t . '/config.php')
                || !file_exists($t . '/run.php')
                || !file_exists($t . '/' . $name . '.info')
                || !file_exists($t . '/page.php')
            ) {
                unset($temp[$k]);
            } else {
                $parse = load_library('parse');
                $content = file_get_contents($t . '/' . $name . '.info');
                $list[$name] = $parse->ini_string($content);
                $list[$name]['full_path'] = $t;
                $list[$name]['url'] = $name;
                $list[$name]['key'] = $name;
                $list[$name]['actions']['edit'] = array(
                    'name' => 'Chỉnh sửa',
                    'icon' => 'icon-edit',
                    'full_url' => HOME . '/admin/tools/fileManager?file=templates/' . $name
                );
                $list[$name]['actions']['widget'] = array(
                    'name' => 'Widget',
                    'icon' => 'icon-th-large',
                    'full_url' => HOME . '/admin/general/templates/widget/' . $name
                );
                $list[$name]['actions']['uninstall'] = array(
                    'name' => 'Hủy cài đặt',
                    'icon' => 'icon-remove-sign',
                    'full_url' => HOME . '/admin/general/templates/uninstall/' . $name
                );
                $settingFile = __TEMPLATES_PATH . '/' . $name . '/setting/setting.inc.php';
                if (file_exists($settingFile)) {
                    $list[$name]['actions']['setting'] = array(
                        'name' => 'Cài đặt',
                        'icon' => 'icon-cog',
                        'full_url' => HOME . '/admin/general/templates/setting/' . $name
                    );
                }
                /**
                 * template_action_menu hook*
                 */
                $list[$name]['actions'] = $hook->loader('template_action_menu', $list[$name]['actions'], array('var' => $t));
                $chk_screenshot = __TEMPLATES_PATH . '/' . $name . '/screenshot.' . $list[$name]['url'] . '.jpg';
                if (file_exists($chk_screenshot)) {
                    $list[$name]['screenshot'] = HOME . '/templates/' . $name . '/screenshot.' . $list[$name]['url'] . '.jpg';
                } else $list[$name]['screenshot'] = '';
            }
        }
        $_static_function['scan_templates'] = $list;
        return $list;
    }
}

/**
 * get folder size
 *
 * @param $path
 * @return int
 */
if (!function_exists('foldersize')) {

    function foldersize($path)
    {

        $total_size = 0;
        $files = scandir($path);

        foreach ($files as $t) {

            if (is_dir(rtrim($path, '/') . '/' . $t)) {
                if ($t <> "." && $t <> "..") {
                    $size = foldersize(rtrim($path, '/') . '/' . $t);

                    $total_size += $size;
                }

            } else {

                $size = filesize(rtrim($path, '/') . '/' . $t);
                $total_size += $size;
            }
        }

        return $total_size;
    }
}

if (!function_exists('fileinfo')) {

    function fileinfo($path, $flag = NO_GET_CONTENT)
    {

        if ($flag == ONLY_CONTENT) {

            $content = file_get_contents($path);
            return $content;
        }
        /**
         * if is file
         */
        if (is_file($path)) {

            $bsize = filesize($path);
            $fsize = size2text($bsize);
            $ptype = FILE;

        } else {

            $bsize = 0;
            $fsize = 0;
            $ptype = DIR;
        }

        $info = array('name' => basename($path),
            'full_path' => $path,
            'perms' => getfileperms($path),
            'mtime' => filemtime($path),
            'size' => $fsize,
            'bytesize' => $bsize,
            'ptype' => $ptype);

        if ($flag == GET_CONTENT) {

            $info['content'] = file_get_contents($path);
        }
        /**
         * get more info
         */
        $more = pathinfo($path);

        return array_merge($info, $more);
    }
}

/**
 * this function has return permission of file.
 *  win && linux os
 * @param $file
 * @return bool|int|string
 */
if (!function_exists('getfileperms')) {

    function getfileperms($file)
    {

        $perms = fileperms($file);

        /**
         * if it is not win os return here
         */
        if (!preg_match('/win/is', PHP_OS)) {

            return $perms;
        }

        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        /**
         * Owner
         */
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x') :
            (($perms & 0x0800) ? 'S' : '-'));

        /**
         * Group
         */
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x') :
            (($perms & 0x0400) ? 'S' : '-'));

        /**
         * World
         */
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x') :
            (($perms & 0x0200) ? 'T' : '-'));


        if (!preg_match("/[-d]?([-r][-w][-xsS]){2}[-r][-w][-xtT]/", $info)) {

            return false;
        }

        /**
         * 9 chars from the right-hand side
         */
        $Mrwx = substr($info, -9);
        /**
         * pick out sticky
         */
        $ModeDecStr = (preg_match("/[sS]/", $Mrwx[2])) ? 4 : 0;
        /**
         * _ bits and change
         */
        $ModeDecStr .= (preg_match("/[sS]/", $Mrwx[5])) ? 2 : 0;
        /**
         * _ to e.g. '020'
         */
        $ModeDecStr .= (preg_match("/[tT]/", $Mrwx[8])) ? 1 : 0;
        /**
         *  add them
         */
        $Moctal = $ModeDecStr[0] + $ModeDecStr[1] + $ModeDecStr[2];
        /**
         * change execute bit
         */
        $Mrwx = str_replace(array('s', 't'), "x", $Mrwx);
        /**
         * _ to on or off
         */
        $Mrwx = str_replace(array('S', 'T'), "-", $Mrwx);
        /**
         * prepare for strtr
         */
        $trans = array('-' => '0', 'r' => '4', 'w' => '2', 'x' => '1');
        /**
         * translate to e.g. '020421401401'
         */
        $ModeDecStr .= strtr($Mrwx, $trans);
        /**
         * continue
         */
        $Moctal .= $ModeDecStr[3] + $ModeDecStr[4] + $ModeDecStr[5];
        /**
         * _ adding
         */
        $Moctal .= $ModeDecStr[6] + $ModeDecStr[7] + $ModeDecStr[8];
        /**
         * _ triplets
         */
        $Moctal .= $ModeDecStr[9] + $ModeDecStr[10] + $ModeDecStr[11];
        /**
         * returns octal mode, e.g. '2755' from above.
         */
        return $Moctal;
    }
}

/**
 * remove directory not empty
 *
 * @param $dir
 */
if (!function_exists('rrmdir')) {

    function rrmdir($dir)
    {

        if (is_dir($dir)) {

            $objects = scandir($dir);

            foreach ($objects as $object) {

                if ($object != "." && $object != "..") {

                    if (is_dir($dir . "/" . $object)) rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
