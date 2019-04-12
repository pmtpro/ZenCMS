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

class ZenCaching
{

    static $modname;
    static $var;

    /**
     * @return string
     */
    private function cacheDir()
    {

        return __FILES_PATH . '/systems/cache/data';
    }

    /**
     * set cache
     *
     * @param $name
     * @param $data
     * @param $time_hold
     */
    static function set($name, $data, $time_hold)
    {

        if (!is_numeric($time_hold)) {

            $time_hold = 300;
        }

        $time_expired = time() + $time_hold;

        $basename = self::createName($name);

        $name = $basename . '.' . $time_expired;

        $file = $name . '.caching';

        $cacheDir = self::cacheDir();

        $data = serialize($data);

        $scan = glob($cacheDir . '/' . $basename . '.*');

        if (is_array($scan)) {

            foreach ($scan as $cache) {

                unlink($cache);
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
    static function get($name)
    {


        $basename = self::createName($name);

        $cacheDir = self::cacheDir();

        $scan = glob($cacheDir . '/' . $basename . '.*');

        if (is_array($scan)) {

            foreach ($scan as $cache) {

                $filename = basename($cache);

                $ex = explode('.', $filename);

                $scan_time_expired = $ex[3];

                if (time() > $scan_time_expired) {

                    $GLOBALS['count']['cache']++;

                    return null;
                }

                $data = file_get_contents($cache);

                return unserialize($data);
            }
        }
    }

    /**
     * clean cache
     * if empty $name then clean all cache
     *
     * @param string $name
     */
    static function clean($name = '')
    {

        $cacheDir = self::cacheDir();

        if (empty ($name)) {

            $scanall = glob($cacheDir . '/*');

            if (is_array($scanall)) {

                foreach ($scanall as $f) {

                    unlink($f);
                }
            }
            return;
        }


        $debug = debug_backtrace();

        $modname = $debug[1]['class'] . '-' . $name;

        $scan = glob($cacheDir . '/Cache.' . md5($modname) . '*');

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
    private function createName($name)
    {

        $cache_info = debug_backtrace();

        $mod_name = $cache_info[2]['class'] . '-' . $cache_info[2]['function'];

        return 'Cache.' . md5($mod_name) . '.' . md5($name);
    }

}