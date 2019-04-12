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

Class ZenSettings
{
    /**
     * @the vars array
     * @access private
     */

    private $vars = array();
    public $registry;
    static $instance;
    public $setting = array();
    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    /**
     *
     * @get variables
     *
     * @param mixed $index
     *
     * @return mixed
     *
     */
    public function __get($index)
    {
        if (isset($this->vars[$index]))
            return $this->vars[$index];
    }

    public static function getInstance()
    {

        if (!self::$instance) {
            self::$instance = new ZenSettings();
        }
        return self::$instance;
    }

    public function get($name)
    {

        $file = __MODULES_PATH . '/' . $name . '/' . str_replace("Settings", "", strtolower($name)) . "Settings.php";

        if (file_exists($file)) {

            include_once($file);

            $class = str_replace("Settings", "", strtolower($name)) . "Settings";

            if (class_exists($class, false)) {

                return new $class();
            } else {

                return NULL;
            }
        }

        return NULL;
    }

}