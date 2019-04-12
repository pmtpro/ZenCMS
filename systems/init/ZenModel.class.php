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

class ZenModel
{

    private static $instance;
    private $_bgInstance;
    public $db;
    public $registry;
    public $user;

    function __construct($registry) {
        $this->db = $registry->db;
        $this->user = $registry->user;
        $this->registry = $registry;
    }

    public static function getInstance($registry) {
        if (!self::$instance) {
            self::$instance = new ZenModel($registry);
        }
        return self::$instance;
    }

    /**
     * insert new method to main Model
     *
     * @param $instance
     */
    public function __insertNewMethod($instance) {
        $this->_bgInstance = $instance;
    }

    public function __call($method, $args) {
        if (is_object($this->_bgInstance)) {
            return call_user_func_array(array($this->_bgInstance, $method), $args);
        }
    }

    public function __get($key) {
        return $this->_bgInstance->$key;
    }

    public function __set($key, $val) {
        return $this->_bgInstance->$key = $val;
    }

    public function get($name = false) {
        if (!$name) {
            return $this;
        }
        $class = $name . 'Model';
        /**
         * set model path
         */
        $file = __MODULES_PATH . '/' . $name . '/' . $class . ".php";

        /**
         * make sure file exists
         */
        if (file_exists($file)) {
            include_once $file;
            /**
             * initialize model
             */
            $output = new $class($this->registry);
            return $output;
        }
        return NULL;
    }

    function __destruct() {}
}
