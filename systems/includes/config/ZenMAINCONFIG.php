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

/**
 * Prefix database
 */
$zen['config']['table_prefix'] = 'zen_cms_';

/**
 * Router default when router is empty
 */
$zen['config']['default_router'] = 'blog';

/**
 * Router when error
 */
$zen['config']['default_router_error'] = 'error';

/**
 * rewrite url
 */
$zen['config']['rewrite_url'] = array(

    '/^dl\/hightspeed\/(.+)\/([a-zA-Z0-9]+)\/(.+)$/' => 'download/product/$2/$1/$3', //forum

    '/^forum\/(.*)-([0-9]+)\.html(\/*)?$/' => 'forum/index/$2/$1', //forum

    '/^(.*)-([0-9]+)\.html(\/*)?$/' => 'blog/index/$2/$1', //blog

    '/^download-(file|link)-([0-9]+)(-((.*)\.([0-9a-zA-z_\-]+)))?$/' => 'download/$1/$2/$6/$4', //download

    '/^search((\-)?(.*)?)?$/' => 'search/index/$3', //search

    '/^sitemap\.?(xml|html)$/' => 'sitemap/$1' //sitemap

    );

/**
 * Module protected
 */
$zen['config']['modules_protected'] = array('_background', 'admin', 'update', 'login', 'error');

/**
 * Timezone
 */
$zen['config']['timezone'] = +7;

/**
 * Date format
 */
$zen['config']['date_format'] = 'd-m-Y';

/**
 * Time format
 */
$zen['config']['time_format'] = 'H:i';

/**
 * Max upload (kb)
 */
$zen['config']['max_file_size'] = 5242880;

/**
 * File extension allow upload
 */
$zen['config']['exts'][] = 'jar';
$zen['config']['exts'][] = 'sis';
$zen['config']['exts'][] = 'apk';
$zen['config']['exts'][] = 'ipa';
$zen['config']['exts'][] = 'nth';
$zen['config']['exts'][] = 'zip';
$zen['config']['exts'][] = 'rar';
$zen['config']['exts'][] = 'tar';
$zen['config']['exts'][] = 'gtar';
$zen['config']['exts'][] = 'gz';

$zen['config']['exts'][] = 'mp3';
$zen['config']['exts'][] = 'mp4';
$zen['config']['exts'][] = '3gp';
$zen['config']['exts'][] = 'mid';

$zen['config']['exts'][] = 'txt';
$zen['config']['exts'][] = 'php';
$zen['config']['exts'][] = 'php3';
$zen['config']['exts'][] = 'php4';
$zen['config']['exts'][] = 'sql';
$zen['config']['exts'][] = 'html';
$zen['config']['exts'][] = 'js';
$zen['config']['exts'][] = 'css';
$zen['config']['exts'][] = 'xml';
$zen['config']['exts'][] = 'xhtml';

$zen['config']['exts'][] = 'jpg';
$zen['config']['exts'][] = 'jpeg';
$zen['config']['exts'][] = 'jpe';
$zen['config']['exts'][] = 'png';
$zen['config']['exts'][] = 'gif';
$zen['config']['exts'][] = 'bmp';
$zen['config']['exts'][] = 'ico';


/**
 * setting user register
 */
$zen['config']['user']['username']['min_length'] = 3; // Min string username

$zen['config']['user']['username']['max_length'] = 30; // Max string username

$zen['config']['user']['password']['min_length'] = 5; // Min string password

$zen['config']['user']['password']['max_length'] = 50; // Max string password


/**
 * setting user permission
 */
$zen['config']['user_perm']['key'] = array(
    'guest' => 0,
    'user_lock' => 1,
    'user_need_active' => 2,
    'user_actived' => 3,
    'mod' => 4,
    'smod' => 5,
    'admin' => 6);
$zen['config']['user_perm']['name'] = array(
    'guest' => 'Khách',
    'user_lock' => 'Tài khoản đã bị khóa',
    'user_need_active' => 'Tài khoản cần kích hoạt',
    'user_actived' => 'Thành viên bình thường',
    'mod' => 'Mod',
    'smod' => 'Super Mod',
    'admin' => 'Admin');
$zen['config']['user_perm']['sign'] = array(
    'guest' => 'Khách',
    'user_lock' => '<s>Member</s>',
    'user_need_active' => 'Member',
    'user_actived' => 'Member!',
    'mod' => 'Mod!',
    'smod' => 'SMod!',
    'admin' => 'Adm!');
$zen['config']['user_perm']['color'] = array(
    'guest' => '#555',
    'user_lock' => '#555',
    'user_need_active' => '#555',
    'user_actived' => '#222',
    'mod' => '#00cc00',
    'smod' => '#993399',
    'admin' => '#ff0000');
/**
 * set roles for each position
 * For example:
 * With a 'manager' => 'mod', all positions have bigger importance 'mod' are the 'manager'
 */
$zen['config']['role'] = array ('manager' => 'mod',
                                'super_manager' => 'smod',
                                'admin' => 'admin');