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

/**
 * php doesn't have this function; ftfy
 *
 * @param callable $callback
 * @param array $input
 * @return array
 */
function array_kmap(Closure $callback, Array $input){
    $output = array();
    foreach($input as $key => $value){
        array_push($output, $callback($key, $value));
    }
    return $output;
}

/**
 * reverse merge arrays
 *
 * @param array $overrides
 * @param array $options
 */
function reverse_merge(Array &$overrides, Array $options){
    $overrides = array_merge($options, $overrides);
}

/**
 * captures the output of a Closure
 *
 * @param callable $call
 * @return string
 */
function capture_closure_output(Closure $call){
    ob_start(); $call(); return ob_get_clean();
}