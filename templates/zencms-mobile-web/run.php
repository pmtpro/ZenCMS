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
 * register new widget: header_top_menu
 */
register_widget_group(array(
    'name' => 'header_top_menu',
    'desc' => 'Thanh menu chính trên đầu website',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '',
        'end' => ''
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

/**
 * register new widget: blog_after_post_content
 */
register_widget_group(array(
    'name' => 'blog_after_post_content',
    'desc' => 'Dưới nội dưng bài viết',
    'start' => '<div class="panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">',
        'end' => '</h3>
            </div>
            <div class="box"></div>
        </div>'
    ),
    'content' => array(
        'start' => '<div class="panel-body">',
        'end' => '</div>'
    )
));

/**
 * register new widget: blog_after_list_post_in_folder
 */
register_widget_group(array(
    'name' => 'blog_after_list_post_in_folder',
    'desc' => 'Widget phía dưới danh sách bài viết thư mục',
    'start' => '<div class="panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">',
        'end' => '</h3>
            </div>
            <div class="box"></div>
        </div>'
    ),
    'content' => array(
        'start' => '<div class="panel-body">',
        'end' => '</div>'
    )
));

/**
 * Run blog index_top_new_get hook
 */
run_hook('blog', 'index_top_new_get', function($data) {
    return 'id, parent, uid, url, name, title, time, view, icon, des';
});