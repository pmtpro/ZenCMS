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

/** include the caching class ** */
require __SYSTEMS_PATH . '/init/init.php';

/** include the functions file */
include 'ZenFunctions.php';

/** include the common file */
require_once 'ZenCommon.php';

/***
 * initialize a registry object
 */
$registry = new ZenRegistry;

/**
 * load security lib
 */
$registry->security = load_library('security');

/**
 * init input class
 */
ZenInput::init();

/**
 * initialize a database object
 */
$zen['db'] = &ZenDatabase::getInstance();
$registry->db = $zen['db'];

/**
 * initialize ZenConfig
 */
$config = new ZenConfig($registry);
$registry->configOBJ = $config;

/**
 * get config from database
 */
$zen['config']['fromDB'] = $config->loader();


//define('HOME', isset($zen['config']['fromDB']['home']) ? $zen['config']['fromDB']['home'] : getHttpHost());
define('HOME', getHttpHost());
define('REAL_HOME', getHttpHost());
define('_URL_FILES', HOME . '/files');
define('_URL_FILES_SYSTEMS', _URL_FILES . '/systems');
define('_URL_FILES_POSTS', _URL_FILES . '/posts');
define('_URL_FILES_FORUM', _URL_FILES . '/forum');
define('_URL_TEMPLATES', HOME . '/templates');
define('_URL_MODULES', HOME . '/modules');

/**
 * validate user session
 */
if (!empty($_SESSION['ZENSS_USER_ID']) && !empty($_SESSION['ZENSS_LOGIN_TOKEN'])) {
    $user_hash_id = $_SESSION['ZENSS_USER_ID'];
    $zen_login_token = $_SESSION['ZENSS_LOGIN_TOKEN'];
    /**
     * validate user cookie
     */
} else if (!empty($_COOKIE['ZENCK_USER_ID']) && !empty($_COOKIE['ZENCK_LOGIN_TOKEN'])) {
    $user_hash_id = $_COOKIE['ZENCK_USER_ID'];
    $zen_login_token = $_COOKIE['ZENCK_LOGIN_TOKEN'];
    $_SESSION['ZENSS_USER_ID'] = $_COOKIE['ZENCK_USER_ID'];
    $_SESSION['ZENSS_LOGIN_TOKEN'] = $_COOKIE['ZENCK_LOGIN_TOKEN'];
    /**
     * gen token for a request
     */
    genRequestToken();
}

$registry->user = array();
/**
 * Validate user data
 */
if (!empty($user_hash_id) && !empty($zen_login_token)) {
    /**
     * decode user id
     */
    $uid = _decode_user_id($user_hash_id);
    /**
     * load user data
     */
    $uData = _load_user($uid);
    if ($uData) {
        /**
         * generator login token from password
         */
        if ($zen_login_token == md5($uData['password'])) {
            /**
             * update login
             */
            _update_login($uid);
            $registry->user = $uData;
        } else _clean_user_data_log();
    } else _clean_user_data_log();
} else _clean_user_data_log();

if (isset($registry->user['id']) ) {
    define('IS_MEMBER', TRUE);
} else define('IS_MEMBER', FALSE);

/**
 * load user helper
 */
load_helper('user');

/**
 * un register globals var
 */
unregister_globals();