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

class ZenRouter
{
    /**
     * @the registry
     */

    private $registry;

    /**
     * @the controller path
     */
    private $path;
    private $args = array();
    public $router;
    public $file;
    public $controller;
    public $action;
    public $module;
    public $file_settings;

    function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $path
     * @throws Exception
     */
    function setPath($path)
    {

        /**
         * check if path is a directory
         */
        if (is_dir($path) == false) {

            throw new Exception('Invalid controller path: `' . $path . '`');
        }

        /**
         * set the path
         */
        $this->path = $path;
    }

    private function run_background_module()
    {

        $_list = get_list_modules();

        $bg_mod = $_list[BACKGROUND];

        foreach ($bg_mod as $mod) {

            $controller = $mod . 'Controller';

            $settings = $mod . 'Settings';

            $path_controller = __MODULES_PATH . '/' . $mod . '/' . $controller . '.php';

            $path_settings = __MODULES_PATH . '/' . $mod . '/' . $settings . '.php';

            if (file_exists($path_controller) && file_exists($path_settings)) {

                include_once $path_settings;

                /**
                 * check class exists
                 */
                if (class_exists($settings, false)) {

                    $set = new $settings();

                    if (empty($set->setting['type'])) {

                        $set->setting['type'] = APP;
                    }

                    if ($set->setting['type'] == BACKGROUND) {

                        if (!isset($set->setting['startup'])) {

                            $set->setting['startup'] = null;
                        }

                        $startup = $set->setting['startup'];

                        $continous = false;

                        if (is_null($startup) || empty ($startup)) {

                            $continous = true;

                        } else {

                            $finded = strpos(rtrim($this->router, '/') . '/', $set->setting['startup']);

                            if (!is_bool($finded) && $finded == 0) {

                                if (preg_match('/\/$/', $startup)) {

                                    $continous = true;

                                } else {

                                    if ($startup == $this->router) {

                                        $continous = true;
                                    }
                                }
                            }
                        }

                        if ($continous) {

                            include_once $path_controller;

                            if (class_exists($controller, false)) {

                                $bg = new $controller($this->registry);

                                /**
                                 * check method exists
                                 */
                                if (method_exists($bg, '_run')) {

                                    $bg->_run();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @load the controller
     *
     * @access public
     *
     * @return void
     *
     */
    public function loader()
    {
        global $system_config;

        $user = $this->registry->user;

        $default_router_error = $system_config['default_router_error'];

        $dis_allow_access = false;

        $path_error_controller = $this->path . '/' . $default_router_error . '/' . $default_router_error . 'Controller.php';

        /**
         * check the route
         */
        $this->getController();

        /**
         * check the settings
         */
        $this->getSettings();

        if (!file_exists($this->file)) {

            show_error(404);
        }

        /**
         * if the file is not there diaf
         */
        if (is_readable($this->file) == false) {

            $this->file = $path_error_controller;

            $this->controller = $default_router_error;
        }

        /**
         * include the controller
         */
        include_once $this->file;

        $class = $this->controller . 'Controller';

        if (!class_exists($class, false)) {

            show_error(404);
        }

        $_list_modules = get_list_modules();

        if (!in_array($this->controller, $_list_modules[APP]) && !in_array($this->controller, $_list_modules[BACKGROUND])) {

            show_error(404);
        }

        /**
         * run background module
         */
        $this->run_background_module();

        /**
         * a new controller class instance
         */
        $controller = new $class($this->registry);

        /**
         * check if the action is callable
         */
        if (is_callable(array($controller, $this->action), true) == false) {

            show_error(403);

        } else {

            $action = $this->action;
        }

        if (is_callable(array($controller, $action), true) == false) {

            show_error(403);
        }

        /**
         * Start load settings
         */
        if (empty($this->file_settings)) {

            show_error(2000);
        }

        include_once $this->file_settings;

        $settings = $this->controller . 'Settings';

        /**
         * check class settings is exists
         */
        if (!class_exists($settings, false)) {

            show_error(2001);
        }

        $load_settings = new $settings();

        /**
         * default type module
         */
        $type_module = APP;

        if (isset($load_settings->setting['type']) && !is_null($load_settings->setting['type'])) {

            $type_module = $load_settings->setting['type'];
        }

        if ($type_module == BACKGROUND && $this->action == '_run') {

            show_error(403);
        }

        if (!method_exists($controller, $action)) {

            show_error(404);
        }

        $val_perm = 0;
        $val_perm_user = 0;
        $dis_allow_access = false;

        if (isset($load_settings->setting['filter_access']) && !is_null($load_settings->setting['filter_access'])) {

            $allowing = $load_settings->setting['filter_access'];

            /**
             * check if the router is enabled
             */
            if (!array_key_exists($action, $allowing)) {

                show_error(505);
            }


            if (!isset($user['perm'])) {

                $user_perm = 'guest';

            } else {

                $user_perm = $user['perm'];
            }

            if (isset($system_config['user_perm']['key'][$allowing[$action]])) {

                $val_perm = $system_config['user_perm']['key'][$allowing[$action]];

            }

            if (isset($system_config['user_perm']['key'][$user_perm])) {

                $val_perm_user = $system_config['user_perm']['key'][$user_perm];
            }
        }

        /**
         * Check permission
         */
        if ($val_perm_user < $val_perm) {

            $dis_allow_access = true;
        }

        if ($dis_allow_access == true) {

            show_error(403);
        }

        /**
         * check if module has VerifyAccess
         */
        if (!isset($load_settings->setting['verify_access'])) {

            $load_settings->setting['verify_access'] = null;
        }
        if (!is_null($load_settings->setting['verify_access']) || is_array($load_settings->setting['verify_access'])) {

            /**
             * do action verify
             */
            $this->do_verifyaccess();

            /**
             * check access
             */
            if ($this->do_check_verifyaccess() == false) {
                /**
                 * find router verify access
                 */
                foreach ($load_settings->setting['verify_access'] as $rut) {

                    $pos = strpos($this->router, $rut);

                    if (!is_bool($pos) && $pos == 0) {

                        include_once $path_error_controller;

                        $class_verify_access = $default_router_error . 'Controller';

                        $controller = new $class_verify_access($this->registry);

                        $action = 'index';

                        $this->args = array(600);
                        break;
                    }
                }
            }
        }

        /**
         * run the action
         */
        if (!empty($this->args)) {

            $controller->$action($this->args);

        } else {

            $controller->$action();
        }

    }

    /**
     * get setting file
     */
    private function getSettings()
    {

        if (isset($this->controller) && isset($this->path)) {

            $path = $this->path . '/' . $this->controller . '/' . $this->controller . 'Settings.php';

            /**
             * check setting file exist
             */
            if (file_exists($path)) {

                if (is_readable($path) == true) {

                    $this->file_settings = $path;
                }
            }
        }
    }

    private function do_verifyaccess()
    {

        if (!isset($_POST['zen_verity_access'])) {

            return;
        }

        $ss_verity_access = md5($_POST['zen_verity_access']);

        $authorzi = md5($ss_verity_access);

        if (!defined('ZEN_VERITY_ACCESS')) {

            define('ZEN_VERITY_ACCESS', ZEN_DEFAULT_PASSWORD);
        }

        if ($authorzi == ZEN_VERITY_ACCESS) {

            $_SESSION['ss_verity_access'] = $ss_verity_access;

        } else {

            generate_log();
        }
    }

    private function do_check_verifyaccess()
    {

        if (!isset($_SESSION['ss_verity_access'])) {

            return false;
        }

        $authorzi = md5($_SESSION['ss_verity_access']);

        if ($authorzi == ZEN_VERITY_ACCESS) {

            return true;
        }
        return false;
    }

    /**
     *
     * @get the controller
     *
     * @access private
     *
     * @return void
     *
     */
    private function getController()
    {
        global $system_config;

        $security = load_library('security');

        /**
         * get the route from the url
         */
        $route = (empty($_GET['_zen_router_'])) ? '' : $_GET['_zen_router_'];

        $route = $security->cleanXSS($route);

        $route = rtrim($route, '/');

        define('ROUTER_BEFORE_REWRITE', $route);

        if (empty($route)) {

            $route = $system_config['default_router'] . '/index';
        }

        /**
         * checking rewrite url
         */
        foreach ($system_config['rewrite_url'] as $search => $replace) {

            if (preg_match($search, $route)) {

                $route = preg_replace($search, $replace, $route);

                break;
            }
        }

        if (is_numeric($route)) {

            $route = $system_config['default_router'] . '/index/' . $route;
        }

        define('ROUTER_AFTER_REWRITE', $route);

        /**
         * analysis and "hash" router
         */
        $parts = explode('/', $route);

        foreach ($parts as $arr_id => $path) {

            if (is_null($parts[$arr_id])) {

                unset($parts[$arr_id]);

            }
        }

        if (empty($parts[1])) {

            $parts[1] = 'index';
        }

        $this->controller = $parts[0];

        $this->action = $parts[1];

        $total = count($parts);

        for ($i = 2; $i < $total; $i++) {

            $this->args[] = $parts[$i];
        }

        /**
         * If router does not exist
         * it will automatically load the router default
         */
        if (empty($this->controller)) {

            $this->controller = $system_config['default_router'];
        }

        $this->router = $route;

        define('__ROUTER', $this->router);

        /**
         * default action is index
         */
        if (empty($this->action)) {

            $this->action = 'index';
        }

        /**
         * set define
         */
        define('__MODULE_PATH', $this->path . '/' . $this->controller);

        define('__MODULE_NAME', $this->controller);

        /**
         * set the fle path
         */
        $this->file = $this->path . '/' . $this->controller . '/' . $this->controller . 'Controller.php';

    }
}
