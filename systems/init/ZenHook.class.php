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
     *
     * @var array
     */
    public $push = array();

    /**
     *
     * @param object $registry
     */
    function __construct($registry)
    {
        $this->db = $registry->db;
        $this->registry = $registry;
        $this->model = $registry->model;
    }

    /**
     *
     * @param object $registry
     * @return object
     */
    public static function getInstance($registry)
    {

        if (!self::$instance) {

            self::$instance = new ZenHook($registry);
        }
        return self::$instance;
    }

    /**
     *
     * @param string $name
     * @return \object|null
     */
    public function get($name)
    {
        $name_module = str_replace("Hook", "", strtolower($name));

        $file = __MODULES_PATH . '/' . $name . '/' . $name_module . "Hook.php";

        /**
         * make sure module exists
         */
        if (file_exists($file)) {

            include_once $file;

            $class = $name_module . "Hook";

            if (class_exists($class)) {

                $this->obj = new $class($this->registry);

                $this->module = $name_module;
            }

        }
        return NULL;
    }


    /**
     * @param $name
     * @param $data
     * @param bool $protected
     * @param bool $return_everywhere
     * @return bool
     */
    public function loader($name, $data, $protected = false, $return_everywhere = true)
    {
        if (empty($this->module)) {

            $this->module = _PUBLIC;
        }

        if (isset($GLOBALS['hook'][$this->module][$name])) {

            $this->result[$this->module][$name] = $data;

            return hook($this->module, $name, $data, $protected);
        }

        if (isset($this->obj)) {

            if (method_exists($this->obj, $name)) {

                $this->result[$this->module][$name] = $data;

                return $this->obj->$name($data);

            } else {

                $this->result[$this->module][$name] = $data;

                if ($return_everywhere == true) {

                    return $data;
                }
            }
        }
    }

    function get_result($name) {

        if (isset($this->result[$this->module][$name])) {

            return $this->result[$this->module][$name];
        }
        return $this->result;
    }

    function __destruct()
    {

    }

}
