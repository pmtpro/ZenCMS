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

class ZenModel
{

    private static $instance;
    private $_bgInstance;
    public $db;
    public $registry;
    public $user;

    function __construct($registry)
    {

        $this->db = $registry->db;
        $this->user = $registry->user;
        $this->registry = $registry;
    }

    public static function getInstance($registry)
    {

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
    public function __insertNewMethod($instance)
    {
        $this->_bgInstance = $instance;
    }

    public function __call($method, $args)
    {
        if (is_object($this->_bgInstance)) {

            return call_user_func_array(array($this->_bgInstance, $method), $args);
        }
    }

    public function __get($key)
    {
        return $this->_bgInstance->$key;
    }

    public function __set($key, $val)
    {
        return $this->_bgInstance->$key = $val;
    }

    /**
     * auto run back ground model
     */
    private function _run_background_model()
    {
        $router = $this->registry->router->router;

        $_list = get_list_modules();

        $bg_mod = $_list[BACKGROUND];

        foreach ($bg_mod as $mod) {

            $controller = $mod . 'Controller';
            $model = $mod . 'Model';
            $settings = $mod . 'Settings';

            $path_controller = __MODULES_PATH . '/' . $mod . '/' . $controller . '.php';

            $path_model = __MODULES_PATH . '/' . $mod . '/' . $model . '.php';

            $path_settings = __MODULES_PATH . '/' . $mod . '/' . $settings . '.php';

            /**
             * make sure this modules has model file
             */
            if (file_exists($path_controller) && file_exists($path_settings) && file_exists($path_model)) {

                include_once $path_settings;

                /**
                 * check class exists
                 */
                if (class_exists($settings)) {

                    /**
                     * initialize model
                     */
                    $set = new $settings();

                    if (empty($set->setting['type'])) {

                        $set->setting['type'] = APP;
                    }
                    /**
                     * check type module
                     * if is background module then continous
                     * else stop working
                     */
                    if ($set->setting['type'] == BACKGROUND) {

                        if (!isset($set->setting['startup'])) {

                            $set->setting['startup'] = null;
                        }
                        $startup = $set->setting['startup'];

                        $continous = false;

                        if (is_null($startup) || empty ($startup)) {

                            $continous = true;

                        } else {

                            $finded = strpos(rtrim($router, '/') . '/', $set->setting['startup']);

                            if (!is_bool($finded) && $finded == 0) {

                                if (preg_match('/\/$/', $startup)) {

                                    $continous = true;

                                } else {

                                    if ($startup == $router) {

                                        $continous = true;
                                    }
                                }
                            }
                        }

                        if ($continous) {

                            /**
                             * include controller to make sure this file is a module
                             */
                            include_once $path_controller;

                            /**
                             * check cotroller class exists
                             */
                            if (class_exists($controller)) {

                                /**
                                 * include model file
                                 */
                                include_once $path_model;

                                if (class_exists($model)) {

                                    $merge = new $model($this->registry);

                                    /**
                                     * import method from $merge object
                                     * to main model
                                     */
                                    $this->__insertNewMethod($merge);

                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function get($name = false)
    {

        /**
         * run back ground model
         */
        $this->_run_background_model();

        if (!$name) {

            return $this;
        }

        /**
         * set model path
         */
        $file = __MODULES_PATH . '/' . $name . '/' . str_replace("Model", "", strtolower($name)) . "Model.php";

        /**
         * make sure file exists
         */
        if (file_exists($file)) {

            include_once($file);

            $class = str_replace("Model", "", strtolower($name)) . "Model";

            /**
             * initialize model
             */
            $output = new $class($this->registry);

            return $output;
        }
        return NULL;
    }

    function __destruct()
    {

    }

}
