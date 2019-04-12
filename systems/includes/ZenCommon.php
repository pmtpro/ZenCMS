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

/**
 * get table prefix
 * @return string
 */
if (!function_exists('tb')) {
    function tb() {
        global $zen;
        return $zen['config']['table_prefix'];
    }
}

/**
 * this function will return to the hompage address.
 * if your address isn't set before, it will returns the value 'http host'
 *
 * @return string
 */
if (!function_exists('home')) {
    function home() {
        global $zen;
        if (isset($zen['config']['fromDB']['home'])) {
            return $zen['config']['fromDB']['home'];
        } else return getHttpHost();
    }
}

/**
 * get current router
 */
if (!function_exists('getRouter')) {
    function getRouter() {
        if (defined('ROUTER')) {
            return ROUTER;
        } else return '';
    }
}

/**
 * get router url
 */
if (!function_exists('getRouterUrl')) {
    function getRouterUrl() {
        if (defined('ROUTER')) {
            return HOME . '/' . ROUTER;
        } else {
            $curUrl = curPageURL();
            $hash = explode('?', $curUrl);
            if (isset($hash[0])) {
                return $hash[0];
            } else {
                return $curUrl;
            }
        }
    }
}


/**
 * get current page url
 * @return bool|string
 */
if (!function_exists('curPageURL')) {

    function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

if (!function_exists('curBaseURL')) {
    function curBaseURL($url = false) {
        static $_static_function;
        if (!empty($url)) {
            $_static_function['curBaseURL'] = $url;
        }
        if (isset($_static_function['curBaseURL'])) {
            return $_static_function['curBaseURL'];
        }
        return '';
    }
}

/**
 * gen url for extend app. ex: http://localhost/admin/general/modulescp?appFollow=blog/manager
 * @param string $router
 * @return string
 */
if (!function_exists('genUrlAppFollow')) {
    function genUrlAppFollow($router) {
        return REAL_HOME . '/admin/general/modulescp?appFollow=' . $router;
    }
}

/**
 * this function will return TRUE if request executed by ajax jquery.
 * @return bool
 */
if (!function_exists('is_ajax_request')) {

    function is_ajax_request($field = 'is-ajax-request') {
        if (empty($field)) $field = 'is-ajax-request';
        if (isset($_POST[$field]) || isset($_GET[$field])) {
            return true;
        }
        return false;
    }
}


/**
 * generate request address. Default is md5(USER_IP-USER_AGENT);
 * @return string
 */
if (!function_exists('genRequestAddress')) {

    function genRequestAddress() {
        return md5(client_ip() . '-' . client_user_agent());
    }
}

/**
 * generate token for a ajax request or other
 * @return string mixed
 */
if (!function_exists('genRequestToken')) {

    function genRequestToken() {
        $token_address = genRequestAddress();
        if (empty($_SESSION['ZENSS_REQUEST_TOKEN'][$token_address])) {
            $_SESSION['ZENSS_REQUEST_TOKEN'][$token_address] = md5(randStr(10));
        }
        return $_SESSION['ZENSS_REQUEST_TOKEN'][$token_address];
    }
}

/**
 * get token of a request
 * @return mixed
 */
if (!function_exists('getRequestToken')) {

    function getRequestToken() {
        return $_SESSION['ZENSS_REQUEST_TOKEN'][genRequestAddress()];
    }
}

/**
 * check a valid of request
 * @param $val
 * @return bool
 */
if (!function_exists('confirmRequest')) {

    function confirmRequest($val) {
        $token_address = genRequestAddress();
        if (isset($_SESSION['ZENSS_REQUEST_TOKEN'][$token_address]) && $_SESSION['ZENSS_REQUEST_TOKEN'][$token_address] == $val) {
            return true;
        }
        return false;
    }
}

/**
 * generate a session for login
 * @param $data_user
 * @param int $cookie_time
 */
if (!function_exists('_gen_session_login')) {

    function _gen_session_login($data_user, $cookie_time = 0) {
        $user_id_code = $data_user['id'] + ZEN_WORKID;
        $_SESSION['ZENSS_USER_ID'] = base64_encode($user_id_code);
        $_SESSION['ZENSS_LOGIN_TOKEN'] = md5($data_user['password']);
        genRequestToken();
        if (is_numeric($cookie_time) && $cookie_time != 0) {
            setcookie('ZENCK_USER_ID', $_SESSION['ZENSS_USER_ID'], time() + $cookie_time, "/");
            setcookie('ZENCK_LOGIN_TOKEN', $_SESSION['ZENSS_LOGIN_TOKEN'], time() + $cookie_time, "/");
        }
    }
}

/**
 * decode user id
 * @param $user_id_code
 * @return string
 */
if (!function_exists('_decode_user_id')) {

    function _decode_user_id($user_id_code) {
        return base64_decode($user_id_code) - ZEN_WORKID;
    }
}

/**
 * generate login token
 * @return string
 */
if (!function_exists('_gen_login_token')) {

    function _gen_login_token() {
        return md5(client_ip() . '-' . client_user_agent());
    }
}

if (!function_exists('_clean_user_data_log')) {
    function _clean_user_data_log()
    {
        global $registry;
        if (isset($_SESSION['ZENSS_USER_ID']))
            unset($_SESSION['ZENSS_USER_ID']);
        if (isset($_SESSION['ZENSS_LOGIN_TOKEN']))
            unset($_SESSION['ZENSS_LOGIN_TOKEN']);
        if (isset($_COOKIE['ZENCK_USER_ID']))
            unset($_COOKIE['ZENCK_USER_ID']);
        if (isset($_COOKIE['ZENCK_LOGIN_TOKEN']))
            unset($_COOKIE['ZENCK_LOGIN_TOKEN']);
        unset($registry->user);
    }
}

/**
 * reload user data
 * @param int $uid
 * @return bool
 */
if (!function_exists('_reload_user_data')) {

    function _reload_user_data($uid = 0)
    {
        global $registry;
        if (empty($uid)) $uid = $registry->user['id'];
        return _load_user($uid);
    }
}

/**
 * load the use data
 *
 * @param $uid
 * @return bool
 */
if (!function_exists('_load_user')) {

    function _load_user($uid)
    {
        global $registry;
        $query = $registry->db->query("SELECT * FROM " . tb() . "users where id='$uid'");
        if ($registry->db->num_row($query) == 1) {
            $row = $registry->db->fetch_array($query);
            $row = _handle_user_data($row);
            return $registry->db->sqlQuoteRm($row);
        }
        return false;
    }
}

/**
 * update login
 * update time login
 * update last ip login
 *
 * @param $uid
 */
if (!function_exists('_update_login')) {

    function _update_login($uid)
    {
        global $registry;
        $registry->db->query("UPDATE " . tb() . "users SET
        `last_login`='" . time() . "',
        `last_ip` = '" . client_ip() . "' where `id`='$uid'");
    }
}

/**
 * @param $data
 * @return mixed
 */
if (!function_exists('_handle_user_data')) {

    function _handle_user_data($data) {
        $u = $data;
        if (isset($data['avatar'])) {
            $u['full_avatar'] = $data['avatar'] ? _URL_FILES . '/account/avatars/' . $data['avatar'] : _URL_FILES_SYSTEMS . '/images/default/avatar.png';
        }
        if (isset($u['birth'])) {
            $u['birth'] = (int) $u['birth'];
        }
        if (isset($u['security_code'])) {
            $u['security_code'] = unserialize($u['security_code']);
        }
        if (isset($u['smiles'])) {
            $u['smiles'] = unserialize($u['smiles']);
        }
        return $u;
    }
}

/**
 * @param $uid
 * @param string $name
 * @return bool
 */
if (!function_exists('still_valid_security_code')) {

    function still_valid_security_code($uid, $name = '')
    {
        global $registry;
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        if (empty($uid)) {
            return false;
        }
        $user = $registry->model->get('account')->get_user_data($uid);
        if (!empty($user)) {
            if (isset($user['security_code'][$name])) {
                if (empty($user['security_code'][$name]['time_expired']) || empty($user['security_code'][$name]['time_start'])) {
                    return true;
                } else {
                    if (time() - $user['security_code'][$name]['time_start'] > $user['security_code'][$name]['time_expired']) {
                        return false;
                    }
                    return true;
                }
            }
            return false;
        }
        return false;
    }
}

/**
 * @param $uid
 * @param $name
 * @param $value
 * @param bool $expired
 * @return bool
 */
if (!function_exists('set_security_code')) {

    function set_security_code($uid, $name, $value, $expired = false)
    {
        global $registry;
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        if (empty($name) || empty($uid) || empty($value)) {
            return false;
        }
        $user = $registry->model->get('account')->get_user_data($uid);
        if (empty($user)) {
            return false;
        }
        $data = $user['security_code'];
        $arr['code'] = $value;
        $arr['time_start'] = time();
        $arr['time_expired'] = textToTime($expired);
        $data[$name] = $arr;
        $update['security_code'] = serialize($data);
        if ($registry->model->_update_user($uid, $update)) {
            return true;
        }
        return false;
    }
}


/**
 * return online status
 * by default, time hold online is 180s (3m)
 * @param $time
 * @return bool
 */
if (!function_exists('is_online')) {

    function is_online($time = 0, $timeHold = 180)
    {
        global $registry;
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        if (empty($time)) {
            $time = $registry->user['last_login'];
        }
        $time = (int)$time;
        if ($time > time()) return false;
        if (empty($timeHold)) $timeHold = 180;//3m
        $t = hook(ZPUBLIC, 'time_hold_online', $timeHold);
        if (time() - $time > $t) return false;
        return true;
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

    function &load_library($class, $options = array(
                                     'directory' => 'libraries',
                                     'module' => '',
                                     'cache' => true
                                 )
    ) {
        global $registry;
        static $_classes = array();
        if (empty($options['directory'])) {
            $options['directory'] = 'libraries';
        }
        if (!isset($options['cache'])) {
            $options['cache'] = true;
        }
        /**
         * Does the class exist?  If so, we're done...
         */
        if (isset($_classes['obj'][$class]) && $_classes['options'][$class] == $options && $options['cache']) {
            return $_classes['obj'][$class];
        }
        $name = FALSE;
        $arr_inc = array(__SITE_PATH, __SITE_PATH . '/systems');

        if (!empty($options['module'])) {
            $arr_inc = array_merge(array(__MODULES_PATH . '/' . $options['module']), $arr_inc);
        } else {
            if (defined('MODULE_DIR')) {
                $arr_inc = array_merge(array(MODULE_DIR), $arr_inc);
            }
        }
        /**
         * Look for the class first in the module directory
         * then in the local libraries folder
         * end in the native system/libraries folder
         */
        foreach ($arr_inc as $path) {
            $file_name = $class . '.lib';
            $path_class = $path . '/' . $options['directory'] . '/' . $file_name . '.php';
            $path_sub_class = $path . '/' . $options['directory'] . '/' . $class . '/' . $file_name . '.php';
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
        if (isset($options['init_data'])) {
            $object = new $name($options['init_data']);
        } else $object = new $name();
        if (method_exists($object, 'setRegistry')) {
            $object->setRegistry($registry);
        }
        if ($options['cache']) {
            $_classes['obj'][$class] = $object;
            $_classes['options'][$class] = $options;
        }
        return $object;
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
        if ($class != '') $_is_loaded[strtolower($class)] = $class;
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

    function load_helper($helper, $options = array('directory' => 'helpers', 'module' => ''))
    {
        global $registry;
        if (empty($options['directory'])) {
            $options['directory'] = 'helpers';
        }
        $name = FALSE;
        $arr_inc = array(__SITE_PATH, __SYSTEMS_PATH);
        if (!empty($options['module'])) {
            $arr_inc = array_merge(array(__MODULES_PATH . '/' . $options['module']), $arr_inc);
        } else {
            if (defined('MODULE_DIR')) {
                $arr_inc = array_merge(array(MODULE_DIR), $arr_inc);
            }
        }
        /**
         * Look for the helper first in the module directory
         * then in the local helpers folder
         * end in the native system/helpers folder
         */
        foreach ($arr_inc as $path) {
            $file_name = $helper . '.helper';
            $path_helper = $path . '/' . $options['directory'] . '/' . $file_name . '.php';
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

    function load_apps($path, $apps, $thisObj = null)
    {
        global $app, $obj, $registry;
        /**
         * load security library
         */
        $security = load_library('security');
        $app = $apps;
        if ($thisObj) $obj = $thisObj;
        else $obj = $registry;

        if (empty($apps[0])) {
            $apps[0] = 'index';
        }
        if (isset($apps[0])) {
            $pos = strpos($path, __MODULES_PATH);
            if (is_bool($pos) && $pos == false) {
                $path = __MODULES_PATH . '/' . ltrim($path, '/');
            }
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

    function get_apps($path, $router = ROUTER, $both_index = false) {
        /**
         * check path.
         */
        $pos = strpos($path, __MODULES_PATH);
        if (is_bool($pos) && $pos == false) {
            $path = __MODULES_PATH . '/' . ltrim($path, '/');
        }
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

    function get_extend_apps($router = false)
    {
        global $registry;
        static $_static_function;
        if (isset($_static_function['get_extend_apps'][$router])) {
            return $_static_function['get_extend_apps'][$router];
        }
        /**
         * set default icon
         */
        $default_icon = 'fa fa-flag';
        /**
         * load helper: fhandle
         */
        load_helper('fhandle');
        $arr_mods = scan_modules(false);
        $out = array();

        foreach ($arr_mods as $mod_name => $arr_info) {
            $blog_set = $registry->settings->get($mod_name);
            if (isset($blog_set->setting['extends']) && is_array($blog_set->setting['extends'])) {
                foreach ($blog_set->setting['extends'] as $url => $extend) {
                    if (isset($extend['router']) && $extend['router'] == $router) {
                        if (!isset($extend['name'])) {
                            $mod_title = $mod_name . ' (' . $url . ')';
                        } else {
                            $mod_title = $extend['name'];
                        }
                        $mod['icon'] = isset($extend['icon']) ? $extend['icon'] : $default_icon;
                        $mod['name'] = $mod_title;
                        $mod['des'] = $extend['des'];
                        $mod['url'] = $url;
                        $mod['title'] = $mod_title;
                        //$mod['full_url'] = HOME . '/' . $extend['router'] . '?appFollow=' . $mod_name . '/' . $url;
                        $mod['full_url'] = genUrlAppFollow($mod_name . '/' . $url);
                        $mod['full_extend_url'] = HOME . '/' . $extend['router'] . '?appFollow=' . $mod_name . '/' . $url;
                        $out[] = $mod;
                    }
                }
            }
        }
        $_static_function['get_extend_apps'][$router] = $out;
        return $out;
    }
}

/**
 * @param $path
 * @param bool $both_index
 * @return array
 */
if (!function_exists('get_apps_from_path')) {

    function get_apps_from_path($path, $router, $both_index = false)
    {
        global $registry;
        static $_static_function;
        if (isset($_static_function['get_apps_from_path'][$path . '-' . $router . '-' . $both_index])) {
            return $_static_function['get_apps_from_path'][$path . '-' . $router . '-' . $both_index];
        }
        $obj = $registry;
        /**
         * load libraries
         */
        $parse = load_library('parse');
        $permission = load_library('permission');
        $tmp = array();
        $menus = array();

        /**
         * set default icon
         */
        $default_icon = 'icon-flag';

        $router = trim($router, '/');
        $currRouter = getRouter();
        $hash_router = explode('/', $router);
        $router_action = trim(strstr($router, '/'), '/');
        $modSet = $registry->settings->get($hash_router[0]);
        if (!empty($modSet->setting['extends'][$router_action]) && $modSet->setting['extends'][$router_action]['router'] == $currRouter) {
            $enable_url_follow = true;
        } else $enable_url_follow = false;

        $list_path = explode('/', $path);
        $sub_name = end($list_path);
        $ignored = array('.', '..', '.svn', '.htaccess');
        if ($both_index == false) $ignored[] = 'index.' . $sub_name . '.php';

        $files = @scandir($path);
        if (empty($files))
            return array();

        foreach ($files as $arr_key => $file) {
            if (in_array($file, $ignored)) {
                unset ($files[$arr_key]);
                continue;
            }
            $file_path = $path . '/' . $file;
            $is_file = is_file($file_path);
            if ($is_file) {
                $fist_file_getcmt = $file_path;
            } else {
                $fist_file_getcmt = $file_path . '/index.' . $file . '.php';
            }
            if (!file_exists($fist_file_getcmt)) {
                continue;
            }
            $ini_file_cmt = $parse->ini_php_file_comment($fist_file_getcmt);
            if (is_array($ini_file_cmt)) {
                $str[$file_path] = $ini_file_cmt;
            }
            if (isset($str[$file_path]['folder_name'])) {
                $str[$file_path]['name'] = $str[$file_path]['folder_name'];
            }
            $str[$file_path]['url'] = str_replace('.' . $sub_name . '.php', '', $file);
            $str[$file_path]['router'] = $router . '/' . $str[$file_path]['url'];
            if ($enable_url_follow == true) {
                $str[$file_path]['full_url'] = REAL_HOME . '/' . $currRouter . '?appFollow=' . $str[$file_path]['router'];
            } else {
                $str[$file_path]['full_url'] = REAL_HOME . '/' . $str[$file_path]['router'];
            }
            if (isset($str[$file_path]['name'])) {
                if (isset($str[$file_path]['position']))
                    $pos = $str[$file_path]['position'];
                else $pos = 99999;
                $tmp[$file_path] = $pos;
            } else {
                unset($str[$file_path]);
                continue;
            }
            if (!$is_file) {
                $dir_path = $file_path; $dir_name = $file; $tmp_sub = array();
                $index_file = $fist_file_getcmt;
                $handlet = glob($dir_path . '/*.php');
                foreach ($handlet as $kdir => $file_path_in_dir) {
                    if (!is_file($file_path_in_dir)) {
                        unset ($handlet[$kdir]);
                        continue;
                    }
                    if ($file_path_in_dir != $index_file) {
                        $str_sub[$file_path_in_dir] = $parse->ini_php_file_comment($file_path_in_dir);
                        if (!empty($str_sub[$file_path_in_dir]['folder_name'])) {
                            $str_sub[$file_path_in_dir]['name'] = $str_sub[$file_path_in_dir]['folder_name'];
                        }
                        if (!empty($str_sub[$file_path_in_dir]['name'])) {
                            $file_name_in_dir = end(explode('/', $file_path_in_dir));
                            if (empty($str_sub[$file_path_in_dir]['title'])) {
                                $str_sub[$file_path_in_dir]['title'] = $str_sub[$file_path_in_dir]['name'];
                            }
                            $str_sub[$file_path_in_dir]['url'] = str_replace('.' . $dir_name . '.php', '', $file_name_in_dir);
                            $str_sub[$file_path_in_dir]['router'] = $router . '/' . $dir_name . '/' . $str_sub[$file_path_in_dir]['url'];
                            if ($enable_url_follow == true) {
                                $str_sub[$file_path_in_dir]['full_url'] = REAL_HOME . '/' . $currRouter . '?appFollow=' . $str_sub[$file_path_in_dir]['router'];
                            } else {
                                $str_sub[$file_path_in_dir]['full_url'] = REAL_HOME . '/' . $str_sub[$file_path_in_dir]['router'];
                            }
                            if (isset($str_sub[$file_path_in_dir]['position'])) {
                                $pos = $str_sub[$file_path_in_dir]['position'];
                            } else $pos = 99999;
                            $tmp_sub[$file_path_in_dir] = $pos;
                        }
                    }
                }
                /**
                 * sort menu by position
                 */
                asort($tmp_sub);
                $handlet = array_keys($tmp_sub);
                foreach ($handlet as $file_path_in_dir) {
                    $str[$dir_path]['sub_menus'][] = $str_sub[$file_path_in_dir];
                }
            }
        }
        /**
         * sort by position
         */
        asort($tmp);
        $list_menu_path = array_keys($tmp);
        foreach ($list_menu_path as $menu_path) {
            $allow_access = true;
            if (isset($str[$menu_path]['allow_access'])) {
                $lists_access = explode(',', $str[$menu_path]['allow_access']);
                foreach ($lists_access as $key => $perm) {
                    $lists_access[$key] = trim($perm);
                }
                if (!in_array($obj->user['perm'], $lists_access) && !$permission->is_admin()) {
                    $allow_access = false;
                }
            }
            if ($allow_access == true) {
                $str[$menu_path]['icon'] = isset($str[$menu_path]['icon']) ? $str[$menu_path]['icon'] : $default_icon;
                $menus[] = $str[$menu_path];
            }
        }
        $_static_function['get_apps_from_path'][$path . '-' . $router . '-' . $both_index] = $menus;
        return $menus;
    }
}

/**
 * return list modules in module folder
 *
 * @return mixed
 */
if (!function_exists('get_list_modules')) {

    function getActiveModule()
    {
        global $zen;
        static $_static_function;
        if (!empty ($_static_function['get_list_modules'])) {
            return $_static_function['get_list_modules'];
        }
        $cache_file = __MODULES_PATH . '/modules.dat';
        $data = file_get_contents($cache_file);
        $data = unserialize($data);
        if (empty($data)) {
            $data['admin'] = array();
        }
        /**
         * auto load protected module
         */
        $list_protected = $zen['config']['modules_protected'];
        foreach ($list_protected as $mod_protected) {
            if (!in_array($mod_protected, array_keys($data))) {
                $data[$mod_protected] = array();
            }
        }
        $_static_function['get_list_modules'] = $data;
        return $data;
    }
}

if (!function_exists('isModuleActivated')) {

    /**
     * Check module is activated
     * @param string $module
     * @return bool
     */
    function isModuleActivated($module) {
        $listActivated = getActiveModule();
        $listMod = array_keys($listActivated);
        if (in_array($module, $listMod)) {
            return true;
        } else return false;
    }
}
/**
 * the new way to get model of a module
 * @param bool $name
 * @return mixed
 */
if (!function_exists('model')) {

    function model($name = false) {
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

    function run_hook($module = ZPUBLIC, $hook_name, $hook_run, $weight = null)
    {
        if (is_null($weight)) {
            $GLOBALS['hook'][$module][$hook_name][] = $hook_run;
        } else {
            $weight = (int)$weight;
            if (!isset($GLOBALS['hook'][$module][$hook_name][$weight])) {
                $GLOBALS['hook'][$module][$hook_name][$weight] = $hook_run;
            } else {
                $GLOBALS['hook'][$module][$hook_name][] = $hook_run;
            }
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

    function hook($module = ZPUBLIC, $name, $data = false, $opt = array()) {
        global $registry;
        if ($module == ZPUBLIC) {
            return $registry->hook->call($name, $data, $opt);
        }
        $hook = $registry->hook->get($module);
        return $hook->loader($name, $data, $opt);
    }
}

/**
 * @param $name
 * @param bool $data
 * @param bool $protected
 * @return bool
 */
if (!function_exists('phook')) {

    function phook($name, $data = false, $opt = array()) {
        global $registry;
        return $registry->hook->call($name, $data, $opt);
    }
}

/**
 * @param $module
 * @param $name
 * @param $data
 * @param $append_data
 * @return mixed
 */
if (!function_exists('hook_append')) {

    function hook_append($module, $name, $data, $append_data) {
        global $registry;
        return $registry->hook->append($module, $name, $data, $append_data);
    }
}

/**
 *
 * @param string $link
 * @param string $link_title
 * @param string $add
 * @return string
 */
if (!function_exists('url')) {
    function url($link = '', $link_title = '', $add = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }

        if (isset($link)) {
            if (!isset($link_title)) {
                $link_title = $link;
            }
            if (preg_match("/^http/", $link)) {
                $url = $link;
            } elseif (preg_match("~^/~", $link)) {
                $url = HOME . $link;
            } else {
                if (preg_match('/^(\?|&)/', $link)) {
                    $url = HOME . $_SERVER['REQUEST_URI'] . $link;
                } else {
                    $url = HOME . '/' . $link;
                }
            }
            return '<a href="' . $url . '" ' . $add . '>' . $link_title . '</a>';
        }
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

    function register_widget_group($widget_data = array()) {
        if (empty ($widget_data['name'])) {
            return false;
        }
        if (isset ($GLOBALS['widgets'][$widget_data['name']])) {
            return false;
        }
        $GLOBALS['widgets'][$widget_data['name']]['config'] = $widget_data;
    }
}

if (!function_exists('add_widget')) {

    /**
     * @param $name
     * @param array $widget_data
     */
    function add_widget($name, $widget_data = array()) {
        $GLOBALS['widgets'][$name]['list'][] = array(
            'wg' => $name,
            'title' => isset($widget_data['title'])? $widget_data['title'] : '',
            'content' => $widget_data['content'],
            'template' => isset($widget_data['template']) ? $widget_data['template']:TEMPLATE
        );
    }
}

if (!function_exists('widget_group')) {

    function widget_group($name) {
        global $registry;
        if (!isset($GLOBALS['widgets'][$name]) || empty ($GLOBALS['widgets'][$name]['config']) || !is_array($GLOBALS['widgets'][$name]['config'])) {
            return false;
        }

        /**
         * get data widget group
         */
        $wConfig = $GLOBALS['widgets'][$name]['config'];

        /**
         * Check the widget display
         */
        if (isset($wConfig['display'])) {
            if (!is_array($wConfig['display'])) {
                if (!is($wConfig['display'], ONLY_THIS_PERM)) {
                    return;
                }
            } else {
                $show = false;
                foreach ($wConfig['display'] as $who_allow) {
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

        /**
         * Get widget from database
         */
        $listFromDB = model('widget')->get_widget_group($name, TEMPLATE);
        if (isset($GLOBALS['widgets'][$name]['list']) && is_array($GLOBALS['widgets'][$name]['list'])) {
            $GLOBALS['widgets'][$name]['list'] = array_merge($listFromDB, $GLOBALS['widgets'][$name]['list']);
        } else $GLOBALS['widgets'][$name]['list'] = $listFromDB;
        $out = '';
        foreach ($GLOBALS['widgets'][$name]['list'] as $widget) {
            $out .= $wConfig['start'];
            /**
             * print title widget
             */
            if (!empty ($widget['title'])) {
                $out .= $wConfig['title']['start'] . h_decode($widget['title']) . $wConfig['title']['end'];
            }
            /**
             * print content widget
             */
            if (!empty ($widget['content'])) {
                $out .= $wConfig['content']['start'] . h_decode($widget['content']) . $wConfig['content']['end'];
            }

            /**
             * Render widget
             */
            if(!empty($widget['callback'])) {
                $ex = explode('::', $widget['callback']);
                $obj = model('widget')->get_widget_callback($ex[0]);
                /**
                 * make sure this widget obj is exists
                 */
                if ($obj !== NULL) {
                    if (method_exists($obj, $ex[1])) {
                        $values = $obj->$ex[1]();
                        if (!is_array($values)) $values = array();
                        $replace = array();
                        foreach ($values as $key=>$value) {
                            $replace['{'.$key.'}'] = $value;
                        }
                        /**
                         * replace value
                         */
                        $out = strtr($out, $replace);
                    }
                }
            }

            $out .= $wConfig['end'];
        }
        echo $out;
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

if (!function_exists('sysConfig')) {

    /**
     * Load system config from ZenConfig_Main.php
     * @param $key
     * @return mixed
     */
    function sysConfig($key)
    {
        global $zen;
        /**
         * check sys config exist
         */
        if (isset($zen['config'][$key])) {
            return $zen['config'][$key];
        } else return false;
    }
}

/**
 * @access public
 * @param key config
 * Load system config from file config.php in current template
 */
if (!function_exists('tplConfig')) {

    function tplConfig($key, $template = '')
    {
        global $template_config, $registry;
        if (empty($template)) {
            $selectTemp = TEMPLATE;
        } else $selectTemp = $template;
        /**
         * get config from database
         */
        $dbConfig = $registry->config->getTemplateConfig($selectTemp);
        if (isset($dbConfig[$key])) return $dbConfig[$key];
        /**
         * check template config exist
         */
        if (isset($template_config[$key])) {
            return $template_config[$key];
        }
    }
}

/**
 * Load config from database for module
 * @param $key
 * @param $module
 * @return mixed
 */
if (!function_exists('modConfig')) {

    function modConfig($key, $module = '') {
        global $registry;
        static $_static_functions;
        if (defined('MODULE_NAME') && empty($module)) {
            $module = MODULE_NAME;
        }
        if (isset($_static_functions['modConfig'][$key . '-' . $module])) {
            return $_static_functions['modConfig'][$key . '-' . $module];
        }
        /**
         * get config from database
         */
        $out = $registry->config->getModuleConfig($module, $key);
        $_static_functions['modConfig'][$key . '-' . $module] = $out;
        return $out;
    }
}

/**
 * Load system config from database
 * @access public
 * @param key config
 */
if (!function_exists('dbConfig')) {

    function dbConfig($key) {
        global $zen;
        if (isset($zen['config']['fromDB'][$key])) {
            return $zen['config']['fromDB'][$key];
        } else return false;
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
        global $zen, $registry;
        $error_number = $num;
        $error_desc = '';
        $error_html = '';
        switch ($error_number) {
            default:
                $error_name = 'Not found!';
                $error_desc = 'Sorry, this application does not exist';
                break;
            case 403:
                $error_name = "Forbidden!";
                $error_desc = "You don't have permission to access on this page";
                break;

            case 404:
                $error_name = 'Not found!';
                $error_desc = 'Sorry, This page does not exist';
                break;

            case 405:
                $error_name = 'Not found!';
                $error_desc = 'Sorry, This file has been deleted by the manager';
                break;

            case 503:
                $error_name = 'Access denied';
                $error_desc = 'You don\'t have permission to access on this page';
                break;

            case 500:
                $error_name = 'Server error';
                $error_desc = 'Something went wrong, We are fixing it! Please come back in a while.';
                break;

            case 505:
                $error_name = 'Code error';
                $error_desc = 'Sorry, This action does not exist. If you are as administrator please review codes';
                break;

            case 600:
                /*
                $error_name = 'Password 2';
                $error_desc = 'Please confirm password 2';
                $error_html = '<form class="form-horizontal" method="POST">
                    <div class="input-group">
                      <input type="password" name="zen_verity_access" class="form-control" placeholder="Password 2...">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit" name="submit_verify"><i class="glyphicon glyphicon-log-in"></i></button>
                      </span>
                    </div><!-- /input-group -->
                </form>';
                */
                ZenView::set_title('Nhập mật khẩu cấp 2');
                $view = new ZenView($registry);
                $view->show('page:admin-lock');
                exit;
                break;

            case 1000:
                $error_name = 'Template folder does not exists';
                $error_desc = 'Template folder does not exists';
                break;

            case 1001:
                $error_name = 'Template file does not exists';
                $error_desc = 'Template file does not exists';
                break;

            case 1005:
                $error_name = 'Map file does not exists';
                $error_desc = 'Map file does not exists';
                break;

            case 2000:
                $error_name = 'Can not find the settings file';
                break;

            case 2001:
                $error_name = 'Class settings does not exists';
                $error_desc = 'Class settings does not exists';
                break;
        }

        if ($msg) $error_name = $msg;
        $error['number'] = $error_number;
        $error['name'] = $error_name;
        $error['desc'] = $error_desc;
        $error['html'] = $error_html;

        $default_tpl = __FILES_PATH . '/systems/default/pages/error/error.tpl.php';
        if (defined('_PATH_TEMPLATE')) {
            $fileCheck[] = _PATH_TEMPLATE . '/pages/error/error-' . $error['number'] . '.tpl.php';
            $fileCheck[] = _PATH_TEMPLATE . '/pages/error/error.tpl.php';
            foreach($fileCheck as $f) {
                if (file_exists($f)) {
                    $file = $f; break;
                }
            }
        } else $file = $default_tpl;
        if (!isset($file)) $file = $default_tpl;
        include $file;
        exit;
    }
}

/**
 * @access public
 * get template name
 */
if (!function_exists('getTemplate')) {

    function getTemplate()
    {
        global $zen, $registry;
        static $_static_function;
        if ($_static_function['getTemplate']) return $_static_function['getTemplate'];

        if (!empty($_SESSION['ss_review_template'])) {
            return $_SESSION['ss_review_template'];
        }
        $device = load_library('DDetect');
        if (!empty($registry->setting['template'])) {
            $templates = $registry->setting['template'];
        } else {
            if (!empty($zen['config']['fromDB']['templates'])) {
                $templates = $zen['config']['fromDB']['templates'];
            } else {
                $templates = getActiveTemplate();
            }

            if (empty($templates)) {
                $templates['Mobile'] = 'zencms-default';
                $templates['other'] = 'zencms-default';
            }
        }
        if ($device->isMobile()) {
            $out = $templates['Mobile'];
        } else {
            $out = $templates['other'];
        }

        if ($templates) foreach ($templates as $os => $value) {
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
            $out = 'zencms-default';
        }
        $_static_function['getTemplate'] = $out;
        return $out;
    }
}

/**
 * get template list activated from templates.dat
 * @return array
 */
if (!function_exists('getActiveTemplate')) {

    function getActiveTemplate($reload = false) {
        static $_static_function;
        if (!empty($_static_function['getActiveTemplate']) && $reload == false) {
            return $_static_function['getActiveTemplate'];
        }
        $file = __TEMPLATES_PATH . '/templates.dat';
        if (!file_exists($file)) {
            $out = array();
        } else {
            $data = file_get_contents($file);
            $dataArr = unserialize($data);
            if (!is_array($dataArr)) {
                $out = array();
            } else {
                $out = $dataArr;
            }
        }
        $_static_function['getActiveTemplate'] = $out;
        return $out;
    }
}

if (!function_exists('is_module_activate')) {
    /**
     * @param string $module_name
     * @return bool
     */
    function is_module_activate($module_name) {
        load_helper('fhandle');
        $list_handle = scan_modules(false);
        if (in_array($module_name, array_keys($list_handle))) {
            return true;
        }
        return false;
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

    function is($perm, $only_this_perm = false) {
        return is_perm($perm, $only_this_perm);
    }
}


if (!function_exists('set_global_msg')) {

    function set_global_msg($msg = '') {
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
if (!function_exists('tempDir')) {

    function tempDir($prefix = '_Z_')
    {
        $dir = __FILES_PATH . '/systems/tmp';
        $tempFile = tempnam($dir, $prefix);
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        $ok = mkdir($tempFile);
        if ($ok) {
            if (is_dir($tempFile)) {
                return $tempFile;
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
                } else h($var[$id]);
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

    function h_decode($var = '', $flags = ENT_QUOTES) {
        if (is_array($var)) {
            foreach ($var as $id => $str) {
                if (!is_array($var[$id])) {
                    $var[$id] = htmlspecialchars_decode($str, $flags);
                } else h_decode($var[$id]);
            }
            return $var;
        }
        return htmlspecialchars_decode($var, $flags);
    }
}

/**
 * redirect to last url
 * @return bool
 */
if (!function_exists('goBack')) {

    function goBack($returnUrl = false)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        $_get_back = ZenInput::get('back', true);
        if ($_get_back) {
            $urlBack = urldecode($_get_back);
            $valid = load_library('validation');
            /**
             * make sure this is a url
             */
            if ($valid->isValid('url', $urlBack)) {
                if (!$returnUrl)
                    redirect($urlBack);
                else return $urlBack;
            }
        }
    }
}

/**
 * return true if device is mobile
 * @return mixed
 */
if (!function_exists('is_mobile')) {

    function is_mobile()
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        /**
         * load DDetect library
         */
        $device = load_library('DDetect');
        return $device->isMobile();
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
                $msg = microTime_float() . "\r\n";
            }
        } elseif ($name == LOG_VERIFY_ACCESS) {
            $file_log = $dir_log . '/verify_access.log';
            if (!$msg) {
                if (isset($registry->user['username'])) {
                    $msg = $registry->user['username'] . ': ';
                } else $msg = '';
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

/**
 * @param array $tree
 * @param string $mixed
 * @return string
 */
if (!function_exists('display_tree')) {

    function display_tree($tree = array(), $mixed = '')
    {
        global $registry;
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        $temp = $registry->templateOBJ;
        $breadcrumb = $temp->getMap('breadcrumb');
        $out = '';
        if (is_array($tree)) {
            $i = 0;
            foreach ($tree as $url) {
                if ($url != '') {
                    $i++;
                    if ($i != count($tree)) $out .= $breadcrumb['item']['start'] . $url . $breadcrumb['item']['end'];
                    else $out .= $breadcrumb['item']['start'] . $url . $breadcrumb['item']['end'];
                }
            }
        }
        return $out;
    }
}

/**
 *
 * @param array $tree
 * @param string $mixed
 */
if (!function_exists('display_tree_modulescp')) {

    function display_tree_modulescp($tree = array(), $mixed = '')
    {
        global $registry;
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        /**
         * load permission library
         */
        $perm = load_library('permission');
        $perm->set_user($registry->user);
        if ($perm->is_super_manager()) {
            $kstree[] = url(HOME . '/admin', 'Admin CP');
            $kstree[] = url(HOME . '/admin/general/modulescp', 'Modules cpanel');
        }
        if (!empty($tree)) {
            return (isset($kstree) ? display_tree($kstree, $mixed) . $mixed : '') . display_tree($tree, $mixed);
        }
        return display_tree($kstree, $mixed);
    }
}

/**
 * function send mail user PHPmailer
 * @param bool $to_email
 * @param bool $subject
 * @param bool $content
 * @param bool $altbody
 * @return mixed
 */
if (!function_exists('send_mail')) {

    function send_mail($to_email = false, $subject = false, $content = false, $altbody = false)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        /**
         * load PHPmailer library
         */
        $mailer = load_library('PHPmailer');
        $mailer->IsSMTP();
        $mailer->IsHTML(true);
        $mailer->AddAddress($to_email, $to_email);
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $mailer->AltBody = $altbody;
        $mailer->WordWrap = 50;
        /**
         * action send mail
         */
        $send = $mailer->Send();
        if (!$send) set_global_msg($mailer->ErrorInfo);
        return $send;
    }
}