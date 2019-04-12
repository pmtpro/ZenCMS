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

/**
 * auto load classes
 *
 * @param string $class_name
 * @return boolean
 */
function __autoload($class_name)
{
    $filename = strtolower($class_name) . '.lib.php';

    $file = __SITE_PATH . '/systems/libraries/' . $filename;

    if (file_exists($file) == false) {
        return false;
    }
    include_once($file);
}


/**
 *
 * get table prefix
 *
 * @return string
 */
if (!function_exists('tb')) {

    function tb()
    {
        global $system_config;

        static $_table_prefix;

        if (isset($_table_prefix)) {

            return $_table_prefix;
        }
        $_table_prefix = $system_config['table_prefix'];

        return $system_config['table_prefix'];
    }
}

/**
 * this function will return to the hompage address.
 * if your address isn't set before, it will returns the value 'http host'
 *
 * @return string
 */
if (!function_exists('home')) {

    function home()
    {
        global $system_config;

        static $_home;

        if (isset($_home)) {

            return $_home;
        }

        if (isset($system_config['from_db']['home'])) {

            $_home = $system_config['from_db']['home'];

            return $system_config['from_db']['home'];

        } else {

            return get_http_host();
        }
    }
}

/**
 * return http host
 *
 * @return string
 */
if (!function_exists('get_http_host')) {

    function get_http_host()
    {

        $scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';

        $host = $_SERVER['HTTP_HOST'];

        $home = sprintf('%s://%s/', $scheme, $host);

        $home = rtrim($home, '/');

        return $home;
    }
}


/**
 *
 * This function acts as a singleton.  If the requested class does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 *
 * @access    public
 * @param    string    the class name being requested
 * @param    string    the directory where the class should be found
 * @return    object
 */
if (!function_exists('load_library')) {

    function &load_library($class, $directory = 'libraries')
    {
        global $registry;

        static $_classes = array();

        /**
         * Does the class exist?  If so, we're done...
         */
        if (isset($_classes[$class])) {

            return $_classes[$class];
        }

        $name = FALSE;

        if (defined('__MODULE_PATH')) {

            $arr_inc = array(__MODULE_PATH, __SITE_PATH, __SITE_PATH . '/systems');

        } else {

            $arr_inc = array(__SITE_PATH, __SITE_PATH . '/systems');
        }

        /**
         * look libraries in background module
         */
        $mod = get_list_modules();

        $bg_mod = $mod[BACKGROUND];

        foreach ($bg_mod as $bg) {

            $arr_inc[] = __MODULES_PATH . '/' . $bg;
        }

        /**
         * Look for the class first in the module directory
         * then in the local libraries folder
         * end in the native system/libraries folder
         */
        foreach ($arr_inc as $path) {

            $file_name = $class . '.lib';

            $path_class = $path . '/' . $directory . '/' . $file_name . '.php';

            $path_sub_class = $path . '/' . $directory . '/' . $class . '/' . $file_name . '.php';

            if (file_exists($path_class)) {

                $name = $class;

                if (class_exists($name, false) === FALSE) {

                    require($path_class);

                }

                break;

            } else {

                if (file_exists($path_sub_class)) {

                    $name = $class;

                    if (class_exists($name, false) === FALSE) {

                        require($path_sub_class);
                    }

                    break;
                }
            }
        }

        /**
         * Did we find the class?
         */
        if ($name === FALSE) {

            $erro = debug_backtrace();

            show_error(505, 'Unable to locate the specified class: ' . $file_name . '.php in ' . $erro[0]['file'] . ' on line ' . $erro[0]['line']);
        }

        /**
         * Keep track of what we just loaded
         */
        is_loaded($class);

        $object = new $name();

        if (method_exists($object, 'setRegistry')) {

            $object->setRegistry($registry);
        }

        $_classes[$class] = $object;

        return $_classes[$class];
    }

}

// --------------------------------------------------------------------

/**
 * Keeps track of which libraries have been loaded.  This function is
 * called by the load_class() function above
 *
 * @access    public
 * @return    array
 */
if (!function_exists('is_loaded')) {

    function &is_loaded($class = '')
    {
        static $_is_loaded = array();

        if ($class != '') {

            $_is_loaded[strtolower($class)] = $class;
        }

        return $_is_loaded;
    }

}

/**
 * Đây là hàm dùng để gọi các "helper"
 * Nếu "helper" không tồn tại, quá trình sẽ bị dừng tại đây
 *
 * @param string $helper
 * @param string $directory
 */
if (!function_exists('load_helper')) {

    function load_helper($helper, $directory = 'helpers')
    {
        global $registry;

        $name = FALSE;

        if (defined('__MODULE_PATH')) {

            $arr_inc = array(__MODULE_PATH, __SITE_PATH, __SYSTEMS_PATH);

        } else {

            $arr_inc = array(__SITE_PATH, __SYSTEMS_PATH);
        }

        /**
         * Look for the helper first in the module directory
         * then in the local helpers folder
         * end in the native system/helpers folder
         */
        foreach ($arr_inc as $path) {

            $file_name = $helper . '.helper';

            $path_helper = $path . '/' . $directory . '/' . $file_name . '.php';

            if (file_exists($path_helper)) {

                $name = $helper;

                require_once($path_helper);

                break;
            }
        }

        /**
         * Did we find the class?
         */
        if ($name === FALSE) {

            $erro = debug_backtrace();
            /**
             * show 505 error
             */
            show_error(505, 'Unable to load helper: ' . $file_name . '.php in ' . $erro[0]['file'] . ' on line ' . $erro[0]['line']);
        }
    }
}

// --------------------------------------------------------------------

/**
 *
 * @global array $app
 * @global object $obj
 * @param string $path
 * @param array $apps
 * @param object $objs
 */
if (!function_exists('load_apps')) {

    function load_apps($path, $apps)
    {
        global $app, $obj, $registry;

        /**
         * load security library
         */
        $security = load_library('security');

        $app = $apps;
        $obj = $registry;

        if (empty($apps[0])) {

            $apps[0] = 'index';
        }

        if (isset($apps[0])) {

            $app[0] = $security->cleanXSS($app[0]);

            $path = rtrim($path, '/');

            $foo_app = explode('/', $path);

            $parent_app = end($foo_app);

            $file = $path . '/' . $apps[0] . '.' . $parent_app . '.php';

            if (!file_exists($file)) {

                show_error(404);

            } else {

                include_once $file;
            }
        }
    }
}


/**
 * get app from path and extend module
 *
 * @param $path
 * @param mixed|string $router
 * @param bool $both_index
 * @return array
 */
if (!function_exists('get_apps')) {

    function get_apps($path, $router = __ROUTER, $both_index = false) {

        $menus = get_apps_from_path($path, $router, $both_index);

        $extend = get_extend_apps($router);

        if (!empty($extend) && is_array($extend)) {

            $menus = array_merge($menus, $extend);
        }

        return $menus;
    }
}

/**
 * @param bool $path
 * @return array
 */
if (!function_exists('get_extend_apps')) {

    function get_extend_apps($path = false)
    {

        global $registry;

        /**
         * load helper: fhandle
         */
        load_helper('fhandle');

        $arr_mods = scan_modules();

        $out = array();

        foreach ($arr_mods as $mod_name => $arr_info) {

            $blog_set = $registry->settings->get($mod_name);

            if (isset($blog_set->setting['extends']) && is_array($blog_set->setting['extends'])) {

                foreach ($blog_set->setting['extends'] as $url => $extend) {

                    if (isset($extend['router']) && $extend['router'] == $path) {

                        if (!isset($extend['name'])) {

                            $mod_title = $mod_name . ' (' . $url . ')';

                        } else {

                            $mod_title = $extend['name'];
                        }

                        $mod['icon'] = isset($extend['icon']) ? $extend['icon'] : 'app';
                        $mod['name'] = $mod_title;
                        $mod['url'] = $url;
                        $mod['title'] = $mod_title;
                        $mod['full_url'] = _HOME . '/' . $mod_name . '/' . $url;
                        $out[] = $mod;
                    }
                }
            }
        }

        return $out;
    }
}

/**
 * @param $path
 * @param bool $both_index
 * @return array
 */
if (!function_exists('get_apps_from_path')) {

    function get_apps_from_path($path, $router = __ROUTER, $both_index = false)
    {
        global $registry;

        $obj = $registry;
        /**
         * load libraries
         */
        $parse = load_library('parse');
        $permission = load_library('permission');

        $get = array();

        $tmp = array();

        $menus = array();

        if (empty($router)) {

            $router = __ROUTER;
        }

        $list_path = explode('/', $path);

        $sub_name = end($list_path);

        $ignored = array('.', '..', '.svn', '.htaccess');

        if ($both_index == true) {

            $ignored[] = 'index.' . $sub_name . '.php';
        }

        $files = @scandir($path);

        if (empty($files)) {

            return array();

        }

        foreach ($files as $k => $file) {

            if (!in_array($file, $ignored)) {

                $str = $parse->ini_php_file_comment($path . '/' . $file);

                if (isset($str['name'])) {

                    if (isset($str['position'])) {

                        $pos = $str['position'];
                    } else {
                        $pos = 99999;
                    }

                    $tmp[$file] = $pos;

                }

            } else {

                unset ($files[$k]);
            }
        }

        asort($tmp);

        $files = array_keys($tmp);

        foreach ($files as $file) {

            if (!in_array($file, $ignored)) {

                $str = $parse->ini_php_file_comment($path . '/' . $file);

                $url = str_replace('.' . $sub_name . '.php', '', $file);

                if (isset($str['name'])) {

                    if (isset($str['allow_access'])) {

                        $lists_access = explode(',', $str['allow_access']);

                        foreach ($lists_access as $key => $perm) {

                            $lists_access[$key] = trim($perm);
                        }

                        if (in_array($obj->user['perm'], $lists_access) or $permission->is_admin()) {

                            $get['name'] = $str['name'];
                            $get['url'] = $url;
                            $get['full_url'] = _HOME . '/' . $router . '/' . $url;
                            $get['icon'] = isset($str['icon']) ? $str['icon'] : 'app';
                        }

                    } else {

                        $get['name'] = $str['name'];
                        $get['url'] = $url;
                        $get['full_url'] = _HOME . '/' . $router . '/' . $url;
                        $get['icon'] = isset($str['icon']) ? $str['icon'] : 'app';
                    }
                    $menus[] = $get;
                }
            }
        }

        return $menus;
    }

}

/**
 * return list modules in module folder
 *
 * @return mixed
 */
if (!function_exists('get_list_modules')) {

    function get_list_modules()
    {

        static $_cache_modules = array();

        if (!empty ($_cache_modules)) {

            return $_cache_modules;
        }

        $cache_file = __MODULES_PATH . '/modules.dat';

        $data = @file_get_contents($cache_file);

        $data = @unserialize($data);

        if (!isset($data[BACKGROUND])) {

            $data[BACKGROUND] = array();
        }

        if (!isset($data[APP])) {

            $data[APP] = array();
        }

        if (empty($data) || empty($data[APP])) {

            $data[APP][] = 'admin';
        }

        /**
         * auto load protected module
         */
        $list_protected = sys_config('modules_protected');

        foreach ($list_protected[BACKGROUND] as $mod_protected) {

            if (!in_array($mod_protected, $data[BACKGROUND])) {

                $data[BACKGROUND][] = $mod_protected;
            }
        }

        foreach ($list_protected[APP] as $mod_protected) {

            if (!in_array($mod_protected, $data[APP])) {

                $data[APP][] = $mod_protected;
            }
        }

        $_cache_modules = $data;

        return $data;
    }
}

/**
 * the new way to get model of a module
 *
 * @param bool $name
 * @return mixed
 */
if (!function_exists('model')) {

    function model($name = false)
    {
        global $registry;

        return $registry->model->get($name);
    }
}


/**
 * run a hook
 *
 * @param string $module
 * @param string $hook_name
 * @param string $hook_run
 */
if (!function_exists('run_hook')) {

    function run_hook($module = _PUBLIC, $hook_name, $hook_run, $weight = null)
    {

        if (is_null($weight)) {


            $GLOBALS['hook'][$module][$hook_name][] = $hook_run;

        } else {

            $weight = (int)$weight;

            $GLOBALS['hook'][$module][$hook_name][$weight] = $hook_run;
        }
    }

}

if (!function_exists('hook_data')) {

    function hook_data($module = _PUBLIC, $hook_name, $data = null) {

        if (!is_null($data)) {

            $GLOBALS['hook_data'][$module][$hook_name] = $data;
        }
    }
}

/**
 * @param string $module
 * @param $name
 * @param bool $data
 * @param bool $protected
 * @return bool
 */
if (!function_exists('hook')) {

    function hook($module = _PUBLIC, $name, $data = false, $protected = false)
    {

        if (empty($module)) {

            $module = _PUBLIC;
        }

        if (isset($GLOBALS['hook_data'][$module][$name]) && !is_null($GLOBALS['hook_data'][$module][$name])) {

            return $GLOBALS['hook_data'][$module][$name];
        }

        if (!isset($GLOBALS['hook'][$module][$name])) {

            return $data;
        }

        $listFunction = $GLOBALS['hook'][$module][$name];

        if (!is_array($listFunction)) {

            $HookFunction = $listFunction;

            return $HookFunction($data);

        } else {

            $weights = array_keys($listFunction);

            /**
             * sort hook by weight
             */
            sort($weights);

            /**
             * if the hook has to be protected,
             * just run the hook end
             */
            if ($protected == true) {

                $HookFunction = $listFunction[end($weights)];

                return $HookFunction($data);
            }

            /**
             * if the hook is not protected,
             * run the all hook
             */
            foreach ($weights as $w) {

                $HookFunction = $listFunction[$w];

                $data = $HookFunction($data);

            }

            return $data;
        }
    }

}

/**
 * @param $name
 * @param bool $data
 * @param bool $protected
 * @return bool
 */
if (!function_exists('phook')) {

    function phook($name, $data = false, $protected = false)
    {

        echo hook(_PUBLIC, $name, $data, $protected);
    }

}


/**
 * replace function to new function
 * syntax remains the same
 *
 * @param $search
 * @param $replace
 */
if (!function_exists('function_replace')) {

    function function_replace($search, $replace)
    {
        if (!empty($replace) && !empty($replace)) {

            $GLOBALS['function_replace'][$search] = $replace;
        }

    }
}

/**
 * check function is ready to replace
 *
 * @param $name
 * @return bool
 */
if (!function_exists('check_function_replace')) {

    function check_function_replace($name)
    {
        if (isset($GLOBALS['function_replace'][$name])) {

            return true;
        }
    }
}

/**
 * load new function
 *
 * @param $name
 * @param $data
 * @return bool
 */
if (!function_exists('load_function')) {

    function load_function($name, $data)
    {
        $out = false;

        if (isset($GLOBALS['function_replace'][$name])) {

            $function = $GLOBALS['function_replace'][$name];

            $pushs = array();

            if (!empty($data) && is_array($data)) {

                foreach ($data as $k => $val) {

                    $pushs[] = '$data[' . $k . ']';

                }
                $push_on = implode(', ', $pushs);
            }

            $code1 = $function . '(' . $push_on . ')';

            $code2 = '$out = ' . $code1 . ';';

            eval($code2);

            return $out;
        }
        return false;
    }
}


/**
 *
 * @param array $widget_data
 * @return bool
 */
if (!function_exists('register_widget_group')) {

    function register_widget_group($widget_data = array())
    {

        if (empty ($widget_data['name'])) {

            return false;
        }

        if (isset ($GLOBALS['widgets'][$widget_data['name']])) {

            return false;
        }

        $GLOBALS['widgets'][$widget_data['name']]['data'] = $widget_data;

    }
}

if (!function_exists('widget')) {

    function widget_group($name)
    {

        if (!isset($GLOBALS['widgets'][$name]) || empty ($GLOBALS['widgets'][$name]['data'])) {

            return false;
        }
        /**
         * get data widget group
         */
        $wdata = $GLOBALS['widgets'][$name]['data'];

        if (!isset($wdata['start_title'])) {

            $wdata['start_title'] = '';
        }
        if (!isset($wdata['end_title'])) {

            $wdata['end_title'] = '';
        }
        if (!isset($wdata['start_content'])) {

            $wdata['start_content'] = '';
        }
        if (!isset($wdata['end_content'])) {

            $wdata['end_content'] = '';
        }

        /**
         * Check the widget display
         */
        if (isset($wdata['display'])) {

            if (!is_array($wdata['display'])) {

                if (!is($wdata['display'], ONLY_THIS_PERM)) {

                    return;
                }

            } else {

                $show = false;

                foreach ($wdata['display'] as $who_allow) {

                    if (is($who_allow, ONLY_THIS_PERM)) {

                        $show = true;
                        break;
                    }
                }

                if ($show == false) {

                    return;
                }
            }
        }

        $widgets = model()->_get_widget_group($name);

        $GLOBALS['widgets'][$name]['list'] = $widgets;

        foreach ($GLOBALS['widgets'][$name]['list'] as $widget) {

            /**
             * print title widget
             */
            if (!empty ($widget['title'])) {

                echo $wdata['start_title'] . h_decode($widget['title']) . $wdata['end_title'];
            }

            /**
             * print content widget
             */
            if (!empty ($widget['content'])) {

                echo $wdata['start_content'] . h_decode($widget['content']) . $wdata['end_content'];
            }
        }
    }

}

/**
 * this dunction will unregister globals var
 */
if (!function_exists('unregister_globals')) {

    function unregister_globals()
    {
        if (ini_get('register_globals')) {

            $array = array('_REQUEST', '_SESSION', '_SERVER', '_ENV', '_FILES');

            foreach ($array as $value) {

                foreach ($GLOBALS[$value] as $key => $var) {

                    if ($var === $GLOBALS[$key]) {

                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }
}


/**
 * @access public
 * @param key config
 * Load system config from ZenConfig_Main.php
 */
if (!function_exists('sys_config')) {

    function sys_config($key)
    {
        global $system_config;

        /**
         * check sys config exist
         */
        if (isset($system_config[$key])) {

            return $system_config[$key];

        } else {

            return false;
        }
    }

}

/**
 * @access public
 * @param key config
 * Load system config from file config.php in current template
 */
if (!function_exists('tpl_config')) {

    function tpl_config($key)
    {
        global $template_config;

        /**
         * check template config exist
         */
        if (isset($template_config[$key])) {

            return $template_config[$key];

        } else {

            return false;
        }
    }

}

/**
 * load error controller
 *
 * @param $num
 * @param string $msg
 */
if (!function_exists('show_error')) {

    function show_error($num, $msg = '')
    {
        global $system_config, $registry;

        $controller = $system_config['default_router_error'];

        $path = __MODULES_PATH;

        if (isset($num))
            $error_num = $num;
        else
            $error_num = 404;

        $path_error_controller = $path . '/' . $controller . '/' . $controller . 'Controller.php';

        include_once $path_error_controller;

        $class = $controller . 'Controller';
        $action = 'index';
        $args = array($error_num);

        /**
         * load error controller
         */
        $controller = new $class($registry);

        if (!empty($msg)) {

            $controller->setSpecialMsg($msg);
        }

        $controller->$action($args);
        exit;
    }
}

/**
 * @access public
 * @param key config
 * Load system config from database
 */
if (!function_exists('get_config')) {

    function get_config($key = '')
    {

        global $system_config;

        if (isset($system_config['from_db'][$key])) {

            if ($key == 'templates') {

                $out = @unserialize($system_config['from_db'][$key]);

            } else {

                $out = $system_config['from_db'][$key];
            }

            return $out;

        } else {

            return false;
        }
    }
}

/**
 * @access public
 * get template name
 */
if (!function_exists('get_template')) {

    function get_template()
    {
        global $system_config;

        if (!empty($_SESSION['ss_review_template'])) {

            return $_SESSION['ss_review_template'];
        }

        $device = load_library('DDetect');

        if (!isset($system_config['from_db']['templates'])) {

            $templates = array();

        } else {

            $templates = get_config('templates');
        }

        if (empty($templates)) {

            $templates['Mobile'] = 'default';
            $templates['other'] = 'default';
        }

        if ($device->isMobile()) {

            $out = $templates['Mobile'];
        } else {

            $out = $templates['other'];
        }

        foreach ($templates as $os => $value) {

            if (!empty($value)) {

                if ($os != 'other' && $os != 'Mobile') {

                    $method = 'is' . $os;

                    if ($device->$method()) {

                        $out = $value;
                        break;
                    }
                }
            }
        }

        if (empty($out)) {

            $out = 'default';
        }

        return $out;
    }

}

/**
 * load header layout
 */
if (!function_exists('load_header')) {

    function load_header()
    {
        load_layout('header');
    }
}

/**
 * load layout footer
 */
if (!function_exists('load_footer')) {

    function load_footer()
    {
        load_layout('footer');
    }
}

/**
 * load layout message
 */
if (!function_exists('load_message')) {

    function load_message()
    {
        load_layout('message');
    }
}

if (!function_exists('load_mini_layout')) {

    function load_mini_layout($name, $data)
    {

        /**
         * set layout path
         */
        $layout[] = _PATH_TEMPLATE_TPL . '/' . __MODULE_NAME . '/layout/' . $name . '.layout.php';

        $layout[] = _PATH_TEMPLATE . '/layout/' . $name . '.layout.php';

        $layout[] = __MODULES_PATH . '/' . __MODULE_NAME . '/tpl/layout/' . $name . '.layout.php';

        foreach ($layout as $file) {

            if (file_exists($file)) {

                /**
                 * load the layout
                 */
                include $file;

                break;
            }
        }
    }
}

/**
 * This function will load the "layout" in the desired location.
 * The priority order for the "layout" as follows:
 * First, it will look to the folder "layout" in __FOLDER_TPL_NAME.
 * If it is not compatible with the layout will look to the "layout" in the "layout" of the "template"
 *
 * @param $name
 */
if (!function_exists('load_layout')) {

    function load_layout($name, $show_with = false, $only_this_perm = false)
    {
        global $registry_data, $registry;

        /**
         * load permission library
         */
        $permission = load_library('permission');

        if (is_bool($show_with) && $show_with == true) {

            if (!$permission->is_manager()) {

                return false;
            }

        } else {

            if (!empty ($show_with)) {

                $check = 'is_' . $show_with;

                if (!$permission->$check($only_this_perm)) {

                    return false;
                }
            }
        }

        /**
         * fetch data
         */
        foreach ($registry_data as $key => $value) {

            $$key = $value;
        }

        /**
         * check title
         * if title not exists then get default title
         */
        if (!isset($page_title)) $page_title = get_config('title');

        if (!isset($page_keyword)) $page_keyword = '';

        if (!isset($page_des)) $page_des = '';

        if (!isset($page_url)) $page_url = '';

        /**
         *
         * decode title
         */
        $page_title = h_decode($page_title);

        /**
         * get page more
         */
        if (isset($page_more)) {

            if (!is_array($page_more)) {

                $page_more = array($page_more);
            }

        } else {

            $page_more = array();
        }

        /**
         * get success message
         */
        if (isset($success)) {

            if (!is_array($success)) {

                $success = array($success);
            }

        } else {

            if (!empty($_SESSION['msg']['success'])) {

                $success = array($_SESSION['msg']['success']);

                unset($_SESSION['msg']['success']);

            } else {
                $success = array();
            }
        }

        /**
         * get errors message
         */
        if (isset($errors)) {

            if (!is_array($errors)) {

                $errors = array($errors);
            }

        } else {

            $errors = array();
        }

        /**
         * get notices message
         */
        if (isset($notices)) {

            if (!is_array($notices)) {

                $notices = array($notices);
            }

        } else {

            $notices = array();
        }

        $getsetting = $registry->settings->get(__MODULE_NAME);

        if (!empty($getsetting)) {

            if (!isset($getsetting->setting['own_template'])) {

                $getsetting->setting['own_template'] = null;
            }

            $own_theme = $getsetting->setting['own_template'];

        } else {

            $own_theme = null;
        }

        if (!is_null($own_theme)) {

            $layout[] = __MODULES_PATH . '/' . __MODULE_NAME . '/templates/' . $own_theme . '/' . __FOLDER_TPL_NAME . '/layout/' . $name . '.layout.php';

            $layout[] = __MODULES_PATH . '/' . __MODULE_NAME . '/templates/' . $own_theme . '/layout/' . $name . '.layout.php';

        } else {
            /**
             * set layout path
             */
            $layout[] = _PATH_TEMPLATE_TPL . '/' . __MODULE_NAME . '/layout/' . $name . '.layout.php';

            $layout[] = _PATH_TEMPLATE . '/layout/' . $name . '.layout.php';

            $layout[] = __MODULES_PATH . '/' . __MODULE_NAME . '/tpl/layout/' . $name . '.layout.php';

        }

        foreach ($layout as $file) {

            if (file_exists($file)) {

                /**
                 * load the layout
                 */
                include $file;

                break;
            }
        }
    }
}


/**
 *
 * @param $perm
 * @return bool
 */
if (!function_exists('is_perm')) {

    function is_perm($perm, $only_this_perm = false)
    {
        global $registry;

        if (empty($perm)) {

            return false;
        }

        $permission = load_library('permission');

        $permission->set_user($registry->user);

        $check = 'is_' . $perm;

        if ($permission->$check($only_this_perm)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is')) {

    function is($perm, $only_this_perm = false)
    {

        return is_perm($perm, $only_this_perm);
    }
}


if (!function_exists('set_global_msg')) {

    function set_global_msg($msg = '')
    {

        $err = debug_backtrace();

        $function = $err[1]['function'];

        $GLOBALS['errors_msg'][$function] = $msg;
    }
}

if (!function_exists('get_global_msg')) {

    function get_global_msg($where)
    {

        if (isset($GLOBALS['errors_msg'][$where])) {

            return $GLOBALS['errors_msg'][$where];
        }
    }
}

/**
 * auto make temp dir
 *
 * @return bool|string
 */
if (!function_exists('tempdir')) {

    function tempdir($prefix = '_Z_')
    {
        $dir = __FILES_PATH . '/systems/tmp';

        $tempfile = tempnam($dir, $prefix);

        if (file_exists($tempfile)) {

            unlink($tempfile);
        }

        $ok = mkdir($tempfile);

        if ($ok) {

            if (is_dir($tempfile)) {

                return $tempfile;
            }
            return false;
        }
        return false;
    }
}

/**
 * encode htmlspecialchars for both array and string
 *
 * @param string $var
 * @param bool $flags
 * @param string $encoding
 * @return array|string
 */
if (!function_exists('h')) {

    function h($var = '', $flags = ENT_QUOTES, $encoding = 'UTF-8')
    {
        if (is_array($var)) {

            foreach ($var as $id => $str) {

                if (!is_array($var[$id])) {

                    $var[$id] = htmlspecialchars($str, $flags, $encoding);

                } else {
                    h($var[$id]);
                }
            }
            return $var;
        }
        return htmlspecialchars($var, $flags, $encoding);
    }
}

/**
 * @param string $var
 * @param int $flags
 * @param string $encoding
 * @return array|string
 */
if (!function_exists('h_decode')) {

    function h_decode($var = '', $flags = ENT_QUOTES)
    {

        if (is_array($var)) {

            foreach ($var as $id => $str) {

                if (!is_array($var[$id])) {

                    $var[$id] = htmlspecialchars_decode($str, $flags);

                } else {
                    h_decode($var[$id]);
                }
            }
            return $var;
        }
        return htmlspecialchars_decode($var, $flags);
    }
}


/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access    public
 * @param    string
 * @return    string
 */
if (!function_exists('remove_invisible_characters')) {

    function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

}

if (!function_exists('generate_log')) {

    function generate_log($name = LOG_VERIFY_ACCESS, $msg = false)
    {

        global $registry;

        $dir_log = __FILES_PATH . '/systems/log';

        if ($name == LOG_DDOS) {

            $file_log = $dir_log . '/ipfoods/' . client_ip() . '.log';

            if (empty($msg)) {

                $msg = microtime_float() . "\r\n";
            }

        } elseif ($name == LOG_VERIFY_ACCESS) {

            $file_log = $dir_log . '/verify_access.log';

            if (!$msg) {

                if (isset($registry->user['username'])) {

                    $msg = $registry->user['username'] . ': ';

                } else {

                    $msg = '';
                }
                $msg .= 'Trying to login: ' . curPageURL() . ' with password: ' . $_POST['zen_verity_access'];
            }
        }

        load_helper('time');

        error_log("[" . get_date_time(time(), 'date-time') . "] [" . client_ip() . "] [$msg] [" . client_user_agent() . "]\r\n", 3, $file_log);
    }
}

if (!function_exists('is_really_writable')) {
    /**
     * Check the operating system.  is_really_writable needs to be defined
     * specifically for Windows, but the overhead is pointless otherwise.
     */
    if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {

        /**
         * If we are not on a linux platform, we can assume nothing,
         * Windows, for instance, has a really screwy permissions system
         * that PHP doesn't seem to understand fully.
         *
         * @param $file
         * @return bool
         */
        function is_really_writable($file)
        {

            /**
             * For a full understanding of how this function is
             * testing file, which tests PHP's behavior in known
             * circumstances which may vary from OS to OS.
             */
            if (!file_exists($file)) {
                // If the file does not exist, is_writable will return... False
            }

            if (is_file($file)) {
                // Try to open the file in write mode (binary for good measure)
                // We have to supress error output.
                $tmpfh = @fopen($file, 'ab');
                if ($tmpfh == false) {
                    // If the fopen call returned false, we can't write to the file
                    // Just return false.  No need to close the invalid handle.
                    return false;
                } else {
                    // If the fopen call didn't return false, we can write to the file
                    // So, close the handle (since it is valid) and return true.
                    fclose($tmpfh);
                    return true;
                }
            } else if (is_dir($file)) {
                // Try to create a new file in the directory.
                // Need a sufficiently uniq name.  In the future,
                // we may find it useful to loop until we find
                // a nonexistent file, but this works for now.
                $tmpnam = time() . md5(uniqid('iswritable'));
                if (touch($file . '/' . $tmpnam)) {
                    // If we can touch (create) the file, then we can write to the directory.
                    // So, remove the temporary file and return true.
                    unlink($file . '/' . $tmpnam);
                    return true;
                } else {
                    // If touch returns false, we can't write to the directory.
                    // No file to delete, just return false.
                    return false;
                }
            }
        }

    } else {

        // If we are on a linux platform, then we don't need to do anything
        // special -- Linux has a sane permissions system that PHP
        // understands.

        function is_really_writable($file)
        {
            // At this point, is_really_writable simply becomes a wrapper
            // for the standard is_writable call.
            // see http://php.net/is_writable
            return is_writable($file);
        }

    }
}