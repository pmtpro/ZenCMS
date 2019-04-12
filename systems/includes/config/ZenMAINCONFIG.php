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
 * Prefix database
 */
$system_config['table_prefix'] = 'zen_cms_';

/**
 * Router default when router is empty
 */
$system_config['default_router'] = 'blog';

/**
 * Router when error
 */
$system_config['default_router_error'] = 'error';

/**
 * rewrite url
 */
$system_config['rewrite_url'] = array(

    '/^forum\/(.*)-([0-9]+)\.html(\/*)?$/' => 'forum/index/$2/$1', //forum

    '/^(.*)-([0-9]+)\.html(\/*)?$/' => 'blog/index/$2/$1', //blog

    '/^download-(file|link)-([0-9]+)(-((.*)\.([0-9a-zA-z_\-]+)))?$/' => 'download/$1/$2/$6/$4', //download

    '/^search((\-)?(.*)?)?$/' => 'search/index/$3', //search

    '/^sitemap\.?(xml|html)$/' => 'sitemap/$1' //sitemap

);

/**
 * Module protected
 */
$system_config['modules_protected']['APP'] = array('admin', 'update', 'login');
$system_config['modules_protected']['BACKGROUND'] = array('_background');

/**
 * Timezone
 */
$system_config['timezone'] = +7;

/**
 * Date format
 */
$system_config['date_format'] = 'd-m-Y';

/**
 * Time format
 */
$system_config['time_format'] = 'H:i';

/**
 * Max upload (kb)
 */
$system_config['max_file_size'] = 5242880;

/**
 * File extension allow upload
 */
$system_config['exts'][] = 'jar';
$system_config['exts'][] = 'sis';
$system_config['exts'][] = 'apk';
$system_config['exts'][] = 'ipa';
$system_config['exts'][] = 'nth';
$system_config['exts'][] = 'zip';
$system_config['exts'][] = 'rar';
$system_config['exts'][] = 'tar';
$system_config['exts'][] = 'gtar';
$system_config['exts'][] = 'gz';

$system_config['exts'][] = 'mp3';
$system_config['exts'][] = 'mp4';
$system_config['exts'][] = '3gp';
$system_config['exts'][] = 'mid';

$system_config['exts'][] = 'txt';
$system_config['exts'][] = 'php';
$system_config['exts'][] = 'php3';
$system_config['exts'][] = 'php4';
$system_config['exts'][] = 'sql';
$system_config['exts'][] = 'html';
$system_config['exts'][] = 'js';
$system_config['exts'][] = 'css';
$system_config['exts'][] = 'xml';
$system_config['exts'][] = 'xhtml';

$system_config['exts'][] = 'jpg';
$system_config['exts'][] = 'jpeg';
$system_config['exts'][] = 'jpe';
$system_config['exts'][] = 'png';
$system_config['exts'][] = 'gif';
$system_config['exts'][] = 'bmp';
$system_config['exts'][] = 'ico';


/**
 * setting user register
 */
$system_config['user']['username']['min_length'] = 3; // Min string username

$system_config['user']['username']['max_length'] = 30; // Max string username

$system_config['user']['password']['min_length'] = 5; // Min string password

$system_config['user']['password']['max_length'] = 50; // Max string password


/**
 * setting user permission
 */
$system_config['user_perm']['key'] = array(
    'guest' => 0,
    'user_lock' => 1,
    'user_need_active' => 2,
    'user_actived' => 3,
    'mod' => 4,
    'smod' => 5,
    'admin' => 6);
$system_config['user_perm']['name'] = array(
    'guest' => 'Khách',
    'user_lock' => 'Tài khoản đã bị khóa',
    'user_need_active' => 'Tài khoản cần kích hoạt',
    'user_actived' => 'Thành viên bình thường',
    'mod' => 'Mod',
    'smod' => 'Super Mod',
    'admin' => 'Admin');
$system_config['user_perm']['sign'] = array(
    'guest' => 'Khách',
    'user_lock' => '<s>Member</s>',
    'user_need_active' => 'Member',
    'user_actived' => 'Member!',
    'mod' => 'Mod!',
    'smod' => 'SMod!',
    'admin' => 'Adm!');
$system_config['user_perm']['color'] = array(
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
$system_config['role'] = array ('manager' => 'mod',
    'super_manager' => 'smod',
    'admin' => 'admin');

