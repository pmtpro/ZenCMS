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

class ZenCaching
{

    static $modname;
    static $var;
    static $location;

    static function set_location($lo = 'data') {
        self::$location = $lo;
    }

    static function encode($data) {
        return gzdeflate(serialize($data));
    }

    static function decode($data) {
        return unserialize(gzinflate($data));
    }

    /**
     * @return string
     */
    static function cacheDir() {
        if (!empty(self::$location)) {
            $dir = self::$location;
        } else {
            $dir = 'data';
        }
        return __FILES_PATH . '/systems/cache/' . $dir;
    }

    /**
     * set cache
     *
     * @param $name
     * @param $data
     * @param $time_hold
     * @return bool
     */
    static function set($name, $data, $time_hold) {
        if (!is_numeric($time_hold)) {
            $time_hold = 300;
        }
        $time_expired = time() + $time_hold;
        $basename = self::createName($name);
        if (!$basename) return false;
        $name = $basename . '.' . $time_expired;
        $file = $name . '.caching';
        $cacheDir = self::cacheDir();
        $data = self::encode($data);
        $scan = glob($cacheDir . '/' . $basename . '.*');
        if (is_array($scan)) {
            foreach ($scan as $cache) {
                if (file_exists($cache) && is_file($cache) && is_readable($cache)) {
                    unlink($cache);
                }
            }
        }
        file_put_contents($cacheDir . '/' . $file, $data);
    }

    /**
     * get cache
     *
     * @param $name
     * @return mixed|null
     */
    static function get($name) {
        $basename = self::createName($name);
        $cacheDir = self::cacheDir();
        $scan = glob($cacheDir . '/' . $basename . '.*');
        if (is_array($scan)) {
            foreach ($scan as $cache) {
                if (is_file($cache) && is_readable($cache)) {
                    $filename = basename($cache);
                    $ex = explode('.', $filename);
                    $scan_time_expired = $ex[2];
                    if (time() > $scan_time_expired) {
                        $GLOBALS['count']['cache']++;
                        return null;
                    }
                    $data = file_get_contents($cache);
                    return self::decode($data);
                }
            }
        }
    }

    /**
     * clean cache
     * if empty $function_name then clean all cache of class
     * @param string $function_name
     * @param string $class
     * @return bool
     */
    static function clean($function_name = '', $class = '') {
        $cacheDir = self::cacheDir();
        if (empty($class)) {
            $debug = debug_backtrace();
            $class = $debug[1]['class'];
        }
        $class_folder = $cacheDir . '/' . $class;
        if (!file_exists($class_folder) || !is_dir($class_folder)) {
            return false;
        }

        if (empty ($function_name)) {
            $scanAll = glob($class_folder . '/*');
            if (is_array($scanAll)) {
                foreach ($scanAll as $f) {
                    if (is_file($f) && is_readable($f)) unlink($f);
                }
            }
            return true;
        }
        $glob_match = $class_folder . '/' . md5($function_name) . '.*';
        $scan = glob($glob_match);
        if (is_array($scan)) {
            foreach ($scan as $cache) {
                unlink($cache);
            }
        }
    }

    /**
     * @param $name
     * @return string
     */
    static function createName($name) {
        $cache_info = debug_backtrace();
        $folder_auto_create = $cache_info[2]['class'];
        $mk_folder_path = self::cacheDir() . '/' . $folder_auto_create;
        if (!file_exists($mk_folder_path)) {
            if (mkdir($mk_folder_path)) $folder_exists = true;
            else $folder_exists = false;
        } else $folder_exists = true;

        if ($folder_exists) {
            if (is_writable($mk_folder_path) && !file_exists($mk_folder_path . '/index.php')) {
                file_put_contents($mk_folder_path . '/index.php', '');
            }
            $base_file_name = $folder_auto_create . '/' . md5($cache_info[2]['function']) . '.' . md5($name);
            return $base_file_name;
        }
        return false;
    }
}