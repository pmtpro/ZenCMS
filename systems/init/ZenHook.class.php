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

class ZenHook
{
    private static $instance;
    public $db;
    public $registry;
    public $obj;
    public $module;
    public $result = array();
    /**
     * push data to hook
     * @var array
     */
    public $push = array();
    public $append_callback = null;

    /**
     * @param object $registry
     */
    function __construct($registry) {
        $this->db = $registry->db;
        $this->registry = $registry;
        $this->model = $registry->model;
    }

    /**
     * @param object $registry
     * @return object
     */
    public static function getInstance($registry) {
        if (!self::$instance) {
            self::$instance = new ZenHook($registry);
        }
        return self::$instance;
    }

    /**
     * @param string $name
     * @return \object|null
     */
    public function get($name) {
        $class = $name . 'Hook';
        $file = __MODULES_PATH . '/' . $name . '/' . $class . ".php";
        /**
         * make sure module exists
         */
        if (file_exists($file)) {
            include_once $file;
            if (class_exists($class)) {
                $hook = new $class($this->registry);
                $hook->obj = $hook;
                $hook->module = $name;
                return $hook->obj;
            }
        }
        return NULL;
    }

    /**
     * @param $name
     * @param $data
     * @param array $options
     * @return bool|mixed|string
     */
    public function loader($name, $data, $options = array())
    {
        if (empty($this->module)) {
            $this->module = ZPUBLIC;
        }
        if (isset($GLOBALS['hook'][$this->module][$name])) {
            $this->result[$this->module][$name] = $data;
            return $this->call($name, $data, $options);
        } else {
            /**
             * check hook in class hook
             */
            if (isset($this->obj)) {
                if (method_exists($this->obj, $name)) {
                    /**
                     * add this method to list function
                     */
                    $GLOBALS['hook'][$this->module][$name][] = array($this->obj, $name);
                    $this->result[$this->module][$name] = $data;
                    return $this->call($name, $data, $options);
                } else {
                    $this->result[$this->module][$name] = $data;
                    if ($options['return_everywhere'] == true) {
                        return $data;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param $name
     * @param bool $data
     * @param array $options
     * @return bool|mixed|string
     */
    public function call($name, $data = false, $options = array('protected' => false, 'end_callback' => null, 'var' => null))
    {
        $module = $this->module;
        if (empty($module)) {
            $module = ZPUBLIC;
        }
        if (!isset($options['protected'])) {
            $options['protected'] = false;
        }
        if (!isset($options['end_callback'])) {
            $options['end_callback'] = null;
        }
        if (!isset($options['callback'])) {
            $options['callback'] = null;
        }
        if (is_func($options['callback'])) {
            /**
             * add function append callback
             */
            $GLOBALS['hook_callback'][$module][$name] = $options['callback'];
        }
        $out = $data;

        /**
         * get list var from hook
         */
        if (!isset($options['var']) || !is_array($options['var'])) {
            $listVarArr = array($out);
        } else $listVarArr = array_merge(array($out), array($options['var']));

        if (isset($GLOBALS['hook'][$module][$name])) {
            $listFunction = $GLOBALS['hook'][$module][$name];
            if (!is_array($listFunction)) $listFunction = array($listFunction);
            $weights = array_keys($listFunction);
            /**
             * sort hook by weight
             */
            sort($weights);
            /**
             * if the hook has to be protected,
             * remove any hook before last hook
             */
            if ($options['protected'] == true) {
                $last_hook = end($weights);
                $weights = array($last_hook);
            }

            /**
             * run hook
             */
            foreach ($weights as $w) {
                $HookFunction = $listFunction[$w];
                $out = call_user_func_array($HookFunction, $listVarArr);
                $listVarArr[0] = $out;
            }
        }

        if (is_func($options['end_callback'])){
            $listVarArr[0] = $out;
            $out = call_user_func_array($options['end_callback'], $listVarArr);
        }
        return $out;
    }

    /**
     * @param $module
     * @param $name
     * @param $data
     * @param $appendData
     * @return array|mixed|string
     */
    public function append($module, $name, $data, $appendData) {
        if (empty($module)) $module = ZPUBLIC;
        if (isset($GLOBALS['hook_callback'][$module][$name])) {
            $appendData = call_user_func($GLOBALS['hook_callback'][$module][$name], $appendData);
        }
        if (is_string($data)) {
            $data .= $appendData;
        } elseif (is_array($data)) {
            $data[] = $appendData;
        } elseif (is_numeric($data)) {
            $data += $appendData;
        } else $data .= $appendData;
        return $data;
    }

    function get_result($name) {
        if (isset($this->result[$this->module][$name])) {
            return $this->result[$this->module][$name];
        }
        return $this->result;
    }

    function __destruct(){}
}
