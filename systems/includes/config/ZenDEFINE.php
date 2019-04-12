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
define('ZEN_DEFAULT_PASSWORD', '3cba0fed4d2e42dfa7bba1eb5f0fddbe'); //zencms
define('LOG_VERIFY_ACCESS', 'LOG_VERIFY_ACCESS');
define('LOG_DDOS', 'LOG_DDOS');

define('ZPUBLIC', 'ZPUBLIC');
define('ZPRIVATE', 'ZPRIVATE');
define('ZPROTECTED', 'ZPROTECTED');

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
foreach (array_keys($zen['config']['user_perm']['key']) as $perm) {
    define('PERM_'.strtoupper($perm), $perm);
}

/**
 * define role
 */
foreach (array_keys($zen['config']['role']) as $role) {
    define('ROLE_'.strtoupper($role), $role);
}

/**
 * use in permission library
 */
define('ONLY_THIS_PERM', 'ONLY_THIS_PERM');