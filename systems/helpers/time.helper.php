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

/**
 *
 * @param int $tineline
 * @return string
 */
if (!function_exists('get_date')) {

    function get_date($tineline)
    {

        $time = $tineline;

        return get_date_time($time, 'date');
    }

}

/**
 *
 * @param int $tineline
 * @return string
 */
if (!function_exists('get_time')) {

    function get_time($tineline, $on_time = true)
    {

        $time = $tineline;

        return get_date_time($time, 'time', $on_time);
    }

}

/**
 *
 * @param int $timeline
 * @param string $type
 * @return string
 */
if (!function_exists('get_date_time')) {

    function get_date_time($timeline = false, $type = 'time', $on_time = true)
    {

        if (!$timeline) {

            $timeline = time();
        }

        $timezone = sys_config('timezone');

        $timeline = $timeline + $timezone * 3600;
        $current = time() + $timezone * 3600;

        $it_s = intval($current - $timeline);
        $it_m = intval($it_s / 60);
        $it_h = intval($it_m / 60);
        $it_d = intval($it_h / 24);
        $it_y = intval($it_d / 365);

        if ($type == 'date') {

            return gmdate(sys_config('date_format'), $timeline);

        } elseif ($type == 'date-time') {

            return gmdate(sys_config('date_format') . ' ' . sys_config('time_format'), $timeline);

        } else {

            if (gmdate("j", $timeline) == gmdate("j", $current)) {

                return 'Hôm nay, ' . gmdate(sys_config('time_format'), $timeline);
            } elseif (gmdate("j", $timeline) == gmdate("j", ($current - 3600 * 24))) {

                return 'Hôm qua, ' . gmdate(sys_config('time_format'), $timeline);
            }
            if ($on_time == true) {
                return gmdate(sys_config('date_format') . ', ' . sys_config('time_format'), $timeline);
            }
            return gmdate(sys_config('date_format'), $timeline);
        }
    }

}
?>
