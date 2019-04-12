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
 * initialization ZenConfig
 */
$config = new ZenConfig;

/**
 * get config from database
 */
$system_config['from_db'] = $config->loader();

/**
 * include the common file
 */
include_once __SITE_PATH . '/systems/includes/ZenCommon.php';

/**
 * get template
 */
$registry->template = get_template();

/**
 * set const
 */
define('_HOME', home());
define('_TEMPLATE', $registry->template);
define('_BASE_TEMPLATE', _HOME . '/templates/' . _TEMPLATE);

$temp = new ZenTemplate($registry);
$temp->setTemp($registry->template);
$temp->loader();

/**
 * get template config
 */
$template_config = $temp->template_config;
$registry->tpl_config = $template_config;

/**
 * include the functions file
 */
include_once __SYSTEMS_PATH . '/includes/ZenFunctions.php';

/**
 * Set const
 */
define('_BASE_TEMPLATE_IMG', _BASE_TEMPLATE.'/'.tpl_config('image_dir'));
define('_BASE_TEMPLATE_ICON', _BASE_TEMPLATE.'/'.tpl_config('icon_dir'));
define('_BASE_TEMPLATE_TPL', _BASE_TEMPLATE.'/' . __FOLDER_TPL_NAME);

define('_PATH_TEMPLATE', __TEMPLATES_PATH.'/'._TEMPLATE);
define('_PATH_TEMPLATE_TPL', _PATH_TEMPLATE.'/' . __FOLDER_TPL_NAME);

define('_URL_FILES', _HOME.'/files');
define('_URL_FILES_JS', _URL_FILES.'/js');
define('_URL_FILES_CSS', _URL_FILES.'/css');
define('_URL_FILES_SYSTEMS', _URL_FILES.'/systems');
define('_URL_FILES_IMAGES', _URL_FILES.'/images');
define('_URL_FILES_POSTS', _URL_FILES.'/posts');
define('_URL_FILES_FORUM', _URL_FILES.'/forum');

define('_URL_TEMPLATES', _HOME.'/templates');
define('_URL_MODULES', _HOME.'/modules');
/**
 * Load data user
 */
if (!empty($_SESSION['ss_user_id']) && !empty($_SESSION['ss_zen_token'])) {

    $user_hash_id = $_SESSION['ss_user_id'];
    $zen_token = $_SESSION['ss_zen_token'];

} elseif (!empty($_COOKIE['ck_user_id']) && !empty($_COOKIE['ck_zen_token'])) {

    $user_hash_id = $_COOKIE['ck_user_id'];
    $zen_token = $_COOKIE['ck_zen_token'];
    $_SESSION['ck_user_id'] = $_COOKIE['ck_user_id'];
    $_SESSION['ck_zen_token'] = $_COOKIE['ck_zen_token'];

}

$u = array();
$registry->user = array();

if (!empty($user_hash_id) && !empty($zen_token)) {

    $uid = base64_decode($user_hash_id) - ZEN_WORKID;

    $zen_token = md5(base64_decode($zen_token));

    $udata = _load_user($uid);

    if ($udata) {

        if ($zen_token == $udata['password']) {

            _update_login($uid);

            $registry->user = $udata;

        } else {

            _clean_user_data_log();
        }
    } else {

        _clean_user_data_log();
    }
} else {

    _clean_user_data_log();
}
/**
 * unregister globals var
 */
unregister_globals();

if (isset($registry->user['id']) ) {

    define('IS_MEMBER', TRUE);

} else {

    define('IS_MEMBER', FALSE);
}

load_helper('user');
