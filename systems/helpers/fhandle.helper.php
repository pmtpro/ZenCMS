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

if (!function_exists('write_user_config')) {
    function write_user_config($data, $fileName = 'ZenUSERCONFIG.php') {
        $config_line = array();
        foreach ($data as $i) {
            if (is_string($i['value'])) {
                $value = str_replace("'", "\'", $i['value']);
                $value_write = "'$value'";
            } elseif (is_array($i['value'])) {
                $value_write = var_export($i['value'], true);
            } else {
                $value_write = $i['value'];
            }
            $config_line[] = '$zen[\'config\'][\'' . $i['key'] . '\'] = ' . $value_write . ';';
        }
        $code = "<?php\nif (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');\n" . implode("\n", $config_line);
        $php = load_library('phpcodechecker');
        $php->set_code($code);
        if ($php->load_api()) {
            if ($php->checker()) {
                $code_ok = true;
            } else {
                $code_ok = false;
                set_global_msg('Syntax error: ' . $php->get_error());
            }
        } else {
            $code_ok = false;
            set_global_msg($php->get_error());
        }
        if ($code_ok) {
            if (file_put_contents(__SYSTEMS_PATH . '/includes/config/' . $fileName, $code)) {
                return true;
            } else {
                set_global_msg('Can not write file');
                return false;
            }
        } else return false;
    };
}

if (!function_exists('scan_modules')) {

    /**
     * scan module from module dir
     * @param bool $getAvailableModule
     * @return array
     */
    function scan_modules($getAvailableModule = true) {
        global $registry;
        static $_static_function;
        $ks = 0;
        if ($getAvailableModule) $ks = 1;
        if (!empty($_static_function['scan_modules_' . $ks])) {
            return $_static_function['scan_modules_' . $ks];
        }

        /**
         * get admin model
         */
        $model = $registry->model->get('admin');
        /**
         * get admin hook
         */
        $hook = $registry->hook->get('admin');

        /**
         * load parse library
         */
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

        $listActivated = getActiveModule();
        $listActivated = array_keys($listActivated);

        foreach ($lists as $module_path) {
            $mods = explode('/', $module_path);
            $module_name = end($mods);
            $info_file = $module_path . '/' . $module_name . '.info';
            $readme_file = $module_path . '/readme.txt';

            $needed_file = $model->list_file_valid_module($module_name);
            /**
             * valid structure module
             */
            $is_valid = true;
            foreach ($needed_file as $item) {
                $item_path = __MODULES_PATH . '/' . $item;
                if (!file_exists($item_path)) $is_valid = false;
            }
            if ($is_valid) {
                if ($getAvailableModule || in_array($module_name, $listActivated)) {
                    $set = $registry->settings->get($module_name);
                    $info = $parse->ini_file($info_file);
                    $info['url'] = $module_name;
                    $info['type'] = 'module';
                    $info['package'] = $module_name;
                    $info['full_path'] = $module_path;
                    /**
                     * protect module
                     */
                    $info['protected'] = false;
                    if (in_array($module_name, $protected)) $info['protected'] = true;

                    if (in_array($info['url'], $listActivated)) {
                        $info['activated'] = true;
                    } else $info['activated'] = false;

                    $info['readme_file'] = false;
                    if (file_exists($readme_file) && is_readable($readme_file)) {
                        $info['readme_file'] = $readme_file;
                    }
                    $info['options'] = array();
                    if (isset($set->setting['extends']) && !is_null($set->setting['extends']) && !empty($set->setting['extends']) && is_array($set->setting['extends'])) {
                        $option_list = array();
                        $i = 0;
                        foreach ($set->setting['extends'] as $app => $extends_set) {
                            if (!empty($extends_set['router'])) {
                                $i++;
                                if (empty($extends_set['name'])) $extends_set['name'] = 'Tùy chọn ' . $i;
                                if (empty($extends_set['title'])) $extends_set['title'] = $extends_set['name'];
                                $option_url = genUrlAppFollow($module_name . '/' . $app);
                                $extends_set['full_url'] = $option_url;
                                if (empty($extends_set['icon'])) $extends_set['icon'] = 'fa fa-flag';
                                $option_list[$module_name . '/' . $app] = $extends_set;
                            }
                        }
                        $info['options'] = $option_list;
                    }
                    /**
                     * options_module_info hook*
                     */
                    $info['options'] = $hook->loader('options_module_info', $info['options'], array('var' => array('module_name' => $module_name, 'info' => $info)));

                    $info['setting'] = $set->setting;

                    /**
                     * struct
                     */
                    $struct_path = $info['full_path'] . '/' . $module_name . '.struct';
                    if (file_exists($struct_path)) {
                        $struct_content = file_get_contents($struct_path);
                        $line = explode("\n", $struct_content);
                        $line = array_map(function($i) {
                            return trim($i);
                        }, $line);
                        $info['struct'] = $line;
                    }
                    /**
                     * module_actions hook*
                     */
                    $info['actions'] = $hook->loader('module_actions', array(), array('var' => $info));
                    /**
                     * refine_module_info hook*
                     */
                    $info = $hook->loader('refine_module_info', $info);

                    $out[$module_name] = $info;
                }
            }
        }
        clearstatcache();
        $_static_function['scan_modules_' . $ks] = $out;
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
        $model = $registry->model->get('admin');
        $hook = $registry->hook->get('admin');
        $path = __TEMPLATES_PATH;
        $list = array();
        $temp = glob($path . '/*', GLOB_ONLYDIR);
        foreach ($temp as $k => $t) {
            $name = end(explode('/', $t));
            $list_file_needed = $model->list_file_valid_template($name);
            $not_valid = false;
            foreach ($list_file_needed as $fn) {
                if (!file_exists(__TEMPLATES_PATH . '/' . $fn)) {
                    $not_valid = true;
                    break;
                }
            }
            if ($not_valid) {
                unset($temp[$k]);
            } else {
                $parse = load_library('parse');
                $content = file_get_contents($t . '/' . $name . '.info');
                $list[$name] = $parse->ini_string($content);
                $list[$name]['full_path'] = $t;
                $list[$name]['url'] = $name;
                $list[$name]['type'] = 'template';
                $list[$name]['package'] = $name;
                $list[$name]['options'] = array();
                $settingFile = __TEMPLATES_PATH . '/' . $name . '/setting/setting.inc.php';
                if (file_exists($settingFile)) {
                    $list[$name]['options']['setting'] = array(
                        'name' => 'Cài đặt',
                        'title' => 'Cài đặt',
                        'icon' => 'fa fa-cog',
                        'full_url' => HOME . '/admin/general/templates/setting/' . $name
                    );
                }
                /**
                 * options_template_info hook*
                 */
                $list[$name]['options'] = $hook->loader('options_template_info', $list[$name]['options'], array('var' => array('template_name' => $name)));

                $chk_screenshot = __TEMPLATES_PATH . '/' . $name . '/screenshot.' . $list[$name]['url'] . '.jpg';
                if (file_exists($chk_screenshot)) {
                    $list[$name]['screenshot'] = HOME . '/templates/' . $name . '/screenshot.' . $list[$name]['url'] . '.jpg';
                } else $list[$name]['screenshot'] = '';

                /**
                 * template_actions hook*
                 */
                $list[$name]['actions'] = $hook->loader('template_actions', array(), array('var' => $list[$name]));

                /**
                 * refine_template_info hook*
                 */
                $list[$name] = $hook->loader('refine_template_info', $list[$name]);
            }
        }
        $_static_function['scan_templates'] = $list;
        return $list;
    }
}

if (!function_exists('backup_file')) {
    function backup_file($file, $dest = '') {
        if (file_exists($file)) {
            if (empty($dest)) {
                $dest = __TMP_DIR . '/backup';
                if (!file_exists($dest) || !is_dir($dest)) {
                    mkdir($dest);
                }
            }
            if (is_file($file)) {
                copy($file, $dest);
            } elseif (is_dir($file)) {
                $dir = trim(trim(basename($file), '/'));
                $dest_tmp = $dest . '/' . $dir;
                if (!file_exists($dest_tmp) && !is_dir($dest_tmp)) {
                    mkdir($dest_tmp);
                }
                rcopy($file, $dest_tmp);
            }
            return $dest;
        }
        return false;
    }
}

if (!function_exists('restore_file')) {
    function restore_file($backup, $file, $rm_backup = true) {
        if (file_exists($backup)) {
            $file_name = trim(trim(basename($file), '/'));
            $src = $backup . '/' . $file_name;
            $hash = explode('/', trim($file, '/'));
            $last_key = count($hash)-1;
            unset($hash[$last_key]);
            $dest = implode('/', $hash);
            if (file_exists($src) && file_exists($dest) && is_dir($dest)) {
                if (is_file($src)) {
                    copy($src, $dest);
                    if ($rm_backup) {
                        unlink($src);
                    }
                } elseif (is_dir($src)) {
                    $dest = $file;
                    rrmdir($dest, false);
                    rcopy($src, $dest);
                    if ($rm_backup) {
                        rrmdir($src);
                    }
                }
            }
        }
    }
}


if (!function_exists('foldersize')) {
    /**
     * get folder size
     * @param $path
     * @return int
     */
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
    /**
     * @param $path
     * @param string $flag
     * @return array|string
     */
    function fileinfo($path, $flag = NO_GET_CONTENT) {
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

if (!function_exists('getfileperms')) {
    /**
     * this function has return permission of file.
     *  win && linux os
     * @param $file
     * @return bool|int|string
     */
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

if (!function_exists('rrmdir')) {
    /**
     * remove directory not empty
     * @param $dir
     * @param bool $rm_top_dir
     */
    function rrmdir($dir, $rm_top_dir = true) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            if ($rm_top_dir) rmdir($dir);
        }
    }
}

if (!function_exists('rcopy')) {
    /**
     * copying recursively
     * @param $src
     * @param $dst
     */
    function rcopy($src, $dst) {
        $dir = opendir($src);
        if (!file_exists($dst) || (file_exists($dst) && !is_dir($dst))) {
            @mkdir($dst);
        }
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    rcopy($src . '/' . $file, $dst . '/' . $file);
                } else copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
        closedir($dir);
    }
}