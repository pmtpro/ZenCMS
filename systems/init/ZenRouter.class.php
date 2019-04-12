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
    public $runningServices = array();

    function __construct($registry) {
        $this->registry = $registry;
    }

    /**
     * @param $path
     * @throws Exception
     */
    function setPath($path) {
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

    /**
     * run background module
     */
    private function run_background_module() {
        $activatedList = get_list_modules();
        $activatedList = array_filter($activatedList);
        foreach ($activatedList as $mod => $set) {
            if (!empty($set) && is_array($set)) {
                foreach ($set as $method => $neededRouter) {
                    /**
                     * if needed router is empty, always run on any module
                     */
                    if (empty($neededRouter)) {
                        $initBG = true;
                    } else {
                        if (!is_array($neededRouter)) {
                            $neededRouter = array($neededRouter);
                        }
                        foreach ($neededRouter as $slRouter) {
                            $initBG = false;
                            $rmEndSlashRouter = rtrim($this->router, '/');
                            $fixedRouter = $rmEndSlashRouter . '/';

                            $check = strpos($fixedRouter, $slRouter);
                            if ($check === 0 ) {
                                $initBG = true;
                            }
                            $lastSlash_exists = false;
                            if (preg_match('/\/$/i', $slRouter)) {
                                $lastSlash_exists = true;
                            }
                            if ($lastSlash_exists == false) {
                                if ($rmEndSlashRouter == $slRouter) {
                                    $initBG = true;
                                } else $initBG = false;
                            }
                            if ($initBG === true) break;
                        }
                    }

                    if ($initBG == true) {
                        $controller = $mod . 'Controller';
                        $settings = $mod . 'Settings';
                        $path_controller = __MODULES_PATH . '/' . $mod . '/' . $controller . '.php';
                        $path_settings = __MODULES_PATH . '/' . $mod . '/' . $settings . '.php';
                        if (file_exists($path_controller) && file_exists($path_settings)) {
                            include_once $path_controller;
                            if (class_exists($controller, false)) {
                                $bgOBJ = new $controller($this->registry);
                                /**
                                 * check method exists
                                 */
                                if (method_exists($bgOBJ, $method)) {
                                    $bgOBJ->$method();
                                    $this->runningServices[$controller][] = $method;
                                } else {
                                    if (method_exists($bgOBJ, '__destruct')) $bgOBJ->__destruct();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param bool|string $request_action
     */
    public function loader($request_action = false)
    {
        global $zen;
        $temp = $this->registry->templateOBJ;
        $user = $this->registry->user;
        $default_router_error = $zen['config']['default_router_error'];
        $dis_allow_access = false;
        $path_error_controller = $this->path . '/' . $default_router_error . '/' . $default_router_error . 'Controller.php';

        /**
         * check the route
         */
        $this->getController($request_action);
        if (!file_exists($this->file)) {
            show_error(404);
        }
        /**
         * check the settings
         */
        $this->getSettings();
        if (empty($this->file_settings) || !file_exists($this->file_settings)) {
            show_error(2000);
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
        require_once $this->file;

        $class = $this->controller . 'Controller';
        /**
         * check controller class is exists
         */
        if (!class_exists($class, false)) {
            show_error(404);
        }
        /**
         * get list module from modules directory
         */
        $activatedList = get_list_modules();
        $_list_modules = array_keys($activatedList);
        if (!in_array($this->controller, $_list_modules)) {
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
        }

        $action = $this->action;
        if (!method_exists($controller, $action)) {
            show_error(404);
        }
        /**
         * Start load settings
         */
        include_once $this->file_settings;

        $settings = $this->controller . 'Settings';

        /**
         * check class settings is exists
         */
        if (!class_exists($settings, false)) {
            show_error(2001);
        }
        /**
         * init setting class
         */
        $load_settings = new $settings();

        if (!empty($load_settings->setting['template_system'])) {
            /**
             * reload new template if module using template sys
             */
            $temp->setTempWorkName($load_settings->setting['template_system'], true);
            $temp->reLoader();
        }

        $val_perm = 0;
        $val_perm_user = 0;
        $dis_allow_access = false;

        if (isset($load_settings->setting['filter_access']) && $load_settings->setting['filter_access'] !== null) {

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
            if (isset($zen['config']['user_perm']['key'][$allowing[$action]])) {
                $val_perm = $zen['config']['user_perm']['key'][$allowing[$action]];
            }
            if (isset($zen['config']['user_perm']['key'][$user_perm])) {
                $val_perm_user = $zen['config']['user_perm']['key'][$user_perm];
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
        if ($load_settings->setting['verify_access'] !== null || is_array($load_settings->setting['verify_access'])) {

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
                        $class = $class_verify_access;
                        $controller = new $class_verify_access($this->registry);
                        $action = 'index';
                        $this->args = array(600);
                        break;
                    }
                }
            }
        }

        $launcher_exists = false;

        if (method_exists($class, '_launcher')) {
            $launcher_exists = true;
        }

        /**
         * run the action
         */
        if (!empty($this->args)) {
            if ($launcher_exists) {
                $controller->_launcher($this->args);
            }
            $controller->$action($this->args);
        } else {
            if ($launcher_exists) {
                $controller->_launcher();
            }
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
            $_SESSION['ZENSS_VERITY_ACCESS'] = $ss_verity_access;
        } else generate_log();
    }

    private function do_check_verifyaccess() {
        if (!isset($_SESSION['ZENSS_VERITY_ACCESS'])) {
            return false;
        }
        $authorzi = md5($_SESSION['ZENSS_VERITY_ACCESS']);
        if ($authorzi == ZEN_VERITY_ACCESS) {
            return true;
        }
        return false;
    }

    /**
     * @param bool|string $request_action
     */
    private function getController($request_action = false)
    {
        global $zen;
        $security = load_library('security');
        /**
         * get the route from the url
         */
        if (!empty($request_action)) {
            $route = $request_action;
        } else {
            $route = (empty($_GET['_zen_router_'])) ? '' : $_GET['_zen_router_'];
        }

        $route = $security->cleanXSS($route);
        $route = rtrim($route, '/');

        if (!defined('ROUTER_BEFORE_REWRITE')) {
            define('ROUTER_BEFORE_REWRITE', $route);
        }
        if (empty($route)) {
            $route = $zen['config']['default_router'] . '/index';
        }
        /**
         * checking rewrite url
         */
        foreach ($zen['config']['rewrite_url'] as $search => $replace) {
            if (preg_match($search, $route)) {
                $route = preg_replace($search, $replace, $route);
                break;
            }
        }
        if (is_numeric($route)) {
            $route = $zen['config']['default_router'] . '/index/' . $route;
        }

        if (!defined('ROUTER_AFTER_REWRITE')) {
            define('ROUTER_AFTER_REWRITE', $route);
        }

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
            $this->controller = $zen['config']['default_router'];
        }
        $this->router = implode('/', $parts);

        if (!defined('ROUTER')) {
            define('ROUTER', $this->router);
        }
        if (!defined('ACTION')) {
            define('ACTION', $this->action);
        }

        /**
         * default action is index
         */
        if (empty($this->action)) {
            $this->action = 'index';
        }
        /**
         * set define
         */
        if (!defined('MODULE_DIR')) {
            define('MODULE_DIR', $this->path . '/' . $this->controller);
        }
        if (!defined('MODULE_NAME')) {
            define('MODULE_NAME', $this->controller);
        }
        /**
         * set the fle path
         */
        $this->file = $this->path . '/' . $this->controller . '/' . $this->controller . 'Controller.php';
    }
}
