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

/**
 *
 * @param int $tineline
 * @return string
 */
if (!function_exists('get_date')) {

    function get_date($timeline = false, $options = array())
    {
        return get_date_time($timeline, 'date', $options);
    }
}

/**
 *
 * @param int $tineline
 * @return string
 */
if (!function_exists('m_timetostr')) {

    function m_timetostr($timeline = false, $options = array())
    {
        return get_date_time($timeline, 'time', $options);
    }
}

/**
 *
 * @param int $timeline
 * @param string $type
 * @return string
 */
if (!function_exists('get_date_time')) {

    function get_date_time($timeline = false, $type = 'time', $options = array())
    {
        if (!$timeline) {
            $timeline = time();
        }
        $timezone = sysConfig('timezone');
        $timeline = $timeline + $timezone * 3600;
        $current = time() + $timezone * 3600;
        if (!empty($options['date_format'])) {
            $date_format = $options['date_format'];
        } else {
            $date_format = sysConfig('date_format');
        }
        if (!empty($options['time_format'])) {
            $time_format = $options['time_format'];
        } else {
            $time_format = sysConfig('time_format');
        }
        if ($type == 'date') {
            return gmdate($date_format, $timeline);
        } elseif ($type == 'date-time') {
            return gmdate($date_format . ' ' . $time_format, $timeline);
        } else {
            if (gmdate("j", $timeline) == gmdate("j", $current)) {
                return 'Hôm nay, ' . gmdate($time_format, $timeline);
            } elseif (gmdate("j", $timeline) == gmdate("j", ($current - 3600 * 24))) {
                return 'Hôm qua, ' . gmdate($time_format, $timeline);
            }
            if (isset($options['display_all']) && $options['display_all'] == true) {
                return gmdate($date_format . ', ' . $time_format, $timeline);
            }
            return gmdate($date_format, $timeline);
        }
    }
}

if (!function_exists('ezDate')) {
    /**
     * Return like this: 3 month ago
     * @param $d
     * @return string
     */
    function ezDate($d) {
        $currTime = time();
        if (!is_numeric($d)) {
            $ts = $currTime - strtotime(str_replace("-","/",$d));
        } else {
            $ts = $currTime - $d;
        }
        if($ts>31536000) $val = round($ts/31536000,0).' year';
        else if($ts>2419200) $val = round($ts/2419200,0).' month';
        else if($ts>604800) $val = round($ts/604800,0).' week';
        else if($ts>86400) $val = round($ts/86400,0).' day';
        else if($ts>3600) $val = round($ts/3600,0).' hour';
        else if($ts>60) $val = round($ts/60,0).' minute';
        else $val = $ts.' second';
        if($val>1) $val .= 's';
        return $val;
    }

}
