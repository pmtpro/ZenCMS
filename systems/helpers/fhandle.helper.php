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


if (!function_exists('scan_modules')) {

    function scan_modules()
    {

        $parse = load_library('parse');

        $base_path = __MODULES_PATH;
        $old_perm = fileperms($base_path);
        $perm_read = 0755;

        changemod($base_path, $perm_read);

        if (!is_readable($base_path)) {

            return array();
        }

        $out = array();

        $lists = glob(__MODULES_PATH . '/*', GLOB_ONLYDIR);

        changemod($base_path, $old_perm);

        foreach ($lists as $module_path) {

            $mods = explode('/', $module_path);

            $module_name = end($mods);

            $controller_file = $base_path . '/' . $module_name . '/' . $module_name . 'Controller.php';

            $settings_file = $base_path . '/' . $module_name . '/' . $module_name . 'Settings.php';

            $info_file = $base_path . '/' . $module_name . '/' . $module_name . '.info';

            if (file_exists($controller_file) && file_exists($settings_file) && file_exists($info_file)) {

                $re = $parse->ini_file($info_file);
                $re['url'] = $module_name;
                $re['full_path'] = $module_path;
                $out[$module_name] = $re;
            }
        }
        clearstatcache();
        return $out;
    }

}


if (!function_exists('scan_templates')) {

    function scan_templates()
    {

        $path = __TEMPLATES_PATH;

        $temp = glob($path . '/*', GLOB_ONLYDIR);

        foreach ($temp as $k => $t) {

            $name = end(explode('/', $t));

            if (!file_exists($t . '/config.php') || !file_exists($t . '/run.php') || !file_exists($t . '/' . $name . '.info') || !is_dir($t . '/' . __FOLDER_TPL_NAME)) {

                unset($temp[$k]);

            } else {

                $parse = load_library('parse');
                $content = file_get_contents($t . '/' . $name . '.info');

                $list[$name] = $parse->ini_string($content);
                $list[$name]['full_path'] = $t;
                $list[$name]['url'] = $name;
            }

            $list[$name]['screenshot'] = '';

            $screenext = array('jpg', 'png', 'gif', 'bmp');

            foreach ($screenext as $ext) {

                if (file_exists(__TEMPLATES_PATH . '/' . $name . '/screenshot.' . $ext)) {

                    $list[$name]['screenshot'] = _BASE_TEMPLATE . '/screenshot.' . $ext;
                }
            }
        }

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
            $fsize = get_size($bsize);
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