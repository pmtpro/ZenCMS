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

Class ZenSettings
{
    public $registry;
    static $instance;
    public $setting = array();
    public static $get_setting = array();

    /**
     * @return ZenSettings
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ZenSettings();
        }
        return self::$instance;
    }

    /**
     * @param $name
     * @return null|object
     */
    public function get($name) {
        $class = $name . 'Settings';
        $file = __MODULES_PATH . '/' . $name . '/' . $class . ".php";

        if (file_exists($file)) {
            include_once($file);
            if (class_exists($class, false)) {
                return new $class();
            } else {
                return NULL;
            }
        }
        return NULL;
    }
}