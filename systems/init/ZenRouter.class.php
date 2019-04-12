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
    public $request_action;
    public $router;
    public $child_router;
    public $controller;
    public $fileController;
    public $class;
    public $action;
    public $module;
    public $setting;
    public $file_settings = '';
    public $runningServices = array();
    public $runningObjects = array();
    public $listSetModuleActivated = array();
    public $listModuleActivated = array();

    public $init = array();

    function __construct()
    {
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

    /**
     * @param bool|string $request_action
     */
    public function loader($request_action = false)
    {
        global $zen, $registry;

        $this->request_action = $request_action;
        /**
         * check the router
         */
        $this->getController();

        /**
         * get list module from modules directory
         */
        $this->listSetModuleActivated = getActiveModule();
        $this->listModuleActivated = array_keys($this->listSetModuleActivated);
        if (!in_array($this->controller, $this->listModuleActivated)) {
            show_error(404);
        }

        /**
         * check the settings
         */
        $this->getSettings();
        if (!$this->file_settings || !file_exists($this->file_settings)) {
            show_error(2000);
        }

        /**
         * Start load settings
         */
        include_once $this->file_settings;

        $settingsClass = $this->controller . 'Settings';
        /**
         * check class settings is exists
         */
        if (!class_exists($settingsClass, false)) {
            show_error(2001);
        }
        /**
         * init setting class
         */
        $setObj = new $settingsClass();
        $this->setting = $setObj->setting;
        $registry->setting = $this->setting;

        /**
         * get action
         */
        $this->getAction();

        $is_basic_type = $this->is_basic_type_of_module();

        if (!$is_basic_type) {
            if (!file_exists($this->fileController)) {
                show_error(404);
            }

            /**
             * include the controller
             */
            require_once $this->fileController;

            $this->class = $this->controller . 'Controller';
            /**
             * check controller class is exists
             */
            if (!class_exists($this->class, false)) {
                show_error(404);
            }
        } else {
            if (!preg_match('/\.php$/', $this->router)) {
                if ($this->action == $zen['config']['default_router_action']) {
                    $loadFile = __MODULES_PATH . '/' . $this->router . '.php';
                } else $loadFile = __MODULES_PATH . '/' . $this->router . '/index.php';
            } else {
                $loadFile = __MODULES_PATH . '/' . $this->router;
            }
            if (!file_exists($loadFile)) {
                show_error(404);
            }
        }


        /**
         * init template
         */
        $this->init_template();

        /**
         * filter access to current router
         */
        $this->filter_access();

        /**
         * Check if router is needed verity access
         */
        $this->verify_access();

        /**
         * run background module
         * This method allways run before run main module
         */
        $this->run_background_module();

        //var_dump($this->runningServices);

        if (!$is_basic_type) {
            /**
             * a new controller class instance
             */
            $controller = new $this->class($registry);

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
             * run _launcher method if exists
             */
            $launcher_exists = false;
            if (method_exists($this->class, '_launcher')) {
                $launcher_exists = true;
            }

            /**
             * run the action
             */
            if (!empty($this->args)) {
                if ($launcher_exists)
                    $controller->_launcher($this->args);
                $controller->$action($this->args);
            } else {
                if ($launcher_exists)
                    $controller->_launcher();
                $controller->$action();
            }
        } else {
            $view = new ZenView($registry);
            $registry->view = $view;
            $view->show('file:' . $loadFile);
        }
    }

    private function is_basic_type_of_module() {
        if (isset ($this->setting['type']) && $this->setting['type'] == 'BASIC') {
            return true;
        }
        return false;
    }

    /**
     * initialize template
     */
    private function init_template()
    {
        global $registry;
        /**
         * review template
         */
        if (is(ROLE_MANAGER) && isset($_GET['_review_template'])) {
            $template = $_GET['_review_template'];
        } else
            $template = getTemplate();

        /**
         * init template class
         */
        $temp = new ZenTemplate($registry);
        $temp->setTemp($template);
        $temp->loader();
        /**
         * get template from database
         */
        $registry->template = $temp->getTemplateName();
        $registry->isTempSystem = $temp->isTempSystem();
        $registry->templateOBJ = $temp;
        /**
         * get template config
         */
        $registry->tplConfig = $temp->getTempConfig();

        /**
         * set define
         */
        define('TEMPLATE', $registry->template);
        if ($registry->isTempSystem) {
            define('_BASE_TEMPLATE', _URL_FILES_SYSTEMS . '/templates/' . TEMPLATE);
            define('_PATH_TEMPLATE', __FILES_PATH . '/systems/templates/' . TEMPLATE);
        } else {
            define('_BASE_TEMPLATE', HOME . '/templates/' . TEMPLATE);
            define('_PATH_TEMPLATE', __TEMPLATES_PATH . '/' . TEMPLATE);
        }
        define('_BASE_TEMPLATE_TPL', _BASE_TEMPLATE . '/' . __FOLDER_TPL_NAME);
        define('_PATH_TEMPLATE_TPL', _PATH_TEMPLATE . '/' . __FOLDER_TPL_NAME);
    }

    /**
     * run background module
     */
    private function run_background_module()
    {
        global $registry;

        $activatedList = array_filter($this->listSetModuleActivated);
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
                        if (isset($this->setting['router_equal'][$this->router])) {
                            if (!is_array($this->setting['router_equal'][$this->router])) {
                                $this->setting['router_equal'][$this->router] = array($this->setting['router_equal'][$this->router]);
                            }
                            $groupRouter = array_merge(array($this->router), $this->setting['router_equal'][$this->router]);
                        } else
                            $groupRouter = array($this->router);

                        $initBG = false;
                        foreach ($groupRouter as $routerItem) {
                            $rmEndSlashRouter = rtrim($routerItem, '/');
                            $fixedRouter = $rmEndSlashRouter . '/';
                            foreach ($neededRouter as $slRouter) {
                                $initBG = false;
                                $check = strpos($fixedRouter, $slRouter);
                                if ($check === 0) {
                                    $initBG = true;
                                }
                                $lastSlash_exists = false;
                                if (preg_match('/\/$/i', $slRouter)) {
                                    $lastSlash_exists = true;
                                }
                                if ($lastSlash_exists == false) {
                                    if ($rmEndSlashRouter == $slRouter) {
                                        $initBG = true;
                                    } else
                                        $initBG = false;
                                }
                                if ($initBG === true)
                                    break;
                            }
                            if ($initBG === true)
                                break;
                        }
                    }

                    if ($initBG == true) {
                        /**
                         * call method action
                         */
                        $this->call_action($mod, $method);
                    }
                }
            }
        }
        $registry->runningServices = $this->runningServices;
    }

    public function call_action($module, $method)
    {
        global $registry;
        $controller_class = $module . 'Controller';
        $settings_class = $module . 'Settings';
        $path_controller = $this->path . '/' . $module . '/' . $controller_class . '.php';
        $path_settings = $this->path . '/' . $module . '/' . $settings_class . '.php';
        if (file_exists($path_controller) && file_exists($path_settings)) {
            include_once $path_controller;
            $real_method = $method;
            $args = array();
            if (strpos($method, '/')) {
                $hash_router = explode('/', $method);
                if (count($hash_router) > 1) {
                    $real_method = $hash_router[0];
                    unset($hash_router[0]);
                    $args = array_values($hash_router);
                }
            }
            if (method_exists($controller_class, $real_method)) {
                /**
                 * check if class is loaded
                 */
                if (!isset($this->runningObjects[$controller_class])) {
                    $this->runningObjects[$controller_class] = new $controller_class($registry);
                }
                $bgOBJ = $this->runningObjects[$controller_class];
                /**
                 * run method
                 */
                $bgOBJ->$real_method($args);
                $this->runningServices[$controller_class][] = $method;
            }
        }
    }

    private function filter_access()
    {
        global $zen, $registry;
        $user = $registry->user;
        $val_perm = 0;
        $val_perm_user = 0;
        $dis_allow_access = false;

        if (isset($this->setting['filter_access']) && $this->setting['filter_access'] !== null) {

            $allowing = $this->setting['filter_access'];
            /**
             * check if the router is enabled
             */
            if (!array_key_exists($this->action, $allowing)) {
                show_error(505);
            }
            if (!$user && $this->controller == 'admin' && $this->action != 'login') {
                redirect(HOME . '/admin/login?urlBack=' . urlencode(curPageURL()));
            }
            if (!isset($user['perm'])) {
                $user_perm = 'guest';
            } else
                $user_perm = $user['perm'];

            if (isset($zen['config']['user_perm']['key'][$allowing[$this->action]])) {
                $val_perm = $zen['config']['user_perm']['key'][$allowing[$this->action]];
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
    }

    private function verify_access()
    {
        /**
         * check if module has VerifyAccess
         */
        if (!isset($this->setting['verify_access'])) {
            $this->setting['verify_access'] = null;
        }
        if ($this->setting['verify_access'] !== null || is_array($this->setting['verify_access'])) {
            /**
             * do action verify
             */
            $this->do_verify_access();
            /**
             * check access
             */
            if ($this->do_check_verify_access() == false) {
                /**
                 * find router verify access
                 */
                foreach ($this->setting['verify_access'] as $rut) {
                    $pos = strpos($this->router, $rut);
                    if ($pos === 0) {
                        show_error(600);
                        break;
                    }
                }
            }
        }
    }

    private function do_verify_access()
    {
        if (!isset($_POST['zen_verity_access'])) {
            return;
        }
        $ss_verity_access = md5($_POST['zen_verity_access']);
        $authorized_code = md5($ss_verity_access);
        if (!defined('ZEN_VERITY_ACCESS')) {
            define('ZEN_VERITY_ACCESS', ZEN_DEFAULT_PASSWORD);
        }
        if ($authorized_code == ZEN_VERITY_ACCESS) {
            $_SESSION['ZENSS_VERITY_ACCESS'] = $ss_verity_access;
        } else
            generate_log();
    }

    private function do_check_verify_access()
    {
        if (!isset($_SESSION['ZENSS_VERITY_ACCESS'])) {
            return false;
        }
        $authorized_code = md5($_SESSION['ZENSS_VERITY_ACCESS']);
        if ($authorized_code == ZEN_VERITY_ACCESS) {
            return true;
        }
        return false;
    }

    /**
     * Get controller
     */
    private function getController()
    {
        global $zen, $registry;

        /**
         * get the route from the url
         */
        if (!empty($this->request_action)) {
            $route = $this->request_action;
        } else
            $route = (empty($_GET['_zen_router_'])) ? '' : $_GET['_zen_router_'];

        $route = rtrim($route, '/');

        /**
         * DEFINE ROUTER_BEFORE_REWRITE
         */
        if (!defined('ROUTER_BEFORE_REWRITE')) {
            define('ROUTER_BEFORE_REWRITE', $route);
        }
        if (!$route) {
            $route = $zen['config']['default_router'] . '/' . $zen['config']['default_router_action'];
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
            $route = $zen['config']['default_router'] . '/' . $zen['config']['default_router_action_numeric'] . '/' . $route;
        }

        /**
         * DEFINE ROUTER_AFTER_REWRITE
         */
        if (!defined('ROUTER_AFTER_REWRITE')) {
            define('ROUTER_AFTER_REWRITE', $route);
        }

        /**
         * analysis and "hash" router
         */
        $parts = explode('/', $route);
        /**
         * remove empty part (remove if is string empty)
         */
        array_walk($parts, function ($val, $key) use (&$parts) {
            if ($val === '') unset ($parts[$key]);
        });

        $parts = array_values($parts);

        if (empty($parts[1])) {
            $parts[1] = $zen['config']['default_router_action'];
        }
        /**
         * set controller
         */
        $this->controller = $parts[0];

        $tmpParts = $parts;
        unset ($tmpParts[0]);
        /**
         * set action
         */
        $this->action = implode('/', $tmpParts);

        /**
         * If router does not exist
         * it will automatically load the router default
         */
        if (empty($this->controller)) {
            $this->controller = $zen['config']['default_router'];
        }
        $this->router = implode('/', $parts);

        /**
         * set define
         */
        if (!defined('ROUTER')) {
            define('ROUTER', $this->router);
        }
        if (!defined('CONTROLLER')) {
            define('CONTROLLER', $this->controller);
        }
        if (!defined('MODULE_DIR')) {
            define('MODULE_DIR', $this->path . '/' . $this->controller);
        }
        if (!defined('MODULE_NAME')) {
            define('MODULE_NAME', $this->controller);
        }
        /**
         * set the fle path
         */
        $this->fileController = $this->path . '/' . $this->controller . '/' . $this->controller . 'Controller.php';

        $registry->router = $this->router;
        $registry->controller = $this->controller;
    }

    private function getAction() {
        global $registry;
        if (isset($this->setting['rewrite_url']) && is_array($this->setting['rewrite_url'])) {
            foreach ($this->setting['rewrite_url'] as $search => $replace) {
                if (preg_match($search, $this->action)) {
                    $this->action = preg_replace($search, $replace, $this->action);
                    break;
                }
            }
        }
        $hashAction = explode('/', $this->action);
        if (count($hashAction) >= 2) {
            $this->action = $hashAction[0];
            $this->args = $hashAction;
            unset($this->args[0]);
            $this->args = array_values($this->args);
        } else $this->args = array();

        if (!defined('ACTION')) {
            define('ACTION', $this->action);
        }

        $registry->action = $this->action;
        $registry->args = $this->args;
    }

    /**
     * get setting file
     */
    private function getSettings()
    {

        $path = $this->path . '/' . $this->controller . '/' . $this->controller .
            'Settings.php';
        /**
         * check setting file exist
         */
        if (file_exists($path) && is_readable($path) == true) {
            $this->file_settings = $path;
        }
    }

}
