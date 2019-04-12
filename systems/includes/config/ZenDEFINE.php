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

define('ZEN_DEFAULT_PASSWORD', '3cba0fed4d2e42dfa7bba1eb5f0fddbe'); //zencms

define('LOG_VERIFY_ACCESS', 'LOG_VERIFY_ACCESS');

define('LOG_DDOS', 'LOG_DDOS');

define('_PUBLIC', '_PUBLIC');

define('_PRIVATE', '_PRIVATE');

define('_PROTECTED', '_PROTECTED');

define('APP', 'APP');

define('BACKGROUND', 'BACKGROUND');

/**
 * input form
 */
define('FILE_INPUT', 'FILE_INPUT');

define('TEXT_INPUT', 'TEXT_INPUT');

define('RADIO_INPUT', 'RADIO_INPUT');

define('CHECKBOX_INPUT', 'CHECKBOX_INPUT');

define('DIR', 'DIR');
define('FILE', 'FILE');
define('GET_CONTENT', 'GET_CONTENT');
define('NO_GET_CONTENT', 'NO_GET_CONTENT');
define('ONLY_CONTENT', 'ONLY_CONTENT');

define('BBCODE_HTML', 'BBCODE_HTML');
define('BBCODE', 'BBCODE');
define('HTML', 'HTML');
/**
 * define permission
 */
foreach (array_keys($system_config['user_perm']['key']) as $perm) {

    define('PERM_'.strtoupper($perm), $perm);
}

/**
 * define role
 */
foreach (array_keys($system_config['role']) as $role) {

    define('ROLE_'.strtoupper($role), $role);
}
/**
 * use in permission library
 */
define('ONLY_THIS_PERM', 'ONLY_THIS_PERM');